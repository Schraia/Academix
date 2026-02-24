<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Check if user has enrollments for the current school year
        if (!$user->hasCurrentYearEnrollments()) {
            return redirect()->route('enroll');
        }
        
        return view('dashboard');
    }
}

