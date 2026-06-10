<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use App\Models\DossierTransit;
use App\Models\FraisRoute;
use Illuminate\Http\Request;

class FraisRouteController extends Controller
{
    public function index()
    {
        $recharges = FraisRoute::with('dossier')->latest()->get();
        $dossiers = DossierTransit::with('client')->latest()->get();

        return view('carburant.index', compact('recharges', 'dossiers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dossier_id' => ['required', 'exists:dossiers_transit,id'],
            'montant_gasoil' => ['required', 'numeric', 'min:0'],
            'date_recharge' => ['nullable', 'date'],
            'station' => ['nullable', 'string', 'max:255'],
        ]);

        $dossier = DossierTransit::findOrFail($validated['dossier_id']);
        $chauffeurId = $dossier->chauffeur_id ?? Chauffeur::query()->value('id');

        if ($chauffeurId === null) {
            return back()
                ->withErrors(['dossier_id' => 'Ajoutez au moins un chauffeur avant d’enregistrer une recharge.'])
                ->withInput();
        }

        FraisRoute::create([
            'dossier_id' => $validated['dossier_id'],
            'chauffeur_id' => $chauffeurId,
            'montant_gasoil' => $validated['montant_gasoil'],
            'date_recharge' => $validated['date_recharge'] ?? now()->toDateString(),
            'station' => $validated['station'] ?? null,
        ]);

        return redirect()
            ->route('carburant.index')
            ->with('success', 'Recharge de carburant enregistrée avec succès');
    }
}
