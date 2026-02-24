<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get(['id', 'name', 'email', 'role']);

        return view('settings', [
            'users' => $users,
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
}
