<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('discussions_messages', function (Blueprint $table) {
            $table->id();
            $table->text('content');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('thread_id')->constrained('discussions_threads')->onDelete('cascade');
            $table->foreignId('parent_message_id')->nullable()->constrained('discussions_messages')->onDelete('cascade');
            $table->boolean('is_edited')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('discussions_messages');
    }
};

