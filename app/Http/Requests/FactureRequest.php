<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FactureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dossier_id' => ['required', 'integer', 'exists:dossiers_transit,id'],
            'montant_prestations' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'montant_debours' => ['required', 'numeric', 'min:0', 'max:999999999.99'],
            'date_facture' => ['required', 'date'],
            'statut' => ['required', 'string', Rule::in(['En attente', 'Payée', 'Annulée'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'dossier_id' => 'dossier de transit',
            'montant_prestations' => 'montant des prestations',
            'montant_debours' => 'montant des débours',
            'date_facture' => 'date de facture',
            'statut' => 'statut',
        ];
    }
}
