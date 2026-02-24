<?php

namespace Database\Seeders;

use App\Models\CollegeCourse;
use App\Models\CollegeSection;
use Illuminate\Database\Seeder;

class CollegeSectionSeeder extends Seeder
{
    /** Section letter: 1=A, 2=B, ... 9=I, 10+= hex (A, B, C...) */
    protected function sectionLetter(int $index): string
    {
        return $index <= 9
            ? chr(64 + $index)
            : strtoupper(dechex($index));
    }

    public function run(): void
    {
        $timeSlots = [
            '8:00 AM - 9:30 AM', '9:30 AM - 11:00 AM', '11:00 AM - 12:30 PM',
            '1:00 PM - 2:30 PM', '2:30 PM - 4:00 PM', '4:00 PM - 5:30 PM',
            '7:00 AM - 8:30 AM', '8:30 AM - 10:00 AM', '10:00 AM - 11:30 AM',
            '12:00 PM - 1:30 PM', '1:30 PM - 3:00 PM', '3:00 PM - 4:30 PM',
        ];
        $sectionsPerSemester = 5;

        foreach (CollegeCourse::all() as $collegeCourse) {
            $code = $collegeCourse->code;
            for ($year = 1; $year <= 4; $year++) {
                for ($semester = 1; $semester <= 2; $semester++) {
                    for ($i = 1; $i <= $sectionsPerSemester; $i++) {
                        $letter = $this->sectionLetter($i);
                        $sectionCode = $year . $semester . $code . $letter;
                        $timeSlot = $timeSlots[($i - 1) % count($timeSlots)];
                        CollegeSection::firstOrCreate(
                            [
                                'college_course_id' => $collegeCourse->id,
                                'year' => $year,
                                'semester' => $semester,
                                'section_code' => $sectionCode,
                            ],
                            [
                                'time_slot' => $timeSlot,
                                'sort_order' => $i,
                            ]
                        );
                    }
                }
            }
        }
    }
}
