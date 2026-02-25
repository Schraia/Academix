<?php

namespace App\Http\Controllers;

use App\Models\CollegeCourse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $users = User::with('collegeCourses')->orderBy('name')->get();
        $courses = CollegeCourse::orderBy('name')->get();

        return view('settings', [
            'users' => $users,
            'courses' => $courses,
        ]);
    }

    public function updateRole(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:student,instructor,admin',
        ]);

        $target = User::findOrFail($request->user_id);
        $target->update(['role' => $request->role]);

        return back()->with('success', "Updated {$target->name} to " . ucfirst($request->role) . '.');
    }

    public function assignCourses(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'nullable|exists:college_courses,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->role !== 'instructor') {
            return back()->with('error', 'Can only assign courses to instructors.');
        }

        $user->collegeCourses()->sync($request->course_id);

        return back()->with('success', "Updated course assignments for {$user->name}.");
    }
}
