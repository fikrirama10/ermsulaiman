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
        Schema::create('penjualan_obat', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal');
            $table->string('nomor_faktur')->unique();
            $table->string('nama_pembeli')->nullable();
            $table->unsignedBigInteger('id_rawat')->nullable();
            $table->text('keterangan')->nullable();
            $table->json('resep_obat')->nullable();
            $table->decimal('total_harga', 15, 2);
            $table->unsignedBigInteger('id_user');
            $table->timestamps();
        });

        Schema::create('penjualan_obat_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_penjualan');
            $table->unsignedBigInteger('id_obat');
            $table->decimal('harga', 15, 2);
            $table->integer('jumlah');
            $table->decimal('total', 15, 2);
            $table->timestamps();

            $table->foreign('id_penjualan')->references('id')->on('penjualan_obat')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan_obat_detail');
        Schema::dropIfExists('penjualan_obat');
    }
};
