<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');

            $table->string('payment_type', 40)->default('online');
            $table->string('payment_evidence_path')->nullable();

            $table->timestamp('submitted_at')->useCurrent();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('admin_notes')->nullable();

            $table->timestamps();
        });

        Schema::create('pending_enrollment_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pending_enrollment_id')->constrained('pending_enrollments')->cascadeOnDelete();

            $table->foreignId('college_course_id')->nullable()->constrained('college_courses')->nullOnDelete();
            $table->string('course_name');
            $table->string('section_name');
            $table->string('section_code')->nullable();
            $table->string('time_slot')->nullable();
            $table->string('days')->nullable();
            $table->unsignedTinyInteger('units')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_enrollment_items');
        Schema::dropIfExists('pending_enrollments');
    }
};

