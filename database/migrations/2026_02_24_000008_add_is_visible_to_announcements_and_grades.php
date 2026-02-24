<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('course_announcements', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true)->after('content');
        });
        Schema::table('course_grades', function (Blueprint $table) {
            $table->boolean('is_visible')->default(true)->after('graded_at');
        });
    }

    public function down(): void
    {
        Schema::table('course_announcements', function (Blueprint $table) {
            $table->dropColumn('is_visible');
        });
        Schema::table('course_grades', function (Blueprint $table) {
            $table->dropColumn('is_visible');
        });
    }
};
