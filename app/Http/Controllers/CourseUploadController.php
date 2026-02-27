<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\CourseGrade;
use App\Models\Enrollment;
use App\Models\LessonModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseUploadController extends Controller
{
    public function lessonsForm(Course $course)
    {
        return view('upload.lesson', ['course' => $course]);
    }

    public function storeLesson(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,pptx,docx,png|max:51200',
        ]);

        $attachmentPath = null;
        $attachmentOriginalName = null;
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $attachmentPath = $file->store('lessons', 'public');
            $attachmentOriginalName = $file->getClientOriginalName();
        }

        $order = $course->lessonModules()->max('order') + 1;
        LessonModule::create([
            'course_id' => $course->getKey(),
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'attachment_path' => $attachmentPath,
            'attachment_original_name' => $attachmentOriginalName,
            'order' => $order,
            'type' => 'lesson',
            'status' => 'published',
            'published_at' => now(),
        ]);

        if ($request->input('return_to') === 'lessons') {
            return redirect()->route('courses.lessons', $course)->with('success', 'Lesson added.');
        }
        return redirect()->route('courses.show', $course)->with('success', 'Lesson added.');
    }

    public function announcementsForm(Course $course)
    {
        return view('upload.announcement', ['course' => $course]);
    }

    public function storeAnnouncement(Request $request, Course $course)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('announcements', 'public');
        }

        CourseAnnouncement::create([
            'course_id' => $course->getKey(),
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'image_path' => $imagePath,
        ]);

        if ($request->input('return_to') === 'announcements') {
            return redirect()->route('courses.announcements', $course)->with('success', 'Announcement added.');
        }
        return redirect()->route('courses.show', $course)->with('success', 'Announcement added.');
    }

    public function gradesForm(Request $request, Course $course)
    {
        $enrolled = Enrollment::where('course_id', $course->getKey())
            ->where('status', 'enrolled')
            ->with('user:id,name,email')
            ->get();

        $sections = $enrolled->pluck('section_code')->filter()->unique()->sort()->values();
        if ($sections->isEmpty()) {
            $sections = $enrolled->pluck('section_name')->filter()->unique()->sort()->values();
        }

        return view('upload.grade', [
            'course' => $course,
            'enrolledUsers' => $enrolled,
            'sections' => $sections->values()->all(),
            'prefillUserId' => $request->query('user_id'),
            'prefillSection' => $request->query('section'),
        ]);
    }

    public function storeGrade(Request $request, Course $course)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'section_code' => 'nullable|string|max:50',
            'name' => 'required|string|max:255',
            'category' => 'required|in:exam,quiz,activity',
            'score' => 'nullable|numeric',
            'max_score' => 'nullable|numeric',
        ]);

        $enrollment = Enrollment::where('course_id', $course->getKey())
            ->where('user_id', $request->user_id)
            ->where('status', 'enrolled')
            ->first();

        if (! $enrollment) {
            return back()->withErrors(['user_id' => 'Selected user is not enrolled in this course.']);
        }

        CourseGrade::create([
            'course_id' => $course->getKey(),
            'user_id' => $request->user_id,
            'section_code' => $request->section_code ?: null,
            'name' => $request->name,
            'category' => $request->category,
            'score' => $request->score,
            'max_score' => $request->max_score ?? 100,
            'graded_at' => now(),
        ]);

        if ($request->input('return_to') === 'grades') {
            return redirect()->route('courses.grades', $course)->with('success', 'Grade recorded.');
        }
        return redirect()->route('courses.show', $course)->with('success', 'Grade recorded.');
    }
}
