<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // For SQLite, we need to recreate the column
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->enum('type', ['smk', 'mak'])->default('smk')->after('province');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schools', function (Blueprint $table) {
            $table->dropColumn('type');
        });

        Schema::table('schools', function (Blueprint $table) {
            $table->enum('type', ['smp', 'mts', 'other'])->default('smp')->after('province');
        });
    }
};
