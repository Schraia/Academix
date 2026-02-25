<?php

namespace App\Http\Controllers;

use App\Models\CollegeCourse;
use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\CourseGrade;
use App\Models\Enrollment;
use App\Models\LessonModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $schoolYear = now()->year;
        $enrollments = collect();
        $collegeCourses = collect();

        if ($user->role === 'instructor') {
            $collegeCourses = $user->collegeCourses()->orderBy('name')->get();
        } else {
            $enrollments = $user->enrollments()
                ->whereYear('enrolled_at', $schoolYear)
                ->where('status', 'enrolled')
                ->orderBy('enrolled_at', 'desc')
                ->get();

            $collegeCourseIds = $enrollments->pluck('college_course_id')->filter()->unique()->values();
            $collegeCourses = $collegeCourseIds->isNotEmpty()
                ? CollegeCourse::whereIn('id', $collegeCourseIds)->orderBy('name')->get()
                : collect();
        }

        return view('courses', [
            'enrollments' => $enrollments,
            'schoolYear' => $schoolYear,
            'collegeCourses' => $collegeCourses,
        ]);
    }

    public function show(Course $course)
    {
        $user = Auth::user();
        $schoolYear = now()->year;

        $enrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->first();

        if (! $enrollment && ! $user->isInstructor() && ! $user->isAdmin()) {
            abort(403, 'You are not enrolled in this course.');
        }
        if (! $enrollment) {
            $enrollment = (object) ['section_name' => $user->isAdmin() ? 'Admin' : 'Instructor'];
        }

        $course->load(['lessonModules' => fn ($q) => $q->where('status', 'published')]);
        $course->load(['discussionThreads' => fn ($q) => $q->with(['user', 'messages.user'])->latest()->limit(5)]);

        $ongoingThreads = $course->discussionThreads->take(2);
        $lastLesson = $course->lessonModules->sortByDesc('updated_at')->first();
        $recentLessons = $course->lessonModules->take(4);
        $discussionCount = $course->discussionThreads()->count();
        $newGradedCount = $course->courseGrades()->where('user_id', $user->id)->whereNotNull('graded_at')->count();
        $lessonUploadCount = $course->lessonModules->count();
        $announcementCount = $course->courseAnnouncements()->count();

        return view('course-show', [
            'course' => $course,
            'enrollment' => $enrollment,
            'ongoingThreads' => $ongoingThreads,
            'lastLesson' => $lastLesson,
            'recentLessons' => $recentLessons,
            'discussionCount' => $discussionCount,
            'newGradedCount' => $newGradedCount,
            'lessonUploadCount' => $lessonUploadCount,
            'announcementCount' => $announcementCount,
        ]);
    }

    private function ensureEnrolled(Course $course): Enrollment
    {
        $user = Auth::user();
        $schoolYear = now()->year;
        $enrollment = $user->enrollments()
            ->where('course_id', $course->id)
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->first();
        if (! $enrollment) {
            abort(403, 'You are not enrolled in this course.');
        }
        return $enrollment;
    }

    private function ensureCanAccessCourse(Course $course): void
    {
        $user = Auth::user();
        if ($user->isInstructor() || $user->isAdmin()) {
            return;
        }
        $schoolYear = now()->year;
        $enrolled = $user->enrollments()
            ->where('course_id', $course->id)
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->exists();
        if (! $enrolled) {
            abort(403, 'You do not have access to this course.');
        }
    }

    public function lessons(Course $course)
    {
        $this->ensureCanAccessCourse($course);
        $user = Auth::user();
        $isInstructor = $user->isInstructor() || $user->isAdmin();
        $course->load(['lessonModules' => fn ($q) => $q->orderBy('order')]);
        if (! $isInstructor) {
            $course->setRelation('lessonModules', $course->lessonModules->where('status', 'published'));
        }
        return view('course-lessons', ['course' => $course, 'isInstructor' => $isInstructor]);
    }

    public function grades(Course $course)
    {
        $this->ensureCanAccessCourse($course);
        $user = Auth::user();
        $isInstructor = $user->isInstructor() || $user->isAdmin();
        if ($isInstructor) {
            $grades = $course->courseGrades()->with('user')->orderBy('graded_at', 'desc')->get();
        } else {
            $grades = $course->courseGrades()->where('user_id', $user->id)->where('is_visible', true)->orderBy('graded_at', 'desc')->get();
        }
        return view('course-grades', ['course' => $course, 'grades' => $grades, 'isInstructor' => $isInstructor]);
    }

    public function discussions(Course $course)
    {
        $this->ensureCanAccessCourse($course);
        $threads = $course->discussionThreads()->with('user')->latest()->paginate(15);
        return view('course-discussions', ['course' => $course, 'threads' => $threads]);
    }

    public function announcements(Course $course)
    {
        $this->ensureCanAccessCourse($course);
        $user = Auth::user();
        $isInstructor = $user->isInstructor() || $user->isAdmin();
        $query = $course->courseAnnouncements()->with('user')->latest();
        if (! $isInstructor) {
            $query->where('is_visible', true);
        }
        $announcements = $query->get();
        return view('course-announcements', ['course' => $course, 'announcements' => $announcements, 'isInstructor' => $isInstructor]);
    }

    public function edit(Course $course)
    {
        return view('course-edit', ['course' => $course]);
    }

    public function update(Request $request, Course $course)
    {
        $request->validate([
            'description' => 'nullable|string',
            'banner' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ]);

        $data = ['description' => $request->description];

        if ($request->hasFile('banner')) {
            if ($course->banner_path) {
                Storage::disk('public')->delete($course->banner_path);
            }
            $data['banner_path'] = $request->file('banner')->store('courses', 'public');
        }

        $course->update($data);

        return redirect()->route('courses.show', $course)->with('success', 'Course updated.');
    }

    public function lessonPreview(Course $course, LessonModule $lesson)
    {
        $this->ensureCanAccessCourse($course);
        if ($lesson->course_id !== $course->getKey()) {
            abort(404);
        }
        $user = Auth::user();
        if (! $user->isInstructor() && ! $user->isAdmin() && $lesson->status !== 'published') {
            abort(404);
        }
        if (! $lesson->attachment_path) {
            return redirect()->route('courses.lessons', $course)->with('info', 'No file to preview.');
        }
        $path = $lesson->attachment_path;
        $ext = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $url = asset('storage/' . $path);
        $canPreview = in_array($ext, ['pdf', 'png', 'jpg', 'jpeg', 'gif', 'webp']);
        return view('lesson-preview', [
            'course' => $course,
            'lesson' => $lesson,
            'fileUrl' => $url,
            'canPreview' => $canPreview,
            'extension' => $ext,
        ]);
    }

    public function editLesson(Course $course, LessonModule $lesson)
    {
        if ($lesson->course_id !== $course->getKey()) {
            abort(404);
        }
        return view('upload.lesson-edit', ['course' => $course, 'lesson' => $lesson]);
    }

    public function updateLesson(Request $request, Course $course, LessonModule $lesson)
    {
        if ($lesson->course_id !== $course->getKey()) {
            abort(404);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,pptx,docx,png|max:51200',
        ]);
        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
        ];
        if ($request->hasFile('attachment')) {
            if ($lesson->attachment_path) {
                Storage::disk('public')->delete($lesson->attachment_path);
            }
            $data['attachment_path'] = $request->file('attachment')->store('lessons', 'public');
        }
        $lesson->update($data);
        return redirect()->route('courses.lessons', $course)->with('success', 'Lesson updated.');
    }

    public function toggleLesson(Course $course, LessonModule $lesson)
    {
        if ($lesson->course_id !== $course->getKey()) {
            abort(404);
        }
        $lesson->update(['status' => $lesson->status === 'published' ? 'draft' : 'published']);
        return back()->with('success', $lesson->status === 'published' ? 'Lesson is now visible.' : 'Lesson is now hidden.');
    }

    public function destroyLesson(Course $course, LessonModule $lesson)
    {
        if ($lesson->course_id !== $course->getKey()) {
            abort(404);
        }
        if ($lesson->attachment_path) {
            Storage::disk('public')->delete($lesson->attachment_path);
        }
        $lesson->delete();
        return redirect()->route('courses.lessons', $course)->with('success', 'Lesson deleted.');
    }

    public function editAnnouncement(Course $course, CourseAnnouncement $announcement)
    {
        if ($announcement->course_id !== $course->getKey()) {
            abort(404);
        }
        return view('upload.announcement-edit', ['course' => $course, 'announcement' => $announcement]);
    }

    public function updateAnnouncement(Request $request, Course $course, CourseAnnouncement $announcement)
    {
        if ($announcement->course_id !== $course->getKey()) {
            abort(404);
        }
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ]);
        $data = ['title' => $request->title, 'content' => $request->content];
        if ($request->hasFile('image')) {
            if ($announcement->image_path) {
                Storage::disk('public')->delete($announcement->image_path);
            }
            $data['image_path'] = $request->file('image')->store('announcements', 'public');
        }
        $announcement->update($data);
        return redirect()->route('courses.announcements', $course)->with('success', 'Announcement updated.');
    }

    public function toggleAnnouncement(Course $course, CourseAnnouncement $announcement)
    {
        if ($announcement->course_id !== $course->getKey()) {
            abort(404);
        }
        $announcement->update(['is_visible' => ! $announcement->is_visible]);
        return back()->with('success', $announcement->is_visible ? 'Announcement is now visible.' : 'Announcement is now hidden.');
    }

    public function destroyAnnouncement(Course $course, CourseAnnouncement $announcement)
    {
        if ($announcement->course_id !== $course->getKey()) {
            abort(404);
        }
        if ($announcement->image_path) {
            Storage::disk('public')->delete($announcement->image_path);
        }
        $announcement->delete();
        return redirect()->route('courses.announcements', $course)->with('success', 'Announcement deleted.');
    }

    public function editGrade(Course $course, CourseGrade $grade)
    {
        if ($grade->course_id !== $course->getKey()) {
            abort(404);
        }
        $grade->load('user');
        return view('upload.grade-edit', ['course' => $course, 'grade' => $grade]);
    }

    public function updateGrade(Request $request, Course $course, CourseGrade $grade)
    {
        if ($grade->course_id !== $course->getKey()) {
            abort(404);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'score' => 'nullable|numeric',
            'max_score' => 'nullable|numeric',
        ]);
        $grade->update([
            'name' => $request->name,
            'score' => $request->score,
            'max_score' => $request->max_score ?? 100,
        ]);
        return redirect()->route('courses.grades', $course)->with('success', 'Grade updated.');
    }

    public function toggleGrade(Course $course, CourseGrade $grade)
    {
        if ($grade->course_id !== $course->getKey()) {
            abort(404);
        }
        $grade->update(['is_visible' => ! $grade->is_visible]);
        return back()->with('success', $grade->is_visible ? 'Grade is now visible to student.' : 'Grade is now hidden.');
    }

    public function destroyGrade(Course $course, CourseGrade $grade)
    {
        if ($grade->course_id !== $course->getKey()) {
            abort(404);
        }
        $grade->delete();
        return redirect()->route('courses.grades', $course)->with('success', 'Grade deleted.');
    }
}
