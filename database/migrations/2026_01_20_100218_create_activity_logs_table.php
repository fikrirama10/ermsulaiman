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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('log_name')->nullable()->index(); // Kategori: kunjungan, rekam_medis, tindakan, dll
            $table->text('description'); // Deskripsi aktivitas
            $table->nullableMorphs('subject'); // Model yang diubah (polymorphic)
            $table->nullableMorphs('causer'); // User yang melakukan (polymorphic)
            $table->string('event')->nullable()->index(); // created, updated, deleted, viewed
            $table->json('properties')->nullable(); // Data before & after
            $table->string('batch_uuid')->nullable()->index(); // Untuk group operasi bulk

            // Additional fields
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->string('url')->nullable();
            $table->string('method', 10)->nullable();

            // Metadata khusus untuk medical records
            $table->string('no_rm')->nullable()->index(); // No Rekam Medis
            $table->unsignedBigInteger('idrawat')->nullable()->index(); // ID Rawat
            $table->string('poli')->nullable(); // Nama Poli
            $table->string('dokter')->nullable(); // Nama Dokter

            $table->timestamps();
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
