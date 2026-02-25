<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::updateOrCreate(
            ['email' => 'test@example.com'],
            ['name' => 'Test User', 'password' => Hash::make('password')]
        );

        User::updateOrCreate(
            ['email' => 'student01@academix.edu'],
            ['name' => 'Test Student', 'password' => Hash::make('student01'), 'role' => 'student']
        );
        User::updateOrCreate(
            ['email' => 'instructor01@academix.edu'],
            ['name' => 'Test Instructor', 'password' => Hash::make('instructor01'), 'role' => 'instructor']
        );
        User::updateOrCreate(
            ['email' => 'admin01@academix.edu'],
            ['name' => 'Test Admin', 'password' => Hash::make('admin01'), 'role' => 'admin']
        );

        $this->call([
            CollegeCourseSeeder::class,
            CurriculumSeeder::class,
            CollegeSectionSeeder::class,
            SectionSubjectScheduleSeeder::class,
        ]);
    }
}
