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
        Schema::create('riasec_categories', function (Blueprint $table) {
            $table->id();
            $table->string('code', 1)->unique(); // R, I, A, S, E, C
            $table->string('name'); // Realistic, Investigative, etc.
            $table->text('description');
            $table->text('characteristics'); // Karakteristik kepribadian
            $table->text('preferred_activities'); // Aktivitas yang disukai
            $table->text('strengths'); // Kekuatan
            $table->string('color')->nullable(); // Warna untuk visualisasi
            $table->string('icon')->nullable(); // Icon name
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riasec_categories');
    }
};
