<?php
namespace App\Http\Controllers;
use App\Models\{Client, DossierTransit, Chauffeur, Facture, Maintenance, FraisRoute};
class DashboardController extends Controller {
    public function index() {
        $stats = [
            "total_clients" => Client::count(),
            "dossiers_actifs" => DossierTransit::whereIn("statut", ["En Douane", "En Cours de Livraison"])->count(),
            "alertes_panne" => Chauffeur::where("statut_dispo", "En Panne")->count(),
            "chiffre_affaire" => Facture::sum("total_ttc"),
            "total_frais" => FraisRoute::sum("montant_gasoil") + FraisRoute::sum("montant_peage") + FraisRoute::sum("autres_frais"),
            "taux_livraison" => DossierTransit::count() > 0 ? round((DossierTransit::where("statut", "Livré")->count() / DossierTransit::count()) * 100, 1) : 0
        ];
        $stats["benefice_net"] = $stats["chiffre_affaire"] - $stats["total_frais"];
        $recent_dossiers = DossierTransit::with("client")->latest()->take(5)->get();
        $alertes_maintenance = Maintenance::where("statut", "En Cours")->with("chauffeur")->get();
        return view("dashboard", compact("stats", "recent_dossiers", "alertes_maintenance"));


        $alertes = \App\Models\Camion::where('assurance_exp', '<=', now()->addDays(7))
                 ->where('assurance_exp', '>=', now())
                 ->get();
                 
    // Widget d l-dossiers f 7alat "En Attente" (Circuit rouge douanier)
    $dossiers_urgents = \App\Models\DossierTransit::where('statut', 'En attente')->count();

    return view('dashboard', compact('alertes', 'dossiers_urgents'));
    }

}