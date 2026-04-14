<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseArchive;
use App\Models\Enrollment;
use App\Models\Message;
use App\Models\MessageRecipient;
use App\Models\PendingEnrollment;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        $users = User::with(['courses', 'registration', 'enrollments'])->orderBy('name')->get();
        $courses = Course::orderBy('title')->get();
        $archives = CourseArchive::with('course:id,title,code')->orderByDesc('created_at')->get();
        $blockedRows = DB::table('instructor_blocked_course_archives')
            ->get(['user_id', 'course_archive_id'])
            ->groupBy('user_id')
            ->map(fn ($rows) => $rows->pluck('course_archive_id')->values());
        $pendingEnrollments = PendingEnrollment::with(['user.registration', 'items'])
            ->orderByDesc('submitted_at')
            ->get();

        return view('settings', [
            'users' => $users,
            'courses' => $courses,
            'archives' => $archives,
            'instructorBlockedArchives' => $blockedRows,
            'pendingEnrollments' => $pendingEnrollments,
        ]);
    }

    public function assignCourses(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
            'blocked_archives' => 'nullable|array',
            'blocked_archives.*' => 'exists:course_archives,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->role !== 'instructor') {
            return back()->with('error', 'Can only assign courses to instructors.');
        }

        $user->courses()->sync($request->courses);
        DB::table('instructor_blocked_course_archives')
            ->where('user_id', $user->id)
            ->delete();
        $blockedArchives = collect($request->input('blocked_archives', []))->unique()->values();
        foreach ($blockedArchives as $archiveId) {
            DB::table('instructor_blocked_course_archives')->insert([
                'user_id' => $user->id,
                'course_archive_id' => $archiveId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', "Updated course assignments for {$user->name}.");
    }

    public function assignStudentCourses(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ]);

        $user = User::with('enrollments')->findOrFail($request->user_id);
        if ($user->role === 'admin' || $user->role === 'instructor') {
            return back()->with('error', 'This action is only for students.');
        }

        $desiredCourseIds = collect($request->input('courses', []))->map(fn ($id) => (int) $id)->unique()->values();
        $existingCourseIds = $user->enrollments->pluck('course_id')->map(fn ($id) => (int) $id)->unique()->values();

        $toRemove = $existingCourseIds->diff($desiredCourseIds);
        $toAdd = $desiredCourseIds->diff($existingCourseIds);

        if ($toRemove->isNotEmpty()) {
            Enrollment::where('user_id', $user->id)
                ->whereIn('course_id', $toRemove->all())
                ->delete();
        }

        if ($toAdd->isNotEmpty()) {
            $courses = Course::whereIn('id', $toAdd->all())->get(['id', 'title']);
            foreach ($courses as $course) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'course_name' => $course->title,
                    'section_name' => null,
                    'status' => 'enrolled',
                    'enrolled_at' => now(),
                ]);
            }
        }

        return back()->with('success', "Updated enrollments for {$user->email}.");
    }

    public function createInstructor(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'instructor',
        ]);

        return back()->with('success', "Instructor account created for {$user->email}.");
    }

    public function approvePending(Request $request, PendingEnrollment $pending)
    {
        if ($pending->status !== 'pending') {
            return back()->with('error', 'This enrollment is already reviewed.');
        }

        $user = $pending->user;
        $schoolYear = now()->year;

        $items = $pending->items()->get();

        $semesterBasesToReplace = [];
        foreach ($items as $item) {
            $courseName = $item->course_name ?? '';
            $sectionName = $item->section_name ?? '';
            if ($courseName === '' || $sectionName === '') {
                continue;
            }
            $base = $this->semesterBaseFromSectionName($sectionName);
            if ($base === null) {
                continue;
            }
            if ($this->isPeEnrollment($courseName)) {
                $semesterBasesToReplace['pe'][$base] = true;
            }
            if ($this->isMlcEnrollment($courseName, $sectionName)) {
                $semesterBasesToReplace['mlc'][$base] = true;
            }
        }

        foreach (array_keys($semesterBasesToReplace['pe'] ?? []) as $base) {
            $user->enrollments()
                ->whereYear('enrolled_at', $schoolYear)
                ->where('status', 'enrolled')
                ->where(function ($q) use ($base) {
                    $q->where('course_name', 'like', 'PPE %')
                        ->where('section_name', 'like', $base . '%');
                })
                ->delete();
        }
        foreach (array_keys($semesterBasesToReplace['mlc'] ?? []) as $base) {
            $user->enrollments()
                ->whereYear('enrolled_at', $schoolYear)
                ->where('status', 'enrolled')
                ->where(function ($q) use ($base) {
                    $q->where('course_name', 'like', 'MLC%')
                        ->where('section_name', 'like', $base . '%');
                })
                ->delete();
        }

        foreach ($items as $item) {
            $courseName = $item->course_name ?? '';
            if ($courseName === '') {
                continue;
            }

            $course = Course::where('title', $courseName)->first();
            if (! $course) {
                $baseCode = strtoupper(Str::slug(substr($courseName, 0, 20), ''));
                $code = $baseCode;
                $n = 0;
                while (Course::where('code', $code)->exists()) {
                    $code = $baseCode . (string) (++$n);
                }
                $course = Course::create([
                    'title' => $courseName,
                    'code' => $code,
                    'description' => null,
                    'status' => 'published',
                ]);
            }

            Enrollment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ],
                [
                    'college_course_id' => $item->college_course_id ?? null,
                    'course_name' => $courseName,
                    'section_name' => $item->section_name,
                    'section_code' => $item->section_code ?? null,
                    'time_slot' => $item->time_slot ?? null,
                    'days' => $item->days ?? null,
                    'status' => 'enrolled',
                ]
            );
        }

        $pending->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $this->notifyEnrollmentApproved($user);

        return back()->with('success', 'Pending enrollment approved.');
    }

    public function rejectPending(Request $request, PendingEnrollment $pending)
    {
        if ($pending->status !== 'pending') {
            return back()->with('error', 'This enrollment is already reviewed.');
        }

        $pending->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Pending enrollment rejected.');
    }

    public function toggleInstructorArchiveAccess(Request $request, User $user)
    {
        if ($user->role !== 'instructor') {
            abort(404);
        }
        $user->can_access_course_archive = ! $user->can_access_course_archive;
        $user->save();

        return back()->with('success', 'Course archive access updated for instructor.');
    }

    protected function semesterBaseFromSectionName(string $sectionName): ?string
    {
        $patterns = [
            '/\s*-\s*PE-\d+$/i',
            '/\s*-\s*MLC-\d+$/i',
            '/\s*-\s*Literacy\s*\(STC\)$/i',
            '/\s*-\s*Civic Welfare\s*\(STL\)$/i',
            '/\s*-\s*Military Science\s*\(STM\)$/i',
        ];
        $trimmed = trim($sectionName);
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $trimmed)) {
                return trim(preg_replace($pattern, '', $trimmed));
            }
        }
        return null;
    }

    protected function isPeEnrollment(string $courseName): bool
    {
        return str_starts_with($courseName, 'PPE ');
    }

    protected function isMlcEnrollment(string $courseName, string $sectionName): bool
    {
        if (str_starts_with($courseName, 'MLC')) {
            return true;
        }
        $mlcTracks = ['Literacy (STC)', 'Civic Welfare (STL)', 'Military Science (STM)'];
        foreach ($mlcTracks as $track) {
            if (str_contains($sectionName, $track)) {
                return true;
            }
        }
        return false;
    }

    protected function notifyEnrollmentApproved(User $user): void
    {
        $registration = $user->registration;
        $last = Str::lower((string) ($registration->last_name ?? 'student'));
        $first = Str::lower((string) ($registration->first_name ?? 'user'));
        $last = preg_replace('/[^a-z0-9]/', '', $last) ?: 'student';
        $first = preg_replace('/[^a-z0-9]/', '', $first) ?: 'user';
        $institutionalEmail = $last . '.' . $first . '@academix.edu';

        $user->forceFill(['institutional_email' => $institutionalEmail])->save();

        $birthdatePassword = optional($registration?->birthdate)->format('mdY');
        if ($birthdatePassword) {
            $user->forceFill(['password' => Hash::make($birthdatePassword)])->save();
        }
        $passwordHint = $birthdatePassword ? "Initial password: {$birthdatePassword}" : 'Initial password: your recorded birthdate (MMDDYYYY).';
        $messageBody = "Enrollment approved. Institutional email: {$institutionalEmail}. {$passwordHint} You can keep using your existing login methods as well.";

        UserNotification::create([
            'user_id' => $user->id,
            'kind' => 'enrollment',
            'title' => 'Enrollment approved',
            'message' => $messageBody,
            'link_url' => '/inbox?folder=inbox',
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'subject' => 'Enrollment approval and institutional account details',
            'body' => $messageBody,
            'send_individual' => true,
        ]);

        MessageRecipient::create([
            'message_id' => $message->id,
            'recipient_id' => $user->id,
            'folder' => 'inbox',
        ]);
    }

    public function resetCourseContent(Request $request, Course $course)
    {
        DB::transaction(function () use ($course) {
            if ($course->banner_path) {
                \Storage::disk('public')->delete($course->banner_path);
            }
            $course->update([
                'banner_path' => null,
                'banner_object_position' => null,
                'description' => null,
            ]);
            $course->lessonModules()->update(['status' => 'draft', 'published_at' => null]);
            $course->courseAnnouncements()->update(['is_visible' => false]);
            $course->courseGrades()->update(['is_visible' => false]);
        });

        return back()->with('success', 'Course content reset by admin.');
    }
}
