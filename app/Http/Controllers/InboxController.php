<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Message;
use App\Models\MessageAttachment;
use App\Models\MessageRecipient;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InboxController extends Controller
{
    // ── Helpers ────────────────────────────────────────────────────────────────

    /**
     * Return the courses available to the authenticated user
     * (enrolled courses for students, assigned courses for instructors/admins).
     */
    private function userCourses(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        if ($user->isInstructor() || $user->isAdmin()) {
            return $user->courses()->select('courses.id', 'courses.title', 'courses.code')->get();
        }

        // Students – pull through enrollments
        return Course::whereIn('id',
            Enrollment::where('user_id', $user->id)
                ->where('status', 'enrolled')
                ->pluck('course_id')
        )->select('id', 'title', 'code')->get();
    }

    // ── Main inbox view ────────────────────────────────────────────────────────

    /**
     * GET /inbox
     * Show the inbox shell. All conversation data is loaded via JS / Blade partials.
     */
    public function index(Request $request)
    {
        $user    = Auth::user();
        $folder  = $request->input('folder', 'inbox');   // inbox | sent | trash

        // Messages for the requested folder
        if ($folder === 'sent') {
            $messages = Message::with(['recipientUsers', 'course', 'attachments'])
                ->where('sender_id', $user->id)
                ->latest()
                ->get();
        } elseif ($folder === 'starred') {
            $messages = Message::with(['sender', 'course', 'attachments'])
                ->whereHas('recipients', function ($q) use ($user) {
                    $q->where('recipient_id', $user->id)
                      ->where('is_starred', true)
                      ->whereIn('folder', ['inbox', 'archived']);
                })
                ->latest()
                ->get();
        } elseif ($folder === 'archived') {
            $messages = Message::with(['sender', 'course', 'attachments'])
                ->whereHas('recipients', function ($q) use ($user) {
                    $q->where('recipient_id', $user->id)->where('folder', 'archived');
                })
                ->latest()
                ->get();
        } elseif ($folder === 'trash') {
            $messages = Message::with(['sender', 'course', 'attachments'])
                ->whereHas('recipients', function ($q) use ($user) {
                    $q->where('recipient_id', $user->id)->where('folder', 'trash');
                })
                ->latest()
                ->get();
        } else {
            // inbox (default)
            $messages = Message::with(['sender', 'course', 'attachments'])
                ->whereHas('recipients', function ($q) use ($user) {
                    $q->where('recipient_id', $user->id)->where('folder', 'inbox');
                })
                ->latest()
                ->get();
        }

        // Unread count for badge
        $unreadCount = MessageRecipient::where('recipient_id', $user->id)
            ->where('folder', 'inbox')
            ->whereNull('read_at')
            ->count();

        $courses = $this->userCourses();

        // Attach pivot data for recipient-owned folders
        if (in_array($folder, ['inbox', 'trash', 'starred', 'archived'])) {
            $pivots = MessageRecipient::where('recipient_id', $user->id)->get()->keyBy('message_id');
            $messages->each(function ($msg) use ($pivots) {
                $pivot = $pivots->get($msg->id);
                $msg->pivot_read_at   = optional($pivot)->read_at;
                $msg->pivot_is_starred = optional($pivot)->is_starred ?? false;
            });
        }

        return view('inbox', compact('messages', 'folder', 'unreadCount', 'courses'));
    }

    // ── Show a single message ──────────────────────────────────────────────────

    /**
     * GET /inbox/{message}
     * Return a JSON payload for Ajax or redirect to inbox with the message open.
     */
    public function show(Message $message)
    {
        $user = Auth::user();

        // Only sender or recipient may view
        $isRecipient = $message->recipients()->where('recipient_id', $user->id)->exists();
        $isSender    = $message->sender_id === $user->id;

        if (!$isRecipient && !$isSender) {
            abort(403);
        }

        // Mark as read if recipient hasn't read yet
        if ($isRecipient) {
            $message->recipients()
                ->where('recipient_id', $user->id)
                ->whereNull('read_at')
                ->update(['read_at' => now()]);
        }

        $message->load(['sender', 'course', 'recipientUsers', 'attachments']);

        return response()->json($message);
    }

    // ── Send a message ─────────────────────────────────────────────────────────

    /**
     * POST /inbox
     * Validate and persist a new message + attachments + recipient rows.
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'       => 'nullable|exists:courses,id',
            'recipient_ids'   => 'required|array|min:1',
            'recipient_ids.*' => 'exists:users,id',
            'subject'         => 'nullable|string|max:255',
            'body'            => 'required|string',
            'send_individual' => 'nullable|boolean',
            'attachments'     => 'nullable|array',
            'attachments.*'   => 'file|max:20480|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,png,jpg,jpeg,gif,zip',
        ]);

        $user = Auth::user();

        DB::transaction(function () use ($request, $user) {
            $message = Message::create([
                'sender_id'       => $user->id,
                'course_id'       => $request->course_id ?: null,
                'subject'         => $request->subject,
                'body'            => $request->body,
                'send_individual' => $request->boolean('send_individual'),
            ]);

            // Create one recipient row per person
            foreach ($request->recipient_ids as $recipientId) {
                MessageRecipient::create([
                    'message_id'   => $message->id,
                    'recipient_id' => $recipientId,
                    'folder'       => 'inbox',
                ]);
            }

            // Handle file attachments
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('message_attachments', 'public');
                    MessageAttachment::create([
                        'message_id' => $message->id,
                        'filename'   => $file->getClientOriginalName(),
                        'path'       => $path,
                        'mime_type'  => $file->getMimeType(),
                        'size'       => $file->getSize(),
                    ]);
                }
            }
        });

        return redirect()->route('inbox.index')->with('success', 'Message sent successfully.');
    }

    // ── Move to trash / restore ────────────────────────────────────────────────

    /**
     * DELETE /inbox/{message}
     * Move to trash (recipient) or fully delete (sender owns the record).
     */
    public function destroy(Message $message)
    {
        $user = Auth::user();

        $pivot = $message->recipients()->where('recipient_id', $user->id)->first();
        if ($pivot) {
            $pivot->update(['folder' => 'trash']);
        } elseif ($message->sender_id === $user->id) {
            // Sender deletes their own sent copy
            $message->delete();
        } else {
            abort(403);
        }

        return response()->json(['ok' => true]);
    }

    /**
     * PATCH /inbox/{message}/star
     * Toggle starred state for the authenticated recipient.
     */
    public function star(Message $message)
    {
        $user  = Auth::user();
        $pivot = $message->recipients()->where('recipient_id', $user->id)->first();
        if (!$pivot) { abort(403); }

        $pivot->update(['is_starred' => !$pivot->is_starred]);

        return response()->json(['starred' => (bool) $pivot->is_starred]);
    }

    /**
     * PATCH /inbox/{message}/archive
     * Move message to archived folder for the authenticated recipient.
     */
    public function archive(Message $message)
    {
        $user  = Auth::user();
        $pivot = $message->recipients()->where('recipient_id', $user->id)->first();
        if (!$pivot) { abort(403); }

        $pivot->update(['folder' => 'archived']);

        return response()->json(['ok' => true]);
    }

    /**
     * PATCH /inbox/{message}/restore
     * Move message from trash or archived back to inbox.
     */
    public function restore(Message $message)
    {
        $user = Auth::user();

        $message->recipients()
            ->where('recipient_id', $user->id)
            ->whereIn('folder', ['trash', 'archived'])
            ->update(['folder' => 'inbox']);

        return response()->json(['ok' => true]);
    }

    // ── AJAX: fetch users in a course by role ──────────────────────────────────

    /**
     * GET /inbox/course-users?course_id=X&role=student|instructor
     */
    public function courseUsers(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'role'      => 'required|in:student,instructor',
            'search'    => 'nullable|string|max:100',
        ]);

        $courseId = $request->course_id;
        $role     = $request->role;
        $search   = $request->search;

        if ($role === 'instructor') {
            // Instructors are in the course_instructor pivot
            $query = User::whereIn('id', function ($q) use ($courseId) {
                $q->select('user_id')
                  ->from('course_instructor')
                  ->where('course_id', $courseId);
            })->where('id', '!=', Auth::id());
        } else {
            // Students enrolled in this course
            $query = User::whereIn('id', function ($q) use ($courseId) {
                $q->select('user_id')
                  ->from('enrollments')
                  ->where('course_id', $courseId)
                  ->where('status', 'enrolled');
            })->where('id', '!=', Auth::id());
        }

        if ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $users = $query->select('id', 'name', 'email', 'profile_picture', 'role')
                       ->orderBy('name')
                       ->get();

        return response()->json($users);
    }

    // ── Download attachment ────────────────────────────────────────────────────

    /**
     * GET /inbox/attachments/{attachment}/download
     */
    public function downloadAttachment(MessageAttachment $attachment)
    {
        $user    = Auth::user();
        $message = $attachment->message;

        $isRecipient = $message->recipients()->where('recipient_id', $user->id)->exists();
        $isSender    = $message->sender_id === $user->id;

        if (!$isRecipient && !$isSender) {
            abort(403);
        }

        return Storage::disk('public')->download($attachment->path, $attachment->filename);
    }
}
