<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('discussions_threads', function (Blueprint $table) {
            $table->foreignId('announcement_id')->nullable()->after('course_id')->constrained('course_announcements')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('discussions_threads', function (Blueprint $table) {
            $table->dropForeign(['announcement_id']);
        });
    }
};
