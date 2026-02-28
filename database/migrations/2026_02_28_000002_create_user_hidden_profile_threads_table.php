<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_hidden_profile_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('thread_id')->constrained('discussions_threads')->cascadeOnDelete();
            $table->timestamps();
            $table->unique(['user_id', 'thread_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_hidden_profile_threads');
    }
};
