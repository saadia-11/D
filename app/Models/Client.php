<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'telephone',
        'adresse',
        'entreprise',
        'ice',
        'ville',
    ];

    public function dossiers(): HasMany
    {
        return $this->hasMany(DossierTransit::class);
    }
}
