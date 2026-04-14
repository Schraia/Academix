<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('grades_visible_on_profile')->default(true)->after('bio');
            $table->boolean('can_access_course_archive')->default(true)->after('grades_visible_on_profile');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->string('banner_object_position', 64)->nullable()->after('banner_path');
        });

        Schema::table('user_notifications', function (Blueprint $table) {
            $table->string('kind', 32)->nullable()->after('user_id');
            $table->foreignId('course_id')->nullable()->after('kind')->constrained('courses')->nullOnDelete();
            $table->index(['user_id', 'kind', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('user_notifications', function (Blueprint $table) {
            $table->dropForeign(['course_id']);
            $table->dropIndex(['user_id', 'kind', 'created_at']);
            $table->dropColumn(['kind', 'course_id']);
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('banner_object_position');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['grades_visible_on_profile', 'can_access_course_archive']);
        });
    }
};
