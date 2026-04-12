<?php

namespace App\Http\Controllers;

use App\Models\ArchivedLesson;
use App\Models\Course;
use App\Models\CourseArchive;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseArchivingController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $coursesQuery = Course::query()->orderBy('title');
        if ($q !== '') {
            $coursesQuery->where(function ($query) use ($q) {
                $query->where('title', 'like', '%' . $q . '%')
                    ->orWhere('code', 'like', '%' . $q . '%');
            });
        }
        $courses = $coursesQuery->limit(80)->get();
        $archives = CourseArchive::with(['course:id,title,code', 'creator:id,name'])
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.course-archiving', [
            'courses' => $courses,
            'archives' => $archives,
            'searchQ' => $q,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'label' => 'required|string|max:255',
        ]);
        $course = Course::findOrFail($data['course_id']);

        $archive = CourseArchive::create([
            'course_id' => $course->id,
            'label' => $data['label'],
            'created_by' => Auth::id(),
        ]);

        $lessons = $course->lessonModules()->where('status', 'published')->orderBy('order')->get();
        $sort = 0;
        foreach ($lessons as $lesson) {
            $newPath = null;
            if ($lesson->attachment_path && Storage::disk('public')->exists($lesson->attachment_path)) {
                $ext = pathinfo($lesson->attachment_path, PATHINFO_EXTENSION);
                $newPath = 'archived-lessons/' . $archive->id . '/' . Str::uuid() . ($ext !== '' ? '.' . $ext : '');
                Storage::disk('public')->copy($lesson->attachment_path, $newPath);
            }
            ArchivedLesson::create([
                'course_archive_id' => $archive->id,
                'title' => $lesson->title,
                'description' => $lesson->description,
                'content' => $lesson->content,
                'attachment_path' => $newPath,
                'attachment_original_name' => $lesson->attachment_original_name,
                'video_url' => $lesson->video_url,
                'sort_order' => $sort++,
                'source_lesson_id' => $lesson->id,
            ]);
        }

        return redirect()->route('settings.courseArchiving')->with('success', 'Course lessons archived as: ' . $archive->label);
    }

    public function destroy(CourseArchive $courseArchive)
    {
        $courseArchive->load('archivedLessons');
        foreach ($courseArchive->archivedLessons as $al) {
            if ($al->attachment_path && Storage::disk('public')->exists($al->attachment_path)) {
                Storage::disk('public')->delete($al->attachment_path);
            }
        }
        DB::table('instructor_blocked_course_archives')->where('course_archive_id', $courseArchive->id)->delete();
        $courseArchive->delete();

        return redirect()->route('settings.courseArchiving')->with('success', 'Archive timeframe removed.');
    }

    public function instructorArchiveAccessForm(User $user)
    {
        if ($user->role !== 'instructor') {
            abort(404);
        }
        $courseIds = $user->courses()->pluck('courses.id');
        $archives = CourseArchive::whereIn('course_id', $courseIds)->with('course:id,title,code')->orderByDesc('created_at')->get();
        $blocked = DB::table('instructor_blocked_course_archives')->where('user_id', $user->id)->pluck('course_archive_id')->all();

        return view('admin.instructor-archive-access', [
            'instructor' => $user,
            'archives' => $archives,
            'blockedIds' => $blocked,
        ]);
    }

    public function instructorArchiveAccessSave(Request $request, User $user)
    {
        if ($user->role !== 'instructor') {
            abort(404);
        }
        $request->validate([
            'blocked_archive_ids' => 'nullable|array',
            'blocked_archive_ids.*' => 'exists:course_archives,id',
        ]);
        $allowedCourseIds = $user->courses()->pluck('courses.id')->all();
        $blocked = collect($request->input('blocked_archive_ids', []))
            ->map(fn ($id) => (int) $id)
            ->filter(function (int $id) use ($allowedCourseIds) {
                $arch = CourseArchive::find($id);

                return $arch && in_array((int) $arch->course_id, $allowedCourseIds, true);
            })
            ->unique()
            ->values();

        DB::table('instructor_blocked_course_archives')->where('user_id', $user->id)->delete();
        foreach ($blocked as $archiveId) {
            DB::table('instructor_blocked_course_archives')->insert([
                'user_id' => $user->id,
                'course_archive_id' => $archiveId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return redirect()->route('settings.index')->with('success', 'Archive access updated for ' . ($user->name ?: $user->email) . '.');
    }
}
