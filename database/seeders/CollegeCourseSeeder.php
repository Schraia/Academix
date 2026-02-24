<?php

namespace Database\Seeders;

use App\Models\CollegeCourse;
use Illuminate\Database\Seeder;

class CollegeCourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            ['name' => 'Electronic Engineering', 'code' => 'EE'],
            ['name' => 'Computer Engineering', 'code' => 'CE'],
            ['name' => 'Computer Science', 'code' => 'CS'],
        ];

        foreach ($courses as $course) {
            CollegeCourse::firstOrCreate(
                ['code' => $course['code']],
                ['name' => $course['name']]
            );
        }
    }
}
