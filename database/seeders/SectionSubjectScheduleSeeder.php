<?php

namespace Database\Seeders;

use App\Models\CollegeSection;
use App\Models\Curriculum;
use App\Models\SectionSubjectSchedule;
use Illuminate\Database\Seeder;

class SectionSubjectScheduleSeeder extends Seeder
{
    /** 1.5-hour slots; non-overlapping. 16 slots so each section gets 8 distinct times (× 4 days = 32 combos). */
    protected function timeSlots(): array
    {
        return [
            '7:00 AM - 8:30 AM', '8:30 AM - 10:00 AM', '10:00 AM - 11:30 AM', '11:30 AM - 1:00 PM',
            '1:00 PM - 2:30 PM', '2:30 PM - 4:00 PM', '4:00 PM - 5:30 PM', '5:30 PM - 7:00 PM',
            '7:30 AM - 9:00 AM', '9:00 AM - 10:30 AM', '10:30 AM - 12:00 PM', '12:00 PM - 1:30 PM',
            '1:30 PM - 3:00 PM', '3:00 PM - 4:30 PM', '4:30 PM - 6:00 PM', '6:00 PM - 7:30 PM',
        ];
    }

    /** Class days: MW (Mon-Wed), TTH (Tue-Thu), F (Fri), Sat */
    protected function daysOptions(): array
    {
        return ['MW', 'TTH', 'F', 'Sat'];
    }

    public function run(): void
    {
        $slots = $this->timeSlots();
        $daysOptions = $this->daysOptions();
        $numSlots = count($slots);
        $numDays = count($daysOptions);
        $sections = CollegeSection::orderBy('college_course_id')->orderBy('year')->orderBy('semester')->orderBy('sort_order')->get();

        foreach ($sections as $section) {
            $curriculum = Curriculum::where('college_course_id', $section->college_course_id)
                ->where('year', $section->year)
                ->where('semester', $section->semester)
                ->orderBy('sort_order')
                ->get();

            $sectionOffset = (int) $section->sort_order - 1;

            foreach ($curriculum as $index => $row) {
                $daysIndex = $index % $numDays;
                $timeIndexInSection = (int) floor($index / $numDays);
                $timeIndex = ($timeIndexInSection + $sectionOffset * 4) % $numSlots;
                $days = $daysOptions[$daysIndex];
                $timeSlot = $slots[$timeIndex];

                SectionSubjectSchedule::updateOrCreate(
                    [
                        'college_section_id' => $section->id,
                        'course_id' => $row->course_id,
                    ],
                    [
                        'time_slot' => $timeSlot,
                        'days' => $days,
                    ]
                );
            }
        }
    }
}
