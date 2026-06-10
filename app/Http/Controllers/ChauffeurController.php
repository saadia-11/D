<?php

namespace App\Http\Controllers;

use App\Models\Chauffeur;
use Illuminate\Http\Request;

class ChauffeurController extends Controller
{
    public function index()
    {
        $chauffeurs = Chauffeur::latest()->get();

        return view('chauffeurs.index', compact('chauffeurs'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:255'],
            'matricule_camion' => ['required', 'string', 'max:255', 'unique:chauffeurs,matricule_camion'],
        ]);

        Chauffeur::create([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'] ?? '',
            'telephone' => $validated['telephone'],
            'matricule_camion' => $validated['matricule_camion'],
        ]);

        return redirect()
            ->route('chauffeurs.index')
            ->with('success', 'Chauffeur ajouté avec succès');
    }
}
