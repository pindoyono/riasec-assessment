<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forced_choice_assessment_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('forced_choice_question_id');
            $table->enum('selected_option', ['A', 'B']);
            $table->char('selected_type', 1); // R, I, A, S, E, C
            $table->timestamp('answered_at')->nullable();
            $table->timestamps();

            $table->foreign('forced_choice_question_id', 'fc_answers_question_fk')
                  ->references('id')->on('forced_choice_questions')->cascadeOnDelete();

            $table->unique(['assessment_id', 'forced_choice_question_id'], 'fc_answers_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forced_choice_assessment_answers');
    }
};
