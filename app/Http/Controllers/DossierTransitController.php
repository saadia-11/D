<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use App\Models\Client;
use App\Models\DossierTransit;
use Illuminate\Http\Request;

class DossierTransitController extends Controller
{
    public function index()
    {
        $dossiers = DossierTransit::with('client')->latest()->get();
        $clients = Client::orderBy('nom')->get();
        $chauffeurs = Chauffeur::where('statut_dispo', 'Disponible')->get();

        return view('dossiers.index', compact('dossiers', 'clients', 'chauffeurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_dum' => ['nullable', 'string', 'max:255', 'unique:dossiers_transit,numero_dum'],
            'client_id' => ['required', 'exists:clients,id'],
            'valeur_declarée' => ['nullable', 'numeric', 'min:0'],
            'statut' => ['nullable', 'string', 'max:255'],
        ]);

        DossierTransit::create([
            'numero_dum' => $validated['numero_dum'] ?? null,
            'client_id' => $validated['client_id'],
            'valeur_declarée' => $validated['valeur_declarée'] ?? 0,
            'statut' => $validated['statut'] ?? 'Créé',
            'provenance_destination' => 'Non renseignée',
        ]);

        return redirect()
            ->route('dossiers.index')
            ->with('success', 'Dossier de transit ouvert avec succès');
    }

    public function update(Request $request, $id)
    {
        $dossier = DossierTransit::findOrFail($id);
        $dossier->update($request->all());

        if ($request->statut === 'En Cours de Livraison' && $request->chauffeur_id) {
            Chauffeur::where('id', $request->chauffeur_id)->update(['statut_dispo' => 'En Mission']);
        }

        if ($request->statut === 'Livré' && $dossier->chauffeur_id) {
            Chauffeur::where('id', $dossier->chauffeur_id)->update(['statut_dispo' => 'Disponible']);
        }

        return redirect()
            ->route('dossiers.index')
            ->with('success', 'Statut logistique à jour.');
    }
}
