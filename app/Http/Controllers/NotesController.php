<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\File;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;

class NotesController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $courseId = $request->query('course_id');

        $notesQuery = Note::where('user_id', $user->id)
            ->with(['courses:id,title,code'])
            ->orderByDesc('updated_at');

        if ($courseId) {
            $notesQuery->whereHas('courses', fn ($q) => $q->where('courses.id', $courseId));
        }

        $notes = $notesQuery->get();

        $courseIds = $user->enrollments()
            ->where('status', 'enrolled')
            ->pluck('course_id');
        $courses = Course::whereIn('id', $courseIds)->orderBy('title')->get(['id', 'title', 'code']);

        return view('notes.index', [
            'notes' => $notes,
            'courses' => $courses,
            'selectedCourseId' => $courseId,
            'notesLimit' => 10,
            'notesCount' => (int) $user->notes()->count(),
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->notes()->count() >= 10) {
            return redirect()->route('notes.index')->withErrors(['limit' => 'You can only store up to 10 notes. Please delete one to create a new note.']);
        }

        $courseIds = $user->enrollments()
            ->where('status', 'enrolled')
            ->pluck('course_id');
        $courses = Course::whereIn('id', $courseIds)->orderBy('title')->get(['id', 'title', 'code']);

        return view('notes.create', [
            'courses' => $courses,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if ($user->notes()->count() >= 10) {
            return redirect()->route('notes.index')->withErrors(['limit' => 'You can only store up to 10 notes. Please delete one to create a new note.']);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'content_html' => 'nullable|string|max:200000',
            'course_ids' => 'array',
            'course_ids.*' => 'integer|exists:courses,id',
        ]);

        $note = Note::create([
            'user_id' => $user->id,
            'title' => $validated['title'],
            'content_html' => $validated['content_html'] ?? null,
        ]);

        $note->courses()->sync($validated['course_ids'] ?? []);

        return redirect()->route('notes.index')->with('success', 'Note created.');
    }

    public function edit(Note $note)
    {
        $user = Auth::user();
        abort_unless($note->user_id === $user->id, 403);

        $courseIds = $user->enrollments()
            ->where('status', 'enrolled')
            ->pluck('course_id');
        $courses = Course::whereIn('id', $courseIds)->orderBy('title')->get(['id', 'title', 'code']);

        return view('notes.edit', [
            'note' => $note->load('courses:id'),
            'courses' => $courses,
        ]);
    }

    public function update(Request $request, Note $note)
    {
        $user = Auth::user();
        abort_unless($note->user_id === $user->id, 403);

        $validated = $request->validate([
            'title' => 'required|string|max:180',
            'content_html' => 'nullable|string|max:200000',
            'course_ids' => 'array',
            'course_ids.*' => 'integer|exists:courses,id',
        ]);

        $note->update([
            'title' => $validated['title'],
            'content_html' => $validated['content_html'] ?? null,
        ]);
        $note->courses()->sync($validated['course_ids'] ?? []);

        return redirect()->route('notes.index')->with('success', 'Note updated.');
    }

    public function destroy(Note $note)
    {
        $user = Auth::user();
        abort_unless($note->user_id === $user->id, 403);
        $note->delete();

        return redirect()->route('notes.index')->with('success', 'Note deleted.');
    }

    public function uploadAttachment(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'file' => ['required', File::image()->max(6 * 1024)],
        ]);

        $path = $request->file('file')->store('note-attachments/' . $user->id, 'public');
        $url = asset('storage/' . $path);

        return response()->json([
            'url' => $url,
        ]);
    }

    public function download(Note $note, string $format)
    {
        $user = Auth::user();
        abort_unless($note->user_id === $user->id, 403);

        $title = trim($note->title ?: 'note');
        $safeBase = preg_replace('/[^A-Za-z0-9 _.-]+/', '', $title) ?: 'note';

        if ($format === 'txt') {
            $text = $this->htmlToPlainText($note->content_html ?? '');
            return response($text, 200, [
                'Content-Type' => 'text/plain; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="' . $safeBase . '.txt"',
            ]);
        }

        if ($format === 'docx') {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();
            $section->addTitle($title, 1);
            $section->addTextBreak(1);
            $section->addText($this->htmlToPlainText($note->content_html ?? ''));

            $writer = IOFactory::createWriter($phpWord, 'Word2007');
            $tmpPath = storage_path('app/tmp/' . uniqid('note_', true) . '.docx');
            if (! is_dir(dirname($tmpPath))) {
                mkdir(dirname($tmpPath), 0777, true);
            }
            $writer->save($tmpPath);

            return response()->download($tmpPath, $safeBase . '.docx')->deleteFileAfterSend(true);
        }

        if ($format === 'pdf') {
            $html = $note->content_html ?? '';
            $pdf = app('dompdf.wrapper');
            $pdf->loadHTML(view('notes.pdf', [
                'title' => $title,
                'contentHtml' => $html,
            ])->render());
            return $pdf->download($safeBase . '.pdf');
        }

        abort(404);
    }

    private function htmlToPlainText(string $html): string
    {
        $html = str_replace(["\r\n", "\r"], "\n", $html);
        $html = preg_replace("/<br\\s*\\/?>/i", "\n", $html) ?? $html;
        $html = preg_replace("/<\\/p>/i", "\n\n", $html) ?? $html;
        $text = strip_tags($html);
        $text = html_entity_decode($text, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $text = preg_replace("/\\n{3,}/", "\n\n", $text) ?? $text;
        return trim($text) . "\n";
    }
}

