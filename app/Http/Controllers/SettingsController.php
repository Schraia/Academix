<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $users = User::with('courses')->orderBy('name')->get();
        $courses = Course::orderBy('title')->get();

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
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ]);

        $user = User::findOrFail($request->user_id);

        if ($user->role !== 'instructor') {
            return back()->with('error', 'Can only assign courses to instructors.');
        }

        $user->courses()->sync($request->courses);

        return back()->with('success', "Updated course assignments for {$user->name}.");
    }
}
