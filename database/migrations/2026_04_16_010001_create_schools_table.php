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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('npsn')->nullable()->unique();
            $table->text('address')->nullable();
            $table->string('district')->nullable(); // Kecamatan
            $table->string('city')->nullable(); // Kabupaten/Kota
            $table->string('province')->nullable();
            $table->enum('type', ['smk', 'mak'])->default('smk');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
