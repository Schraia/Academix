<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectPrerequisiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * PPE 1st year 2nd sem requires 1st year 1st sem. Add more rules as needed.
     */
    public function run(): void
    {
        $rows = [
            ['subject_type' => 'PPE', 'year' => 1, 'semester' => 2, 'req_year' => 1, 'req_semester' => 1],
            // Add more prerequisites here, e.g.:
            // ['subject_type' => 'PPE', 'year' => 2, 'semester' => 1, 'req_year' => 1, 'req_semester' => 2],
            // ['subject_type' => 'MLC', 'year' => 1, 'semester' => 2, 'req_year' => 1, 'req_semester' => 1],
        ];

        foreach ($rows as $row) {
            DB::table('subject_prerequisites')->updateOrInsert(
                [
                    'subject_type' => $row['subject_type'],
                    'year' => $row['year'],
                    'semester' => $row['semester'],
                ],
                $row + ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
