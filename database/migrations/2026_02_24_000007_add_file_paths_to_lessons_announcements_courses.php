<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons_modules', function (Blueprint $table) {
            $table->string('attachment_path')->nullable()->after('video_url');
        });
        Schema::table('course_announcements', function (Blueprint $table) {
            $table->string('image_path')->nullable()->after('content');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->string('banner_path')->nullable()->after('description');
        });
    }

    public function down(): void
    {
        Schema::table('lessons_modules', function (Blueprint $table) {
            $table->dropColumn('attachment_path');
        });
        Schema::table('course_announcements', function (Blueprint $table) {
            $table->dropColumn('image_path');
        });
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn('banner_path');
        });
    }
};
