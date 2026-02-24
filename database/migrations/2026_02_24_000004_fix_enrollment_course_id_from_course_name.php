<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Fix enrollments that have the wrong course_id: set course_id to the course
     * that matches enrollment.course_name (by courses.title). If no matching course
     * exists, create one so each enrollment points to the correct subject.
     */
    public function up(): void
    {
        $enrollments = DB::table('enrollments')->get();

        foreach ($enrollments as $e) {
            $courseName = $e->course_name;
            if (empty($courseName)) {
                continue;
            }

            $course = DB::table('courses')->where('title', $courseName)->first();
            if (! $course) {
                $baseCode = 'CRS' . substr(md5($courseName), 0, 6);
                $code = $baseCode;
                $n = 0;
                while (DB::table('courses')->where('code', $code)->exists()) {
                    $code = $baseCode . (string) (++$n);
                }
                $id = DB::table('courses')->insertGetId([
                    'title' => $courseName,
                    'code' => $code,
                    'description' => null,
                    'block_section_id' => null,
                    'credits' => 0,
                    'status' => 'published',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $course = (object) ['id' => $id];
            }

            if ((int) $e->course_id === (int) $course->id) {
                continue;
            }
            $duplicate = DB::table('enrollments')
                ->where('user_id', $e->user_id)
                ->where('course_id', $course->id)
                ->where('id', '!=', $e->id)
                ->exists();
            if ($duplicate) {
                DB::table('enrollments')->where('id', $e->id)->delete();
            } else {
                DB::table('enrollments')->where('id', $e->id)->update([
                    'course_id' => $course->id,
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        // Cannot safely reverse; leave data as-is.
    }
};
