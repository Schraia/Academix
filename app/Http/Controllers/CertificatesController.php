<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CertificatesController extends Controller
{
    /**
     * List courses that have at least one certificate issued to the current user.
     */
    public function index()
    {
        $courses = Course::whereHas('certificates', function ($q) {
            $q->where('user_id', Auth::id());
        })
            ->withCount(['certificates' => function ($q) {
                $q->where('user_id', Auth::id());
            }])
            ->orderBy('title')
            ->get();

        $instructorCourses = collect();
        if (Auth::user()->isInstructor()) {
            $instructorCourses = Auth::user()->courses()->orderBy('title')->get();
        }

        return view('certificates.index', [
            'courses' => $courses,
            'instructorCourses' => $instructorCourses,
        ]);
    }

    /**
     * Show certificates for a specific course (only those issued to the current user).
     */
    public function show(Course $course)
    {
        $certificates = $course->certificates()
            ->where('user_id', Auth::id())
            ->orderByDesc('issued_date')
            ->get();

        if ($certificates->isEmpty()) {
            abort(404);
        }

        return view('certificates.show', [
            'course' => $course,
            'certificates' => $certificates,
        ]);
    }

    /**
     * Download a certificate file (only if it belongs to the current user).
     */
    public function download(Certificate $certificate)
    {
        if ($certificate->user_id !== Auth::id()) {
            abort(403);
        }

        if (empty($certificate->certificate_url)) {
            abort(404, 'Certificate file not available.');
        }

        $path = $certificate->certificate_url;
        if (! Storage::disk('public')->exists($path)) {
            abort(404, 'Certificate file not found.');
        }

        $filename = 'certificate-' . $certificate->certificate_number . '.' . pathinfo($path, PATHINFO_EXTENSION);
        $fullPath = Storage::disk('public')->path($path);

        return response()->download($fullPath, $filename);
    }
}
