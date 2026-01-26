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
        Schema::create('surat', function (Blueprint $table) {
            $table->id();
            $table->string('no_surat')->unique();
            $table->enum('jenis_surat', ['sakit', 'lahir', 'kematian', 'rujukan', 'lainnya']);

            // Relasi ke Pasien (Nullable untuk fleksibilitas jika pasien luar)
            $table->unsignedBigInteger('id_pasien')->nullable();
            $table->string('nama_pasien')->nullable()->comment('Snapshot nama pasien saat surat dibuat');

            // Relasi ke Dokter
            $table->unsignedBigInteger('id_dokter')->nullable();
            $table->string('nama_dokter')->nullable()->comment('Snapshot nama dokter saat surat dibuat');

            $table->date('tanggal_surat');

            // JSON column untuk menyimpan detail spesifik per jenis surat
            // Sakit: lama_istirahat, dari_tgl, sampai_tgl, diagnosa
            // Lahir: nama_bayi, jenis_kelamin, berat, panjang, nama_ayah, nama_ibu
            // Kematian: sebab_kematian, waktu_kematian, tempat_kematian
            // Rujukan: faskes_tujuan, alasan_rujukan, tindakan_sementara
            $table->json('konten')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            // Indexing for search performance
            $table->index('no_surat');
            $table->index('jenis_surat');
            $table->index('tanggal_surat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
