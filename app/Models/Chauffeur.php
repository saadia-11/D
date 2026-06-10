<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Chauffeur extends Model {
    protected $fillable = ["nom", "prenom", "telephone", "matricule_camion", "statut_dispo", "permis_categorie"];
    public function dossiers() { return $this->hasMany(DossierTransit::class); }
    public function maintenances() { return $this->hasMany(Maintenance::class); }
    public function frais() { return $this->hasMany(FraisRoute::class); }
}