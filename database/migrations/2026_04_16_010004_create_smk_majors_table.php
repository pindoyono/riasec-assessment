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
        Schema::create('smk_majors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode jurusan
            $table->string('name'); // Nama jurusan
            $table->string('program_keahlian'); // Program Keahlian
            $table->string('bidang_keahlian'); // Bidang Keahlian
            $table->text('description')->nullable();
            $table->text('career_prospects')->nullable(); // Prospek karir
            $table->text('skills_learned')->nullable(); // Keterampilan yang dipelajari
            $table->json('riasec_profile')->nullable(); // Primary RIASEC codes like ["R", "I"]
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smk_majors');
    }
};
