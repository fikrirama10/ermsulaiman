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
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->unique();
            $table->string('nama_karyawan');
            $table->enum('kategori', ['Perawat', 'Bidan', 'Tenaga Kesehatan Lain', 'Administrasi', 'IT', 'Tenaga Medis Lain', 'Tenaga Non Medis Lain', 'dll.'])->default('Perawat');
            $table->string('jabatan')->nullable();
            $table->string('bagian')->nullable();
            $table->boolean('status')->default(true)->comment('1: Aktif, 0: Nonaktif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('karyawan');
    }
};
