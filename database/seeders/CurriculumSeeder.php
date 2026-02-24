<?php

namespace Database\Seeders;

use App\Models\CollegeCourse;
use App\Models\Course;
use App\Models\Curriculum;
use Illuminate\Database\Seeder;

class CurriculumSeeder extends Seeder
{
    /**
     * CS curriculum (applied to all college courses for development).
     * Each row: code, title, credits, year, semester, prerequisites (nullable).
     */
    protected function curriculumData(): array
    {
        return [
            // FIRST YEAR / FIRST SEMESTER
            ['CCS 1101', 'Fundamentals of Programming', 3, 1, 1, null],
            ['CIC 1101', 'Introduction to Computing', 3, 1, 1, null],
            ['MLC 1101', 'Literacy/Civic Welfare/Military Science 1', 3, 1, 1, 'E-STC 1101, E-STL 1101, E-STM 1101'],
            ['PPE 1101', 'Physical Education 1', 2, 1, 1, 'E-PEN 4306, E-PPF 1201'],
            ['ZGE 1101', 'Art Appreciation', 3, 1, 1, null],
            ['ZGE 1103', 'Ethics', 3, 1, 1, null],
            ['ZGE 1104', 'Mathematics in the Modern World', 3, 1, 1, null],
            // FIRST YEAR / SECOND SEMESTER
            ['CCS 1201', 'Intermediate Programming', 3, 1, 2, 'P-CCS 1101'],
            ['CMA 1101', 'Analysis 1 for CS', 3, 1, 2, null],
            ['CSP 1101', 'Social and Professional Issues in Computing', 3, 1, 2, 'P-CIC 1101'],
            ['MLC 1102', 'Literacy/Civic Welfare/Military Science 2', 3, 1, 2, 'E-STC 1102, E-STL 1102, E-STM 1102, P-MLC 1101'],
            ['PPE 1102', 'Physical Education 2', 2, 1, 2, 'E-PEN 4303, E-PEN 4304, E-PEN 4307, E-PEN 4308, E-PEN 4310, E-PEN 4311, E-PEN 4312, E-PEN 4313, E-PEN 4315, E-PEN 4317, E-PEN 4322'],
            ['ZGE 1105', 'Purposive Communication', 3, 1, 2, null],
            ['ZGE 1106', 'Readings in Philippine History', 3, 1, 2, null],
            ['ZGE 1108', 'Understanding the Self', 3, 1, 2, null],
            // SECOND YEAR / FIRST SEMESTER
            ['CCS 2101', 'Discrete Structures 1', 3, 2, 1, null],
            ['CCS 2102', 'Object-Oriented Programming', 3, 2, 1, 'P-CCS 1201'],
            ['CDS 1101', 'Data Structures and Algorithms', 3, 2, 1, 'P-CCS 1201'],
            ['CHC 1101', 'Human Computer Interaction', 3, 2, 1, 'P-CIC 1101'],
            ['CIM 1101', 'Information Management', 3, 2, 1, 'P-CCS 1201'],
            ['CMA 1102', 'Analysis 2 for CS', 3, 2, 1, 'P-CMA 1101'],
            ['PPE 1103', 'Physical Education 3', 2, 2, 1, 'E-PEN 4303, E-PEN 4304, E-PEN 4308, E-PEN 4310, E-PEN 4311, E-PEN 4312, E-PEN 4313, E-PEN 4317, E-PEN 4322, E-PPF 2309, E-PPF 2310, E-PPF 2315'],
            ['ZGE 1102', 'The Contemporary World', 3, 2, 1, null],
            // SECOND YEAR / SECOND SEMESTER
            ['CCS 2201', 'Architecture and Organization', 3, 2, 2, 'P-CCS 2102'],
            ['CCS 2202', 'Discrete Structures 2', 3, 2, 2, 'P-CCS 2101'],
            ['CCS 2203', 'Operating Systems', 3, 2, 2, 'P-CDS 1101'],
            ['CCS 2204', 'Statistics for CS', 3, 2, 2, null],
            ['CIA 1101', 'Information Assurance and Security 1', 3, 2, 2, 'P-CIM 1101'],
            ['CIP 1101', 'Integrative Programming and Technologies 1', 3, 2, 2, 'P-CCS 2102'],
            ['CSE 1101', 'Software Engineering 1', 3, 2, 2, 'P-CDS 1101, P-CIM 1101'],
            ['PPE 1104', 'Physical Education 4', 2, 2, 2, 'E-PEN 4303, E-PEN 4304, E-PEN 4308, E-PEN 4310, E-PEN 4311, E-PEN 4312, E-PEN 4313, E-PEN 4317, E-PEN 4322, E-PPF 2303, E-PPF 2304, E-PPF 2306, E-PPF 2308, E-PPF 2309, E-PPF 2310, E-PPF 2315, E-PPF 2320'],
            // THIRD YEAR / FIRST SEMESTER
            ['APC 3101', 'Physics for CS (with Electromagnetism)', 4, 3, 1, null],
            ['CCS 3101', 'Algorithm and Complexity', 3, 3, 1, 'P-CCS 2202, P-CDS 1101'],
            ['CCS 3102', 'Methods of Research for CS', 3, 3, 1, 'P-CCS 2204, P-CIA 1101, P-CIP 1101, P-CSE 1101'],
            ['CCS 3103', 'Networks and Communications', 3, 3, 1, 'P-CCS 2203'],
            ['CCS 3104', 'Software Engineering 2', 3, 3, 1, 'P-CSE 1101'],
            ['CDT 1101', 'Data Analytics', 3, 3, 1, 'P-CCS 2204, P-CIM 1101'],
            ['CMS 1101', 'Multimedia Systems', 3, 3, 1, 'P-CIP 1101'],
            // THIRD YEAR / SECOND SEMESTER
            ['CCS 3201', 'Artificial Intelligence', 3, 3, 2, 'P-CCS 2102'],
            ['CCS 3202', 'Automata Theory and Formal Languages', 3, 3, 2, 'P-CCS 3101'],
            ['CCS 4980', 'Thesis Writing 1 for CS', 3, 3, 2, 'P-CCS 3102, P-CCS 3104, P-CDT 1101'],
            ['CCS EL01', 'Professional Elective 1', 3, 3, 2, 'E-CCS 4303'],
            ['CDE 1101', 'Applications Development and Emerging Technologies', 3, 3, 2, 'P-CIM 1101'],
            ['ZGE EL01', 'GE Elective 1', 3, 3, 2, 'E-ZGE 4301, E-ZGE 4304, E-ZGE 4305, E-ZGE 4309'],
            ['ZPD 1102', 'Effective Communication with Personality Development', 3, 3, 2, null],
            ['CCS 4970', 'Practicum for CS', 3, 3, 2, null],
            // FOURTH YEAR / FIRST SEMESTER
            ['CCS 4101', 'Modeling and Simulation', 3, 4, 1, 'P-CMA 1102'],
            ['CCS 4102', 'Programming Languages', 3, 4, 1, 'P-CDS 1101'],
            ['CCS 4990', 'Thesis Writing 2 for CS', 3, 4, 1, 'P-CCS 4980'],
            ['CCS EL02', 'Professional Elective 2', 3, 4, 1, 'E-CCS 4301'],
            ['CNA 1101', 'Numerical Analysis for ITE', 3, 4, 1, 'P-CMA 1102'],
            ['ZGE 1107', 'Science, Technology, and Society', 3, 4, 1, null],
            ['ZGE 1109', 'Life and Works of Rizal', 3, 4, 1, null],
            // FOURTH YEAR / SECOND SEMESTER
            ['CCS 4201', 'Digital Electronics and Logic Design', 3, 4, 2, 'P-CCS 2101'],
            ['CCS EL03', 'Professional Elective 3', 3, 4, 2, null],
            ['CCS EL04', 'Professional Elective 4', 3, 4, 2, null],
            ['CIS 3202', 'Technopreneurship', 3, 4, 2, null],
            ['ZGE EL02', 'GE Elective 2', 3, 4, 2, 'E-ZGE 4301, E-ZGE 4304, E-ZGE 4305, E-ZGE 4309'],
            ['ZGE EL03', 'GE Elective 3', 3, 4, 2, null],
        ];
    }

    public function run(): void
    {
        $rows = $this->curriculumData();
        $sortOrder = 0;

        foreach ($rows as $row) {
            [$code, $title, $credits, $year, $semester, $prerequisites] = $row;

            $course = Course::firstOrCreate(
                ['code' => $code],
                [
                    'title' => $title,
                    'credits' => $credits,
                    'status' => 'published',
                ]
            );

            // Update title/credits if course already existed (e.g. from previous run)
            $course->update([
                'title' => $title,
                'credits' => $credits,
                'status' => 'published',
            ]);

            foreach (CollegeCourse::all() as $collegeCourse) {
                Curriculum::updateOrCreate(
                    [
                        'college_course_id' => $collegeCourse->id,
                        'course_id' => $course->id,
                        'year' => $year,
                        'semester' => $semester,
                    ],
                    [
                        'prerequisites' => $prerequisites,
                        'sort_order' => $sortOrder,
                    ]
                );
            }

            $sortOrder++;
        }
    }
}
