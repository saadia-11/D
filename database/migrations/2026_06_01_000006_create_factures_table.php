<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up() {
        Schema::create("factures", function (Blueprint $table) {
            $table->id(); $table->foreignId("dossier_id")->constrained("dossiers_transit")->onDelete("cascade");
            $table->string("numero_facture")->unique(); $table->date("date_facture");
            $table->double("montant_prestations"); $table->double("montant_debours"); $table->double("total_ttc");
            $table->enum("statut_paiement", ["Payée", "Impayée"])->default("Impayée"); $table->timestamps();
        });
    }
    public function down() { Schema::dropIfExists("factures"); }
};