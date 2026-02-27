<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CourseUploadController;

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
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/lessons', [CourseController::class, 'lessons'])->name('courses.lessons');
    Route::get('/courses/{course}/grades', [CourseController::class, 'grades'])->name('courses.grades');
    Route::get('/courses/{course}/discussions', [CourseController::class, 'discussions'])->name('courses.discussions');
    Route::post('/courses/{course}/discussions', [CourseController::class, 'storeDiscussion'])->name('courses.discussions.store');
    Route::get('/courses/{course}/discussions/{thread}', [CourseController::class, 'showThread'])->name('courses.discussions.thread');
    Route::post('/courses/{course}/discussions/{thread}/messages', [CourseController::class, 'storeMessage'])->name('courses.discussions.messages.store');
    Route::get('/courses/{course}/announcements', [CourseController::class, 'announcements'])->name('courses.announcements');
    Route::get('/courses/{course}/lessons/{lesson}/preview', [CourseController::class, 'lessonPreview'])->name('courses.lessons.preview');
    Route::post('/courses/{course}/lessons/{lesson}/progress', [CourseController::class, 'toggleLessonProgress'])->name('courses.lessons.progress.toggle');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware('admin')->group(function () {
        Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('/settings/update-role', [SettingsController::class, 'updateRole'])->name('settings.updateRole');
        Route::post('/settings/assign-courses', [SettingsController::class, 'assignCourses'])->name('settings.assignCourses');
    });

    Route::middleware('instructor')->group(function () {
        Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
        Route::post('/courses/{course}/edit', [CourseController::class, 'update'])->name('courses.update');
        Route::get('/courses/{course}/upload/lessons', [CourseUploadController::class, 'lessonsForm'])->name('courses.upload.lessons');
        Route::post('/courses/{course}/upload/lessons', [CourseUploadController::class, 'storeLesson'])->name('courses.upload.lessons.store');
        Route::get('/courses/{course}/upload/announcements', [CourseUploadController::class, 'announcementsForm'])->name('courses.upload.announcements');
        Route::post('/courses/{course}/upload/announcements', [CourseUploadController::class, 'storeAnnouncement'])->name('courses.upload.announcements.store');
        Route::get('/courses/{course}/upload/grades', [CourseUploadController::class, 'gradesForm'])->name('courses.upload.grades');
        Route::post('/courses/{course}/upload/grades', [CourseUploadController::class, 'storeGrade'])->name('courses.upload.grades.store');
        Route::get('/courses/{course}/lessons/{lesson}/edit', [CourseController::class, 'editLesson'])->name('courses.lessons.edit');
        Route::post('/courses/{course}/lessons/{lesson}', [CourseController::class, 'updateLesson'])->name('courses.lessons.update');
        Route::post('/courses/{course}/lessons/{lesson}/toggle', [CourseController::class, 'toggleLesson'])->name('courses.lessons.toggle');
        Route::delete('/courses/{course}/lessons/{lesson}', [CourseController::class, 'destroyLesson'])->name('courses.lessons.destroy');
        Route::get('/courses/{course}/announcements/{announcement}/edit', [CourseController::class, 'editAnnouncement'])->name('courses.announcements.edit');
        Route::post('/courses/{course}/announcements/{announcement}', [CourseController::class, 'updateAnnouncement'])->name('courses.announcements.update');
        Route::post('/courses/{course}/announcements/{announcement}/toggle', [CourseController::class, 'toggleAnnouncement'])->name('courses.announcements.toggle');
        Route::delete('/courses/{course}/announcements/{announcement}', [CourseController::class, 'destroyAnnouncement'])->name('courses.announcements.destroy');
        Route::get('/courses/{course}/grade-weights', [CourseController::class, 'gradeWeights'])->name('courses.grade-weights');
        Route::post('/courses/{course}/grade-weights', [CourseController::class, 'updateGradeWeights'])->name('courses.grade-weights.update');
        Route::get('/courses/{course}/grade-section', [CourseController::class, 'gradeSectionForm'])->name('courses.grade-section');
        Route::post('/courses/{course}/grade-section', [CourseController::class, 'storeGradeSection'])->name('courses.grade-section.store');
        Route::get('/courses/{course}/rollcall', [CourseController::class, 'rollCall'])->name('courses.rollcall');
        Route::post('/courses/{course}/rollcall', [CourseController::class, 'storeRollCall'])->name('courses.rollcall.store');
        Route::get('/courses/{course}/grades/{grade}/edit', [CourseController::class, 'editGrade'])->name('courses.grades.edit');
        Route::post('/courses/{course}/grades/{grade}', [CourseController::class, 'updateGrade'])->name('courses.grades.update');
        Route::post('/courses/{course}/grades/{grade}/toggle', [CourseController::class, 'toggleGrade'])->name('courses.grades.toggle');
        Route::delete('/courses/{course}/grades/{grade}', [CourseController::class, 'destroyGrade'])->name('courses.grades.destroy');
    });
});
