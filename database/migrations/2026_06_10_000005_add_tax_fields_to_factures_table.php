<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->decimal('base_tva', 12, 2)->default(0)->after('montant_debours');
            $table->decimal('montant_tva', 12, 2)->default(0)->after('base_tva');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn(['base_tva', 'montant_tva']);
        });
    }
};
