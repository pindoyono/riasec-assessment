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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('nisn')->nullable()->unique();
            $table->string('name');
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->string('class')->nullable(); // Kelas (9A, 9B, etc.)
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('registration_token')->unique(); // Token untuk akses assessment
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['school_id', 'class']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
