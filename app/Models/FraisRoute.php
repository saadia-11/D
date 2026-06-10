<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class FraisRoute extends Model {
    protected $table = "frais_route";
    protected $fillable = ["dossier_id", "chauffeur_id", "montant_gasoil", "montant_peage", "autres_frais", "date_recharge", "station", "note"];
    public function dossier() { return $this->belongsTo(DossierTransit::class); }
    public function chauffeur() { return $this->belongsTo(Chauffeur::class); }
}
