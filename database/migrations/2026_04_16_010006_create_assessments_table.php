<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('assessment_code')->unique(); // Kode unik assessment
            $table->enum('status', ['pending', 'in_progress', 'completed', 'expired'])->default('pending');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->integer('duration_seconds')->nullable(); // Durasi pengerjaan
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();

            // Skor per kategori RIASEC (0-100)
            $table->integer('score_r')->nullable(); // Realistic
            $table->integer('score_i')->nullable(); // Investigative
            $table->integer('score_a')->nullable(); // Artistic
            $table->integer('score_s')->nullable(); // Social
            $table->integer('score_e')->nullable(); // Enterprising
            $table->integer('score_c')->nullable(); // Conventional

            // Primary RIASEC codes (top 3)
            $table->string('riasec_code', 3)->nullable(); // e.g., "RIA"

            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index('riasec_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
