<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class CarburantController extends Controller
{
    public function index()
    {
        // 🛡️ Check les tables possibles f la base de données SQLite
        if (Schema::hasTable('carburants')) {
            $recharges = \DB::table('carburants')->orderBy('created_at', 'desc')->get();
        } elseif (Schema::hasTable('recharges_carburant')) {
            $recharges = \DB::table('recharges_carburant')->orderBy('created_at', 'desc')->get();
        } else {
            $recharges = collect([]); // Safe mode
        }

        return view('carburant.index', compact('recharges'));
    }
}