<?php

namespace App\Http\Controllers;

use App\Models\CollegeCourse;
use App\Models\CollegeSection;
use App\Models\Course;
use App\Models\Curriculum;
use App\Models\Enrollment;
use App\Models\SectionSubjectSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnrollController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $schoolYear = now()->year;
        $alreadyEnrolled = $user->enrollments()
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->get(['course_name', 'section_name', 'section_code', 'time_slot', 'days'])
            ->map(fn ($e) => [
                'course_name' => $e->course_name,
                'section_name' => $e->section_name,
                'section_code' => $e->section_code,
                'time_slot' => $e->time_slot,
                'days' => $e->days,
            ])
            ->values()
            ->toArray();

        $userCollegeCourseIds = $user->enrollments()
            ->whereYear('enrolled_at', $schoolYear)
            ->where('status', 'enrolled')
            ->whereNotNull('college_course_id')
            ->distinct()
            ->pluck('college_course_id');

        $collegeCourses = $userCollegeCourseIds->isNotEmpty()
            ? CollegeCourse::whereIn('id', $userCollegeCourseIds)->orderBy('name')->get()
            : CollegeCourse::orderBy('name')->get();

        $restrictToCollege = $userCollegeCourseIds->isNotEmpty();

        $curriculumByCollege = $this->buildCurriculumByCollege();
        $sectionsByCollege = $this->buildSectionsByCollege();
        $sectionSubjectTimes = $this->buildSectionSubjectTimes();
        $peMlcSchedules = $this->buildPeMlcSchedules();

        return view('enroll', [
            'alreadyEnrolled' => $alreadyEnrolled,
            'collegeCourses' => $collegeCourses,
            'restrictToCollege' => $restrictToCollege,
            'curriculumByCollege' => $curriculumByCollege,
            'sectionsByCollege' => $sectionsByCollege,
            'sectionSubjectTimes' => $sectionSubjectTimes,
            'peMlcSchedules' => $peMlcSchedules,
        ]);
    }

    /**
     * PPE and MLC schedules for conflict checking and display.
     * Each entry has time_slot, days, section_code; frontend uses these when adding to cart.
     */
    protected function buildPeMlcSchedules(): array
    {
        $pe = [
            ['courseCode' => 'PPE 1101', 'courseName' => 'Badminton', 'section_code' => 'PE-1', 'time_slot' => '8:00 AM - 9:00 AM', 'days' => 'MW'],
            ['courseCode' => 'PPE 1102', 'courseName' => 'Volleyball', 'section_code' => 'PE-2', 'time_slot' => '9:00 AM - 10:00 AM', 'days' => 'TTH'],
            ['courseCode' => 'PPE 1103', 'courseName' => 'Basketball', 'section_code' => 'PE-3', 'time_slot' => '10:00 AM - 11:00 AM', 'days' => 'MW'],
            ['courseCode' => 'PPE 1104', 'courseName' => 'Table Tennis', 'section_code' => 'PE-4', 'time_slot' => '11:00 AM - 12:00 PM', 'days' => 'TTH'],
            ['courseCode' => 'PPE 1105', 'courseName' => 'Swimming', 'section_code' => 'PE-5', 'time_slot' => '1:00 PM - 3:00 PM', 'days' => 'F'],
            ['courseCode' => 'PPE 1106', 'courseName' => 'Dance', 'section_code' => 'PE-6', 'time_slot' => '2:00 PM - 3:00 PM', 'days' => 'MW'],
        ];
        $mlc = [
            ['option' => 'Literacy (STC)', 'section_code' => 'MLC-1', 'time_slot' => '2:00 PM - 3:30 PM', 'days' => 'TTH'],
            ['option' => 'Civic Welfare (STL)', 'section_code' => 'MLC-2', 'time_slot' => '10:00 AM - 11:30 AM', 'days' => 'MW'],
            ['option' => 'Military Science (STM)', 'section_code' => 'MLC-3', 'time_slot' => '1:00 PM - 4:00 PM', 'days' => 'F'],
        ];
        return ['pe' => $pe, 'mlc' => $mlc];
    }

    /**
     * Build time_slot per (college_course_id, year, semester, section_code, course_code).
     * So frontend can show different time for each section for each subject.
     */
    protected function buildSectionSubjectTimes(): array
    {
        $rows = SectionSubjectSchedule::with(['collegeSection', 'course'])
            ->get();

        $out = [];
        foreach ($rows as $row) {
            if (!$row->collegeSection || !$row->course) {
                continue;
            }
            $ccId = (string) $row->collegeSection->college_course_id;
            $y = (string) $row->collegeSection->year;
            $s = (string) $row->collegeSection->semester;
            $sectionCode = $row->collegeSection->section_code;
            $courseCode = $row->course->code;
            if (!isset($out[$ccId][$y][$s][$sectionCode])) {
                $out[$ccId][$y][$s][$sectionCode] = [];
            }
            $out[$ccId][$y][$s][$sectionCode][$courseCode] = [
                'time_slot' => $row->time_slot,
                'days' => $row->days ?? '',
            ];
        }
        return $out;
    }

    /**
     * Build college sections grouped by college_course_id -> year -> semester.
     * Each section has section_code (e.g. 11CSA) and time_slot.
     */
    protected function buildSectionsByCollege(): array
    {
        $sections = CollegeSection::orderBy('college_course_id')
            ->orderBy('year')
            ->orderBy('semester')
            ->orderBy('sort_order')
            ->get();

        $out = [];
        foreach ($sections as $row) {
            $ccId = (string) $row->college_course_id;
            $y = (string) $row->year;
            $s = (string) $row->semester;
            if (!isset($out[$ccId][$y][$s])) {
                $out[$ccId][$y][$s] = [];
            }
            $out[$ccId][$y][$s][] = [
                'section_code' => $row->section_code,
                'time_slot' => $row->time_slot,
            ];
        }
        return $out;
    }

    /**
     * Build curriculum grouped by college_course_id -> year -> semester -> [subjects].
     * Used so the enroll page shows real curriculum subjects per year/semester.
     */
    protected function buildCurriculumByCollege(): array
    {
        $curriculum = Curriculum::with('course')
            ->orderBy('year')
            ->orderBy('semester')
            ->orderBy('sort_order')
            ->get();

        // 1.5-hour slots so conflicts are detectable when ranges overlap (no repeat within semester)
        $timeSlots = [
            '8:00 AM - 9:30 AM', '9:30 AM - 11:00 AM', '11:00 AM - 12:30 PM',
            '1:00 PM - 2:30 PM', '2:30 PM - 4:00 PM', '4:00 PM - 5:30 PM',
            '7:00 AM - 8:30 AM', '8:30 AM - 10:00 AM', '10:00 AM - 11:30 AM',
            '12:00 PM - 1:30 PM', '1:30 PM - 3:00 PM', '3:00 PM - 4:30 PM',
        ];
        $out = [];
        $slotIndex = [];
        foreach ($curriculum as $row) {
            if (!$row->course) {
                continue;
            }
            $ccId = (string) $row->college_course_id;
            $y = (string) $row->year;
            $s = (string) $row->semester;
            $key = $ccId . '_' . $y . '_' . $s;
            if (!isset($slotIndex[$key])) {
                $slotIndex[$key] = 0;
            }
            $timeSlot = $timeSlots[$slotIndex[$key] % count($timeSlots)];
            $slotIndex[$key]++;
            if (!isset($out[$ccId][$y][$s])) {
                $out[$ccId][$y][$s] = [];
            }
            $out[$ccId][$y][$s][] = [
                'code' => $row->course->code,
                'title' => $row->course->title,
                'credits' => (int) $row->course->credits,
                'timeSlot' => $timeSlot,
            ];
        }
        return $out;
    }

    public function save(Request $request)
    {
        $request->validate([
            'items' => 'required|string',
        ]);

        $items = json_decode($request->input('items'), true);
        if (!is_array($items) || empty($items)) {
            return redirect()->route('enroll')->with('error', 'Please select at least one section to enroll.');
        }

        $user = Auth::user();
        $placeholders = [];
        foreach ($items as $item) {
            $courseName = $item['courseName'] ?? '';
            $sectionName = $item['sectionName'] ?? '';
            if ($courseName === '' && $sectionName === '') {
                continue;
            }
            $row = ['course_name' => $courseName, 'section_name' => $sectionName];
            if (!empty($item['section_code'])) {
                $row['section_code'] = $item['section_code'];
            }
            if (!empty($item['timeSlot'])) {
                $row['time_slot'] = $item['timeSlot'];
            }
            if (!empty($item['days'])) {
                $row['days'] = $item['days'];
            }
            if (!empty($item['collegeCourseId'])) {
                $row['college_course_id'] = (int) $item['collegeCourseId'];
            }
            $placeholders[] = $row;
        }

        if (empty($placeholders)) {
            return redirect()->route('enroll')->with('error', 'Please select at least one section to enroll.');
        }

        $request->session()->put('pending_enrollments', $placeholders);
        return redirect()->route('enroll.summary');
    }

    public function summary(Request $request)
    {
        $items = $request->session()->get('pending_enrollments', []);
        if (empty($items)) {
            return redirect()->route('enroll')->with('info', 'No pending enrollments.');
        }

        $totalAmount = count($items) * 5000; // placeholder amount per subject
        $paymentType = 'To be selected'; // placeholder

        return view('enroll-summary', [
            'items' => $items,
            'totalAmount' => $totalAmount,
            'paymentType' => $paymentType,
        ]);
    }

    public function complete(Request $request)
    {
        $items = $request->session()->get('pending_enrollments', []);
        if (empty($items)) {
            return redirect()->route('enroll')->with('info', 'No pending enrollments.');
        }

        $user = Auth::user();
        $course = Course::first();
        if (!$course) {
            $course = Course::create([
                'title' => 'Placeholder',
                'code' => 'PLACEHOLDER',
                'status' => 'published',
            ]);
        }

        foreach ($items as $item) {
            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'college_course_id' => $item['college_course_id'] ?? null,
                'course_name' => $item['course_name'],
                'section_name' => $item['section_name'],
                'section_code' => $item['section_code'] ?? null,
                'time_slot' => $item['time_slot'] ?? null,
                'days' => $item['days'] ?? null,
                'status' => 'enrolled',
            ]);
        }

        $request->session()->forget('pending_enrollments');
        return redirect()->route('courses.index')->with('success', 'You have been successfully enrolled.');
    }
}

