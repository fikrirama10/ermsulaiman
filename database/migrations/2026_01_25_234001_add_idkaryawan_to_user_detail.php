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
        Schema::table('user_detail', function (Blueprint $table) {
            $table->unsignedBigInteger('idkaryawan')->nullable()->after('iddokter');
            // Adding a foreign key constraint is good practice if possible, 
            // but sometime users prefer loose coupling or rely on code. 
            // Given the existing structure doesn't seem to enforce strict FKs everywhere (based on index method in DokterController doing left joins without mention of FK constraints in errors),
            // I will add the index at least.
            $table->index('idkaryawan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_detail', function (Blueprint $table) {
            $table->dropColumn('idkaryawan');
        });
    }
};
