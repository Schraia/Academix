<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained('messages')->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('read_at')->nullable();
            $table->enum('folder', ['inbox', 'trash', 'archived'])->default('inbox');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_recipients');
    }
};
