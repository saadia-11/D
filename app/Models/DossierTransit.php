<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DossierTransit extends Model
{
    protected $table = 'dossiers_transit';

    protected $fillable = [
        'client_id',
        'chauffeur_id',
        'numero_dum',
        'mode_transport',
        'provenance_destination',
        'description_marchandise',
        'valeur_declarée',
        'statut',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function chauffeur(): BelongsTo
    {
        return $this->belongsTo(Chauffeur::class);
    }

    public function facture(): HasOne
    {
        return $this->hasOne(Facture::class, 'dossier_id');
    }

    public function factures(): HasMany
    {
        return $this->hasMany(Facture::class, 'dossier_id');
    }

    public function fraisRoute(): HasMany
    {
        return $this->hasMany(FraisRoute::class, 'dossier_id');
    }
}
