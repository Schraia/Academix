<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\CourseController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    
    // Google OAuth routes
    Route::get('/auth/google', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/enroll', [EnrollController::class, 'index'])->name('enroll');
    Route::post('/enroll/save', [EnrollController::class, 'save'])->name('enroll.save');
    Route::get('/enroll/summary', [EnrollController::class, 'summary'])->name('enroll.summary');
    Route::post('/enroll/complete', [EnrollController::class, 'complete'])->name('enroll.complete');
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
