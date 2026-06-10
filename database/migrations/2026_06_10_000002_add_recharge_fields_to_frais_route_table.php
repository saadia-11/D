<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('frais_route', function (Blueprint $table) {
            $table->date('date_recharge')->nullable()->after('montant_gasoil');
            $table->string('station')->nullable()->after('date_recharge');
        });
    }

    public function down(): void
    {
        Schema::table('frais_route', function (Blueprint $table) {
            $table->dropColumn(['date_recharge', 'station']);
        });
    }
};
