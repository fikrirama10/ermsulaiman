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
        Schema::table('demo_detail_rekap_medis', function (Blueprint $table) {
            $table->text('soap_data')->nullable()->after('anamnesa_dokter')->comment('Data SOAP (Subjective, Objective, Assessment, Plan) dalam format JSON');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_detail_rekap_medis', function (Blueprint $table) {
            $table->dropColumn('soap_data');
        });
    }
};
