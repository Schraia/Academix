<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Throwable;

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
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        if ($user && $user->isInstructor()) {
            $instructorCourses = $user->courses()->orderBy('title')->get();
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

        if (! empty($certificate->template_id)) {
            $needsImageGeneration = empty($certificate->certificate_url)
                || str_starts_with((string) $certificate->certificate_url, 'signatures/');

            if ($needsImageGeneration) {
                try {
                    $generatedPath = $this->generateCertificatePng($certificate, $certificate->certificate_url);
                    $certificate->forceFill(['certificate_url' => $generatedPath])->save();
                } catch (Throwable $e) {
                    abort(500, 'Unable to generate certificate image right now.');
                }
            }
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

    private function resolveCertificateTemplateView(int $templateId): string
    {
        $views = [
            1 => 'certificates.templates.classic',
            2 => 'certificates.templates.modern',
            3 => 'certificates.templates.academic',
            4 => 'certificates.templates.elegant',
        ];

        return $views[$templateId] ?? $views[1];
    }

    private function buildSignatureDataUri(?string $path): ?string
    {
        if (empty($path) || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        $mime = match ($extension) {
            'jpg', 'jpeg' => 'image/jpeg',
            'gif' => 'image/gif',
            default => 'image/png',
        };

        $content = Storage::disk('public')->get($path);
        return 'data:' . $mime . ';base64,' . base64_encode($content);
    }

    private function generateCertificatePng(Certificate $certificate, ?string $signaturePath): string
    {
        $templateView = $this->resolveCertificateTemplateView((int) $certificate->template_id);
        $html = view('certificates.render', [
            'templateView' => $templateView,
            'previewMode' => false,
            'studentName' => $certificate->user->name,
            'courseName' => $certificate->course->title,
            'signerName' => $certificate->signer_name ?: 'Instructor',
            'subtitle' => $certificate->subtitle ?: 'In recognition of successfully completing the course.',
            'issuedDate' => optional($certificate->issued_date)->toDateString() ?: now()->toDateString(),
            'expiryDate' => optional($certificate->expiry_date)->toDateString(),
            'certificateNumber' => $certificate->certificate_number,
            'signatureUrl' => $this->buildSignatureDataUri($signaturePath),
        ])->render();

        $relativePath = 'certificates/generated/' . $certificate->certificate_number . '.png';
        $tempPath = storage_path('app/temp/cert-download-' . $certificate->certificate_number . '-' . Str::random(6) . '.png');
        $tempDir = dirname($tempPath);

        if (! is_dir($tempDir)) {
            mkdir($tempDir, 0777, true);
        }

        Browsershot::html($html)
            ->setNodeBinary('c:/laragon/bin/nodejs/node-v22/node.exe')
            ->setNpmBinary('c:/laragon/bin/nodejs/node-v22/npm.cmd')
            ->setNodeModulePath(base_path('node_modules'))
            ->windowSize(1123, 794)
            ->setScreenshotType('png')
            ->timeout(120)
            ->save($tempPath);

        $content = file_get_contents($tempPath);
        if ($content === false) {
            throw new \RuntimeException('Failed to read generated certificate image.');
        }

        Storage::disk('public')->put($relativePath, $content);
        @unlink($tempPath);

        return $relativePath;
    }
}
