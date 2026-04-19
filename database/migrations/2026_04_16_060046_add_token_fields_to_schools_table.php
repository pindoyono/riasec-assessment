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
        Schema::table('schools', function (Blueprint $table) {
            $table->string('registration_token')->nullable()->unique()->after('is_active');
            $table->timestamp('token_expires_at')->nullable()->after('registration_token');
            $table->integer('token_valid_hours')->default(24)->after('token_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn(['registration_token', 'token_expires_at', 'token_valid_hours']);
        });
    }
};
