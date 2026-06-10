<?php

namespace App\Http\Controllers;

use App\Models\Camion;
use App\Models\Chauffeur;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        $maintenances = Maintenance::with('camion')->latest()->get();
        $camions = Camion::latest()->get();

        return view('maintenance.index', compact('maintenances', 'camions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'camion_id' => ['required', 'exists:camions,id'],
            'type_entretien' => ['required', 'string', 'max:255'],
            'date_prevue' => ['required', 'date'],
            'montant_facture' => ['nullable', 'numeric', 'min:0'],
        ]);

        $camion = Camion::findOrFail($validated['camion_id']);
        $chauffeurId = Chauffeur::where('matricule_camion', $camion->matricule)->value('id')
            ?? Chauffeur::query()->value('id');

        if ($chauffeurId === null) {
            return back()
                ->withErrors(['camion_id' => 'Ajoutez au moins un chauffeur avant de planifier un entretien.'])
                ->withInput();
        }

        Maintenance::create([
            'camion_id' => $validated['camion_id'],
            'chauffeur_id' => $chauffeurId,
            'type_entretien' => $validated['type_entretien'],
            'type_panne' => $validated['type_entretien'],
            'date_prevue' => $validated['date_prevue'],
            'date_reparation' => $validated['date_prevue'],
            'montant_facture' => $validated['montant_facture'] ?? 0,
            'statut' => 'En Cours',
        ]);

        return redirect()
            ->route('maintenance.index')
            ->with('success', 'Entretien planifié avec succès');
    }
}
