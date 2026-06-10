<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Camion extends Model
{
    protected $fillable = ['matricule', 'date_visite_technique', 'statut'];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
