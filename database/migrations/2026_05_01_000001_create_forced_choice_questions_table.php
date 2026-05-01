<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('forced_choice_questions', function (Blueprint $table) {
            $table->id();
            $table->string('prompt')->default('Pilih aktivitas yang lebih kamu sukai');
            $table->string('option_a_text');
            $table->char('option_a_type', 1); // R, I, A, S, E, C
            $table->string('option_b_text');
            $table->char('option_b_type', 1); // R, I, A, S, E, C
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('forced_choice_questions');
    }
};
