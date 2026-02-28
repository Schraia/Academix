<?php

namespace App\Http\Controllers;

use App\Models\CollegeCourse;
use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\CourseAttendance;
use App\Models\CourseGrade;
use App\Models\CourseGradeWeight;
use App\Models\DiscussionMessage;
use App\Models\DiscussionThread;
use App\Models\Enrollment;
use App\Models\LessonModule;
use App\Models\LessonProgress;
use App\Models\UserCourseSectionView;
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

        $allCourses = collect();

        if ($user->role === 'instructor') {
            $allCourses = $user->courses()->orderBy('title')->get();
            $collegeCourses = collect();
        } elseif ($user->role === 'admin') {
            $allCourses = Course::orderBy('title')->get();
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
            $courses = collect();
        }

        return view('courses', [
            'enrollments' => $enrollments ?? collect(),
            'schoolYear' => $schoolYear,
            'collegeCourses' => $collegeCourses ?? collect(),
            'allCourses' => $allCourses,
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
        $course->load(['discussionThreads' => fn ($q) => $q->with(['user', 'messages.user'])->orderByRaw('COALESCE(last_activity_at, created_at) DESC')->limit(5)]);

        $view = UserCourseSectionView::firstOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            ['announcements_seen_at' => null, 'lessons_seen_at' => null, 'grades_seen_at' => null, 'discussions_seen_at' => null]
        );

        $ongoingThreads = $course->discussionThreads->take(2);
        $lastLesson = $course->lessonModules->sortByDesc('updated_at')->first();
        $recentLessons = $course->lessonModules->take(2);

        $announcementQuery = $course->courseAnnouncements();
        if (! $user->isInstructor() && ! $user->isAdmin()) {
            $announcementQuery->where('is_visible', true);
        }
        $announcementCount = $view->announcements_seen_at
            ? (clone $announcementQuery)->where('created_at', '>', $view->announcements_seen_at)->count()
            : (clone $announcementQuery)->count();

        $lessonModulesQuery = $course->lessonModules()->where('status', 'published');
        $lessonUploadCount = $view->lessons_seen_at
            ? (clone $lessonModulesQuery)->where('updated_at', '>', $view->lessons_seen_at)->count()
            : $course->lessonModules()->where('status', 'published')->count();

        $gradesQuery = $course->courseGrades()->where('user_id', $user->id)->whereNotNull('graded_at');
        if (! $user->isInstructor() && ! $user->isAdmin()) {
            $gradesQuery->where('is_visible', true);
        }
        $newGradedCount = $view->grades_seen_at
            ? (clone $gradesQuery)->where('graded_at', '>', $view->grades_seen_at)->count()
            : (clone $gradesQuery)->count();

        $discussionCount = $view->discussions_seen_at
            ? $course->discussionThreads()->where('created_at', '>', $view->discussions_seen_at)->count()
            : $course->discussionThreads()->count();

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
        UserCourseSectionView::updateOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            ['lessons_seen_at' => now()]
        );
        $isInstructor = $user->isInstructor() || $user->isAdmin();
        $course->load(['lessonModules' => fn ($q) => $q->orderBy('order')]);
        if (! $isInstructor) {
            $course->setRelation('lessonModules', $course->lessonModules->where('status', 'published'));
        }
        $completedIds = [];
        $totalPublished = $course->lessonModules->count();
        if (! $isInstructor && $totalPublished > 0) {
            $completedIds = LessonProgress::where('user_id', $user->id)
                ->whereIn('lesson_module_id', $course->lessonModules->pluck('id'))
                ->where('status', 'completed')
                ->pluck('lesson_module_id')
                ->all();
        }
        return view('course-lessons', [
            'course' => $course,
            'isInstructor' => $isInstructor,
            'completedLessonIds' => $completedIds,
            'totalLessons' => $totalPublished,
            'completedCount' => count($completedIds),
        ]);
    }

    public function grades(Request $request, Course $course)
    {
        $this->ensureCanAccessCourse($course);
        $user = Auth::user();
        UserCourseSectionView::updateOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            ['grades_seen_at' => now()]
        );
        $isInstructor = $user->isInstructor() || $user->isAdmin();
        $weights = $course->courseGradeWeights()->get()->keyBy('category');
        $sections = [];
        $sectionStudents = collect();
        $selectedSection = $request->query('section');
        if ($isInstructor) {
            $grades = $course->courseGrades()->with('user')->orderBy('graded_at', 'desc')->get();
            $enrollments = Enrollment::where('course_id', $course->id)->where('status', 'enrolled')->with('user:id,name,email')->get();
            $sections = $enrollments->pluck('section_code')->filter()->unique()->sort()->values()->all();
            if (empty($sections)) {
                $sections = $enrollments->pluck('section_name')->filter()->unique()->sort()->values()->all();
            }
            if ($selectedSection !== null && $selectedSection !== '') {
                $sectionStudents = $enrollments->filter(function ($e) use ($selectedSection) {
                    return ($e->section_code ?? '') === $selectedSection || ($e->section_name ?? '') === $selectedSection;
                })->values();
            }
        } else {
            $grades = $course->courseGrades()->where('user_id', $user->id)->where('is_visible', true)->orderBy('graded_at', 'desc')->get();
            $gradeSummary = $this->computeGradeSummary($grades, $weights);
        }
        return view('course-grades', [
            'course' => $course,
            'grades' => $grades,
            'isInstructor' => $isInstructor,
            'weights' => $weights,
            'gradeSummary' => $gradeSummary ?? null,
            'sections' => $sections,
            'selectedSection' => $selectedSection,
            'sectionStudents' => $sectionStudents,
        ]);
    }

    public function gradeSectionForm(Request $request, Course $course)
    {
        if (! Auth::user()->isInstructor() && ! Auth::user()->isAdmin()) {
            abort(403);
        }
        $section = $request->query('section');
        $enrollments = Enrollment::where('course_id', $course->id)->where('status', 'enrolled')->with('user:id,name,email')->get();
        if ($section !== null && $section !== '') {
            $enrollments = $enrollments->filter(function ($e) use ($section) {
                return ($e->section_code ?? '') === $section || ($e->section_name ?? '') === $section;
            })->values();
        }
        return view('course-grade-section', ['course' => $course, 'enrollments' => $enrollments, 'section' => $section]);
    }

    public function storeGradeSection(Request $request, Course $course)
    {
        if (! Auth::user()->isInstructor() && ! Auth::user()->isAdmin()) {
            abort(403);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:exam,quiz,activity',
            'max_score' => 'nullable|numeric',
            'section_code' => 'nullable|string|max:50',
            'scores' => 'required|array',
            'scores.*' => 'nullable|numeric',
        ]);
        $maxScore = $request->input('max_score', 100);
        $sectionCode = $request->input('section_code');
        foreach ($request->input('scores', []) as $userId => $score) {
            if ($score === null || $score === '') {
                continue;
            }
            $enrollment = Enrollment::where('course_id', $course->id)->where('user_id', $userId)->where('status', 'enrolled')->first();
            if (! $enrollment) {
                continue;
            }
            CourseGrade::create([
                'course_id' => $course->id,
                'user_id' => $userId,
                'section_code' => $sectionCode,
                'name' => $request->name,
                'category' => $request->category,
                'score' => (float) $score,
                'max_score' => (float) $maxScore,
                'graded_at' => now(),
            ]);
        }
        return redirect()->route('courses.grades', array_merge([$course], $sectionCode ? ['section' => $sectionCode] : []))->with('success', 'Grades recorded.');
    }

    public function rollCall(Request $request, Course $course)
    {
        if (! Auth::user()->isInstructor() && ! Auth::user()->isAdmin()) {
            abort(403);
        }
        $date = $request->query('date', now()->format('Y-m-d'));
        $section = $request->query('section');
        $enrollments = Enrollment::where('course_id', $course->id)->where('status', 'enrolled')->with('user:id,name,email')->get();
        $sections = $enrollments->pluck('section_code')->filter()->unique()->sort()->values()->all();
        if (empty($sections)) {
            $sections = $enrollments->pluck('section_name')->filter()->unique()->sort()->values()->all();
        }
        if ($section !== null && $section !== '') {
            $enrollments = $enrollments->filter(function ($e) use ($section) {
                return ($e->section_code ?? '') === $section || ($e->section_name ?? '') === $section;
            })->values();
        }
        $attendance = CourseAttendance::where('course_id', $course->id)->where('date', $date)->get()->keyBy('user_id');
        return view('course-rollcall', [
            'course' => $course,
            'date' => $date,
            'section' => $section,
            'sections' => $sections,
            'enrollments' => $enrollments,
            'attendance' => $attendance,
        ]);
    }

    public function storeRollCall(Request $request, Course $course)
    {
        if (! Auth::user()->isInstructor() && ! Auth::user()->isAdmin()) {
            abort(403);
        }
        $request->validate([
            'date' => 'required|date',
            'section_code' => 'nullable|string|max:50',
            'status' => 'required|array',
            'status.*' => 'in:present,late,absent,none',
        ]);
        $date = $request->input('date');
        $sectionCode = $request->input('section_code');
        $scores = ['present' => 100, 'late' => 75, 'absent' => 50, 'none' => null];
        foreach ($request->input('status', []) as $userId => $status) {
            CourseAttendance::updateOrCreate(
                ['course_id' => $course->id, 'user_id' => $userId, 'date' => $date],
                ['section_code' => $sectionCode, 'status' => $status]
            );
            $score = $scores[$status] ?? null;
            $name = 'Attendance ' . \Carbon\Carbon::parse($date)->format('M j, Y');
            if ($status === 'none') {
                CourseGrade::where('course_id', $course->id)->where('user_id', $userId)->where('name', $name)->where('category', 'attendance')->delete();
            } else {
                CourseGrade::updateOrCreate(
                    ['course_id' => $course->id, 'user_id' => $userId, 'name' => $name, 'category' => 'attendance'],
                    ['section_code' => $sectionCode, 'score' => $score, 'max_score' => 100, 'graded_at' => now()]
                );
            }
        }
        return redirect()->route('courses.rollcall', [$course, 'date' => $date, 'section' => $sectionCode])->with('success', 'Roll call saved.');
    }

    private function computeGradeSummary($grades, $weights)
    {
        $byCategory = $grades->groupBy('category');
        $summary = [];
        $weightedSum = 0;
        $weightTotal = 0;
        foreach (['exam', 'quiz', 'activity', 'attendance'] as $cat) {
            $items = $byCategory->get($cat, collect());
            $avg = null;
            if ($items->isNotEmpty()) {
                $totalPct = $items->sum(fn ($g) => $g->max_score > 0 ? ($g->score ?? 0) / $g->max_score * 100 : 0);
                $avg = round($totalPct / $items->count(), 2);
            }
            $summary[$cat] = $avg;
            $w = $weights->get($cat);
            if ($w && $avg !== null) {
                $weightedSum += $avg * (float) $w->percentage / 100;
                $weightTotal += (float) $w->percentage;
            }
        }
        $weightedGrade = $weightTotal > 0 ? round($weightedSum, 2) : null;
        return ['by_category' => $summary, 'weighted_grade' => $weightedGrade, 'weights_defined' => $weights->isNotEmpty()];
    }

    public function gradeWeights(Course $course)
    {
        if (! Auth::user()->isInstructor() && ! Auth::user()->isAdmin()) {
            abort(403);
        }
        $weights = $course->courseGradeWeights()->get()->keyBy('category');
        return view('course-grade-weights', ['course' => $course, 'weights' => $weights]);
    }

    public function updateGradeWeights(Request $request, Course $course)
    {
        if (! Auth::user()->isInstructor() && ! Auth::user()->isAdmin()) {
            abort(403);
        }
        $request->validate([
            'exam' => 'nullable|numeric|min:0|max:100',
            'quiz' => 'nullable|numeric|min:0|max:100',
            'activity' => 'nullable|numeric|min:0|max:100',
            'attendance' => 'nullable|numeric|min:0|max:100',
        ]);
        $total = (float) $request->input('exam', 0) + (float) $request->input('quiz', 0) + (float) $request->input('activity', 0) + (float) $request->input('attendance', 0);
        if (abs($total - 100) > 0.01) {
            return back()->withErrors(['percentages' => 'Percentages must add up to 100%.']);
        }
        foreach (['exam', 'quiz', 'activity', 'attendance'] as $cat) {
            $pct = (float) $request->input($cat, 0);
            $course->courseGradeWeights()->updateOrCreate(
                ['course_id' => $course->id, 'category' => $cat],
                ['percentage' => $pct]
            );
        }
        return redirect()->route('courses.grades', $course)->with('success', 'Grade weights updated.');
    }

    public function discussions(Request $request, Course $course)
    {
        $this->ensureCanAccessCourse($course);
        $user = Auth::user();
        UserCourseSectionView::updateOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            ['discussions_seen_at' => now()]
        );
        $threads = $course->discussionThreads()->with(['user', 'announcement.user'])->withCount('messages')->latest()->paginate(15);
        $replyTitle = null;
        $replyAnnouncementId = null;
        if ($request->query('reply_announcement')) {
            $ann = $course->courseAnnouncements()->find($request->query('reply_announcement'));
            $replyTitle = $ann ? $ann->title : $request->query('reply_title');
            $replyAnnouncementId = $ann ? $ann->id : null;
        } elseif ($request->query('reply_title')) {
            $replyTitle = $request->query('reply_title');
        }
        return view('course-discussions', ['course' => $course, 'threads' => $threads, 'replyTitle' => $replyTitle, 'replyAnnouncementId' => $replyAnnouncementId]);
    }

    public function storeDiscussion(Request $request, Course $course)
    {
        $this->ensureCanAccessCourse($course);
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'announcement_id' => 'nullable|integer|exists:course_announcements,id',
        ]);
        $data = [
            'title' => $request->title,
            'content' => $request->content,
            'user_id' => Auth::id(),
            'course_id' => $course->id,
        ];
        if ($request->filled('announcement_id')) {
            $ann = $course->courseAnnouncements()->find($request->announcement_id);
            if ($ann) {
                $data['announcement_id'] = $ann->id;
            }
        }
        $data['last_activity_at'] = now();
        DiscussionThread::create($data);
        return redirect()->route('courses.discussions', $course)->with('success', 'Discussion started.');
    }

    public function showThread(Course $course, DiscussionThread $thread)
    {
        $this->ensureCanAccessCourse($course);
        if ($thread->course_id !== $course->id) {
            abort(404);
        }
        $thread->load(['user', 'announcement.user', 'messages' => fn ($q) => $q->with('user')->orderBy('created_at')]);
        return view('course-discussion-thread', ['course' => $course, 'thread' => $thread]);
    }

    public function storeMessage(Request $request, Course $course, DiscussionThread $thread)
    {
        $this->ensureCanAccessCourse($course);
        if ($thread->course_id !== $course->id) {
            abort(404);
        }
        $request->validate(['content' => 'required|string']);
        DiscussionMessage::create([
            'content' => $request->content,
            'user_id' => Auth::id(),
            'thread_id' => $thread->id,
        ]);
        $thread->update(['last_activity_at' => now()]);
        return redirect()->route('courses.discussions.thread', [$course, $thread])->with('success', 'Reply posted.');
    }

    public function announcements(Course $course)
    {
        $this->ensureCanAccessCourse($course);
        $user = Auth::user();
        UserCourseSectionView::updateOrCreate(
            ['user_id' => $user->id, 'course_id' => $course->id],
            ['announcements_seen_at' => now()]
        );
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
        if ($lesson->course_id !== $course->id) {
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
        $downloadFilename = $lesson->attachment_original_name ?? ($lesson->title . ($ext ? '.' . $ext : ''));
        return view('lesson-preview', [
            'course' => $course,
            'lesson' => $lesson,
            'fileUrl' => $url,
            'canPreview' => $canPreview,
            'extension' => $ext,
            'downloadFilename' => $downloadFilename,
        ]);
    }

    public function editLesson(Course $course, LessonModule $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }
        return view('upload.lesson-edit', ['course' => $course, 'lesson' => $lesson]);
    }

    public function updateLesson(Request $request, Course $course, LessonModule $lesson)
    {
        if ($lesson->course_id !== $course->id) {
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
            $file = $request->file('attachment');
            $data['attachment_path'] = $file->store('lessons', 'public');
            $data['attachment_original_name'] = $file->getClientOriginalName();
        }
        $lesson->update($data);
        return redirect()->route('courses.lessons', $course)->with('success', 'Lesson updated.');
    }

    public function toggleLesson(Course $course, LessonModule $lesson)
    {
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }
        $newStatus = $lesson->status === 'published' ? 'draft' : 'published';
        $data = ['status' => $newStatus];
        if ($newStatus === 'published' && ! $lesson->published_at) {
            $data['published_at'] = now();
        }
        $lesson->update($data);
        return back()->with('success', $newStatus === 'published' ? 'Lesson is now visible.' : 'Lesson is now hidden.');
    }

    public function toggleLessonProgress(Request $request, Course $course, LessonModule $lesson)
    {
        $this->ensureCanAccessCourse($course);
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }
        if (! $lesson->attachment_path || $lesson->status !== 'published') {
            return back()->with('info', 'Lesson is not available.');
        }
        $user = Auth::user();
        if ($user->isInstructor() || $user->isAdmin()) {
            return back();
        }
        $progress = LessonProgress::firstOrCreate(
            ['user_id' => $user->id, 'lesson_module_id' => $lesson->id],
            ['status' => 'not_started', 'progress_percentage' => 0]
        );
        $wantCompleted = (bool) $request->input('completed', 0);
        $progress->update([
            'status' => $wantCompleted ? 'completed' : 'not_started',
            'progress_percentage' => $wantCompleted ? 100 : 0,
            'started_at' => $progress->started_at ?? now(),
            'completed_at' => $wantCompleted ? now() : null,
        ]);
        return back()->with('success', $wantCompleted ? 'Marked as done.' : 'Marked as not done.');
    }

    public function destroyLesson(Course $course, LessonModule $lesson)
    {
        if ($lesson->course_id !== $course->id) {
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
        if ($announcement->course_id !== $course->id) {
            abort(404);
        }
        return view('upload.announcement-edit', ['course' => $course, 'announcement' => $announcement]);
    }

    public function updateAnnouncement(Request $request, Course $course, CourseAnnouncement $announcement)
    {
        if ($announcement->course_id !== $course->id) {
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
        if ($announcement->course_id !== $course->id) {
            abort(404);
        }
        $announcement->update(['is_visible' => ! $announcement->is_visible]);
        return back()->with('success', $announcement->is_visible ? 'Announcement is now visible.' : 'Announcement is now hidden.');
    }

    public function destroyAnnouncement(Course $course, CourseAnnouncement $announcement)
    {
        if ($announcement->course_id !== $course->id) {
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
        if ($grade->course_id !== $course->id) {
            abort(404);
        }
        $grade->load('user');
        return view('upload.grade-edit', ['course' => $course, 'grade' => $grade]);
    }

    public function updateGrade(Request $request, Course $course, CourseGrade $grade)
    {
        if ($grade->course_id !== $course->id) {
            abort(404);
        }
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:exam,quiz,activity',
            'score' => 'nullable|numeric',
            'max_score' => 'nullable|numeric',
        ]);
        $grade->update([
            'name' => $request->name,
            'category' => $request->category,
            'score' => $request->score,
            'max_score' => $request->max_score ?? 100,
        ]);
        return redirect()->route('courses.grades', $course)->with('success', 'Grade updated.');
    }

    public function toggleGrade(Course $course, CourseGrade $grade)
    {
        if ($grade->course_id !== $course->id) {
            abort(404);
        }
        $grade->update(['is_visible' => ! $grade->is_visible]);
        return back()->with('success', $grade->is_visible ? 'Grade is now visible to student.' : 'Grade is now hidden.');
    }

    public function destroyGrade(Course $course, CourseGrade $grade)
    {
        if ($grade->course_id !== $course->id) {
            abort(404);
        }
        $grade->delete();
        return redirect()->route('courses.grades', $course)->with('success', 'Grade deleted.');
    }
}
