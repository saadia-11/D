<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Maintenance extends Model {
    protected $fillable = ["camion_id", "chauffeur_id", "type_entretien", "type_panne", "date_prevue", "date_reparation", "montant_facture", "statut"];
    public function camion() { return $this->belongsTo(Camion::class); }
    public function chauffeur() { return $this->belongsTo(Chauffeur::class); }
}
