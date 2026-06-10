<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->foreignId('camion_id')->nullable()->after('id')->constrained('camions')->onDelete('set null');
            $table->string('type_entretien')->nullable()->after('chauffeur_id');
            $table->date('date_prevue')->nullable()->after('type_entretien');
        });
    }

    public function down(): void
    {
        Schema::table('maintenances', function (Blueprint $table) {
            $table->dropConstrainedForeignId('camion_id');
            $table->dropColumn(['type_entretien', 'date_prevue']);
        });
    }
};
