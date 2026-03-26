<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\PendingEnrollment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        $users = User::with(['courses', 'registration', 'enrollments'])->orderBy('name')->get();
        $courses = Course::orderBy('title')->get();
        $pendingEnrollments = PendingEnrollment::with(['user.registration', 'items'])
            ->orderByDesc('submitted_at')
            ->get();

        return view('settings', [
            'users' => $users,
            'courses' => $courses,
            'pendingEnrollments' => $pendingEnrollments,
        ]);
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

    public function assignStudentCourses(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'courses' => 'nullable|array',
            'courses.*' => 'exists:courses,id',
        ]);

        $user = User::with('enrollments')->findOrFail($request->user_id);
        if ($user->role === 'admin' || $user->role === 'instructor') {
            return back()->with('error', 'This action is only for students.');
        }

        $desiredCourseIds = collect($request->input('courses', []))->map(fn ($id) => (int) $id)->unique()->values();
        $existingCourseIds = $user->enrollments->pluck('course_id')->map(fn ($id) => (int) $id)->unique()->values();

        $toRemove = $existingCourseIds->diff($desiredCourseIds);
        $toAdd = $desiredCourseIds->diff($existingCourseIds);

        if ($toRemove->isNotEmpty()) {
            Enrollment::where('user_id', $user->id)
                ->whereIn('course_id', $toRemove->all())
                ->delete();
        }

        if ($toAdd->isNotEmpty()) {
            $courses = Course::whereIn('id', $toAdd->all())->get(['id', 'title']);
            foreach ($courses as $course) {
                Enrollment::create([
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                    'course_name' => $course->title,
                    'section_name' => null,
                    'status' => 'enrolled',
                    'enrolled_at' => now(),
                ]);
            }
        }

        return back()->with('success', "Updated enrollments for {$user->email}.");
    }

    public function createInstructor(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => 'instructor',
        ]);

        return back()->with('success', "Instructor account created for {$user->email}.");
    }

    public function approvePending(Request $request, PendingEnrollment $pending)
    {
        if ($pending->status !== 'pending') {
            return back()->with('error', 'This enrollment is already reviewed.');
        }

        $user = $pending->user;
        $schoolYear = now()->year;

        $items = $pending->items()->get();

        $semesterBasesToReplace = [];
        foreach ($items as $item) {
            $courseName = $item->course_name ?? '';
            $sectionName = $item->section_name ?? '';
            if ($courseName === '' || $sectionName === '') {
                continue;
            }
            $base = $this->semesterBaseFromSectionName($sectionName);
            if ($base === null) {
                continue;
            }
            if ($this->isPeEnrollment($courseName)) {
                $semesterBasesToReplace['pe'][$base] = true;
            }
            if ($this->isMlcEnrollment($courseName, $sectionName)) {
                $semesterBasesToReplace['mlc'][$base] = true;
            }
        }

        foreach (array_keys($semesterBasesToReplace['pe'] ?? []) as $base) {
            $user->enrollments()
                ->whereYear('enrolled_at', $schoolYear)
                ->where('status', 'enrolled')
                ->where(function ($q) use ($base) {
                    $q->where('course_name', 'like', 'PPE %')
                        ->where('section_name', 'like', $base . '%');
                })
                ->delete();
        }
        foreach (array_keys($semesterBasesToReplace['mlc'] ?? []) as $base) {
            $user->enrollments()
                ->whereYear('enrolled_at', $schoolYear)
                ->where('status', 'enrolled')
                ->where(function ($q) use ($base) {
                    $q->where('course_name', 'like', 'MLC%')
                        ->where('section_name', 'like', $base . '%');
                })
                ->delete();
        }

        foreach ($items as $item) {
            $courseName = $item->course_name ?? '';
            if ($courseName === '') {
                continue;
            }

            $course = Course::where('title', $courseName)->first();
            if (! $course) {
                $baseCode = strtoupper(Str::slug(substr($courseName, 0, 20), ''));
                $code = $baseCode;
                $n = 0;
                while (Course::where('code', $code)->exists()) {
                    $code = $baseCode . (string) (++$n);
                }
                $course = Course::create([
                    'title' => $courseName,
                    'code' => $code,
                    'description' => null,
                    'status' => 'published',
                ]);
            }

            Enrollment::updateOrCreate(
                [
                    'user_id' => $user->id,
                    'course_id' => $course->id,
                ],
                [
                    'college_course_id' => $item->college_course_id ?? null,
                    'course_name' => $courseName,
                    'section_name' => $item->section_name,
                    'section_code' => $item->section_code ?? null,
                    'time_slot' => $item->time_slot ?? null,
                    'days' => $item->days ?? null,
                    'status' => 'enrolled',
                ]
            );
        }

        $pending->update([
            'status' => 'approved',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Pending enrollment approved.');
    }

    public function rejectPending(Request $request, PendingEnrollment $pending)
    {
        if ($pending->status !== 'pending') {
            return back()->with('error', 'This enrollment is already reviewed.');
        }

        $pending->update([
            'status' => 'rejected',
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Pending enrollment rejected.');
    }

    protected function semesterBaseFromSectionName(string $sectionName): ?string
    {
        $patterns = [
            '/\s*-\s*PE-\d+$/i',
            '/\s*-\s*MLC-\d+$/i',
            '/\s*-\s*Literacy\s*\(STC\)$/i',
            '/\s*-\s*Civic Welfare\s*\(STL\)$/i',
            '/\s*-\s*Military Science\s*\(STM\)$/i',
        ];
        $trimmed = trim($sectionName);
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $trimmed)) {
                return trim(preg_replace($pattern, '', $trimmed));
            }
        }
        return null;
    }

    protected function isPeEnrollment(string $courseName): bool
    {
        return str_starts_with($courseName, 'PPE ');
    }

    protected function isMlcEnrollment(string $courseName, string $sectionName): bool
    {
        if (str_starts_with($courseName, 'MLC')) {
            return true;
        }
        $mlcTracks = ['Literacy (STC)', 'Civic Welfare (STL)', 'Military Science (STM)'];
        foreach ($mlcTracks as $track) {
            if (str_contains($sectionName, $track)) {
                return true;
            }
        }
        return false;
    }
}
