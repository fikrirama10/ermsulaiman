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
        Schema::table('labor_records', function (Blueprint $table) {
            $table->json('initial_risk_assessment')->nullable()->after('status');
            $table->text('initial_assessment_notes')->nullable()->after('initial_risk_assessment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('labor_records', function (Blueprint $table) {
            $table->dropColumn(['initial_risk_assessment', 'initial_assessment_notes']);
        });
    }
};
