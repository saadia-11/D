<?php

namespace App\Http\Controllers;

use App\Models\Camion; // ⚠️ Rj3i smyt le modèle dial les camions kima m-smmiah f le projet (Ex: Camion ou Vehicule)
use Illuminate\Http\Request;

class CamionController extends Controller
{
    public function index()
    {
        // 1. Njibou ga3 les camions ordered by date wella matricule
        // Ilamazal maddrtych le modèle, khwّi l-code wella khlih b7al had le style standard:
        $camions = class_exists(\App\Models\Camion::class) ? \App\Models\Camion::orderBy('created_at', 'desc')->get() : collect([]);

        // 2. Render dial la view perfectly
        // 💡 Choufi ila 3ndk la view jdiya f resources/views/camions/index.blade.php wella ghir camions.blade.php
        if (view()->exists('camions.index')) {
            return view('camions.index', compact('camions'));
        } elseif (view()->exists('camions')) {
            return view('camions', compact('camions'));
        } else {
            // Création d'une vue de secours rapide au cas où
            return "📁 Le contrôleur fonctionne ! Pensez à vérifier l'existence de votre fichier view dans resources/views/camions.blade.php";
        }
    }
}