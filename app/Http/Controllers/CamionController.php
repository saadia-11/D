<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CamionController extends Controller
{
    public function index()
    {
        // 🛡️ N-choufu wach la table 'camions' kayna f la base de données SQLite
        if (Schema::hasTable('camions')) {
            $camions = \DB::table('camions')->orderBy('created_at', 'desc')->get();
        } 
        // 🛡️ Ila makaynach 'camions', checki wach m-smiyha 'vehicules' f le projet dyalk
        elseif (Schema::hasTable('vehicules')) {
            $camions = \DB::table('vehicules')->orderBy('created_at', 'desc')->get();
        } 
        // 🛡️ Ila mal9a 7ta wefda, y-rj3 collect khwya bch l-page t-t7al bla ta chi erreur SQL
        else {
            $camions = collect([]); 
        }

        return view('camions.index', compact('camions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matricule' => ['required', 'string', 'max:255'],
            'date_visite_technique' => ['nullable', 'date'],
            'statut' => ['nullable', 'string', 'max:255'],
        ]);

        $table = Schema::hasTable('camions')
            ? 'camions'
            : (Schema::hasTable('vehicules') ? 'vehicules' : null);

        if ($table === null) {
            return back()
                ->withErrors(['matricule' => "La table des camions n'existe pas encore."])
                ->withInput();
        }

        $now = now();
        $data = [
            'matricule' => $validated['matricule'],
            'date_visite_technique' => $validated['date_visite_technique'] ?? null,
            'statut' => $validated['statut'] ?? 'Disponible',
        ];

        if (Schema::hasColumn($table, 'created_at')) {
            $data['created_at'] = $now;
        }

        if (Schema::hasColumn($table, 'updated_at')) {
            $data['updated_at'] = $now;
        }

        DB::table($table)->insert($data);

        return redirect()
            ->route('camions.index')
            ->with('success', 'Camion ajouté avec succès');
    }
}
