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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('description')->nullable();
            $table->string('group')->default('general');
            $table->timestamps();
        });

        // Insert Default Settings
        DB::table('settings')->insert([
            'key' => 'surat_format',
            'value' => '{SEQUENCE}/SURAT-{CODE}/{ROMAN}/{YEAR}', // Default format
            'description' => 'Format Penomoran Surat',
            'group' => 'surat',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
