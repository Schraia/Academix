<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Course;
use App\Models\CourseAnnouncement;
use App\Models\CourseGrade;
use App\Models\Enrollment;
use App\Models\LessonModule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Throwable;

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
            ->with('user:id,name')
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

    public function certificatesForm(Request $request, Course $course)
    {
        $enrolled = Enrollment::where('course_id', $course->getKey())
            ->where('status', 'enrolled')
            ->with('user:id,name,email')
            ->get();

        $issuedCertificates = Certificate::where('course_id', $course->getKey())
            ->with('user:id,name,email')
            ->orderByDesc('issued_date')
            ->get();

        $templateOptions = [
            1 => ['name' => 'Classic', 'description' => 'Formal layout with traditional styling.'],
            2 => ['name' => 'Modern', 'description' => 'Clean and minimal with bold accents.'],
            3 => ['name' => 'Academic', 'description' => 'Institution-style with structured hierarchy.'],
            4 => ['name' => 'Elegant', 'description' => 'Decorative look with soft gradients.'],
        ];

        return view('upload.certificate', [
            'course' => $course,
            'enrolledUsers' => $enrolled,
            'issuedCertificates' => $issuedCertificates,
            'prefillUserId' => $request->query('user_id'),
            'templateOptions' => $templateOptions,
            'instructor' => $request->user(),
        ]);
    }

    public function certificatePreview(Request $request, Course $course)
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'template_id' => 'nullable|integer|in:1,2,3,4',
            'signer_name' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date',
        ]);

        $awardeeName = 'Student Name';
        if (! empty($data['user_id'])) {
            $enrollment = Enrollment::where('course_id', $course->getKey())
                ->where('user_id', $data['user_id'])
                ->where('status', 'enrolled')
                ->with('user:id,name')
                ->first();

            if ($enrollment && $enrollment->user) {
                $awardeeName = $enrollment->user->name;
            }
        }

        $templateId = (int) ($data['template_id'] ?? 1);
        $templateView = $this->resolveCertificateTemplateView($templateId);
        $signatureUrl = $this->buildSignatureDataUri($request->user()->signature_path);

        $renderData = [
            'templateView' => $templateView,
            'previewMode' => true,
            'studentName' => $awardeeName,
            'courseName' => $course->title,
            'signerName' => $data['signer_name'] ?? $request->user()->name,
            'subtitle' => $data['subtitle'] ?? 'In recognition of successfully completing the course.',
            'issuedDate' => ! empty($data['issued_date']) ? $data['issued_date'] : now()->toDateString(),
            'expiryDate' => $data['expiry_date'] ?? null,
            'certificateNumber' => 'CERT-PREVIEW',
            'signatureUrl' => $signatureUrl,
        ];

        return view('certificates.render', $renderData);
    }

    public function storeCertificate(Request $request, Course $course)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'template_id' => 'required|integer|in:1,2,3,4',
            'signer_name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'issued_date' => 'required|date',
            'expiry_date' => 'nullable|date|after_or_equal:issued_date',
            'digital_signature' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
            'use_saved_signature' => 'nullable|boolean',
        ]);

        $enrollment = Enrollment::where('course_id', $course->getKey())
            ->where('user_id', $request->user_id)
            ->where('status', 'enrolled')
            ->first();

        if (! $enrollment) {
            return back()->withErrors(['user_id' => 'Selected user is not enrolled in this course.']);
        }

        $instructor = $request->user();
        $signaturePath = $instructor->signature_path;

        if ($request->hasFile('digital_signature')) {
            $signaturePath = $request->file('digital_signature')->store('signatures', 'public');
            $instructor->forceFill(['signature_path' => $signaturePath])->save();
        } elseif (! $request->boolean('use_saved_signature') && empty($signaturePath)) {
            return back()->withErrors([
                'digital_signature' => 'Upload a digital signature or use a previously saved signature.',
            ])->withInput();
        }

        do {
            $certificateNumber = 'CERT-' . strtoupper(Str::random(10));
        } while (Certificate::where('certificate_number', $certificateNumber)->exists());

        $certificate = Certificate::create([
            'user_id' => $request->user_id,
            'course_id' => $course->getKey(),
            'certificate_number' => $certificateNumber,
            'template_id' => (int) $request->template_id,
            'signer_name' => $request->signer_name,
            'subtitle' => $request->subtitle,
            'issued_date' => $request->issued_date,
            'expiry_date' => $request->expiry_date,
            'certificate_url' => null,
        ]);

        try {
            $imagePath = $this->generateCertificatePng($certificate, $course, $enrollment->user->name, $signaturePath);
            $certificate->forceFill(['certificate_url' => $imagePath])->save();
        } catch (Throwable $e) {
            $certificate->delete();

            return back()->withErrors([
                'template_id' => 'Unable to render certificate image right now. Please try again.',
            ])->withInput();
        }

        return redirect()->route('courses.upload.certificates', $course)->with('certificate_sent', [
            'student' => $enrollment->user->name,
            'email' => $enrollment->user->email,
            'course' => $course->title,
        ]);
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

    private function generateCertificatePng(Certificate $certificate, Course $course, string $studentName, ?string $signaturePath): string
    {
        $templateView = $this->resolveCertificateTemplateView((int) $certificate->template_id);
        $html = view('certificates.render', [
            'templateView' => $templateView,
            'previewMode' => false,
            'studentName' => $studentName,
            'courseName' => $course->title,
            'signerName' => $certificate->signer_name ?: 'Instructor',
            'subtitle' => $certificate->subtitle ?: 'In recognition of successfully completing the course.',
            'issuedDate' => optional($certificate->issued_date)->toDateString() ?: now()->toDateString(),
            'expiryDate' => optional($certificate->expiry_date)->toDateString(),
            'certificateNumber' => $certificate->certificate_number,
            'signatureUrl' => $this->buildSignatureDataUri($signaturePath),
        ])->render();

        $relativePath = 'certificates/generated/' . $certificate->certificate_number . '.png';
        $tempPath = storage_path('app/temp/cert-' . $certificate->certificate_number . '-' . Str::random(6) . '.png');
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
