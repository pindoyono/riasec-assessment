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
        Schema::create('assessment_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('smk_major_id')->constrained()->cascadeOnDelete();
            $table->integer('rank'); // Peringkat rekomendasi (1, 2, 3, ...)
            $table->decimal('match_score', 5, 2); // Skor kecocokan (0-100)
            $table->text('match_reason')->nullable(); // Alasan kecocokan
            $table->timestamps();

            $table->unique(['assessment_id', 'smk_major_id']);
            $table->index(['assessment_id', 'rank']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_recommendations');
    }
};
