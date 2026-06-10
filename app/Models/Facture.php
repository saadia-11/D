<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    protected $fillable = [
        'dossier_id',
        'numero_facture',
        'date_facture',
        'montant_prestations',
        'montant_debours',
        'base_tva',
        'montant_tva',
        'total_ttc',
        'statut_paiement',
    ];

    protected $casts = [
        'date_facture' => 'date',
        'montant_prestations' => 'decimal:2',
        'montant_debours' => 'decimal:2',
        'base_tva' => 'decimal:2',
        'montant_tva' => 'decimal:2',
        'total_ttc' => 'decimal:2',
    ];

    public function dossier(): BelongsTo
    {
        return $this->belongsTo(DossierTransit::class);
    }
}
