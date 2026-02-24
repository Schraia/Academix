<?php

namespace App\Http\Controllers;

use App\Models\CollegeCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $schoolYear = now()->year;

        $enrollments = $user->enrollments()
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->orderBy('enrolled_at', 'desc')
            ->get();

        $collegeCourseIds = $enrollments->pluck('college_course_id')->filter()->unique()->values();
        $collegeCourses = $collegeCourseIds->isNotEmpty()
            ? CollegeCourse::whereIn('id', $collegeCourseIds)->orderBy('name')->get()
            : collect();

        return view('courses', [
            'enrollments' => $enrollments,
            'schoolYear' => $schoolYear,
            'collegeCourses' => $collegeCourses,
        ]);
    }
}
