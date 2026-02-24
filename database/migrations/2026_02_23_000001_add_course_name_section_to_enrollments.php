<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $conn = Schema::getConnection();
        $driver = $conn->getDriverName();

        if ($driver === 'mysql') {
            $fks = DB::select("
                SELECT DISTINCT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'enrollments' AND REFERENCED_TABLE_NAME IS NOT NULL
            ", [config('database.connections.mysql.database')]);
            foreach ($fks as $fk) {
                $conn->statement('ALTER TABLE enrollments DROP FOREIGN KEY `' . $fk->CONSTRAINT_NAME . '`');
            }
        } else {
            Schema::table('enrollments', function (Blueprint $table) {
                $table->dropForeign(['course_id']);
                $table->dropForeign(['user_id']);
            });
        }

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropUnique(['user_id', 'course_id']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->string('course_name')->nullable()->after('course_id');
            $table->string('section_name')->nullable()->after('course_name');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn(['course_name', 'section_name']);
            $table->unique(['user_id', 'course_id']);
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};
