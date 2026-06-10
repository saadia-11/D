<?php

namespace App\Http\Controllers;

use App\Http\Requests\FactureRequest;
use App\Models\DossierTransit;
use App\Models\Facture;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class FactureController extends Controller
{
    public function index(): View
    {
        $factures = Facture::with('dossier.client')
            ->latest()
            ->get();

        $dossiers = DossierTransit::with('client')
            ->latest()
            ->get();

        return view('factures.index', compact('factures', 'dossiers'));
    }

    public function create(): View
    {
        $dossiers = DossierTransit::with('client')
            ->latest()
            ->get();

        return view('factures.create', compact('dossiers'));
    }

    public function store(FactureRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $baseTva = (float) $validated['montant_prestations'];
        $montantTva = round($baseTva * 0.20, 2);
        $montantDebours = (float) $validated['montant_debours'];
        $totalTtc = round($baseTva + $montantTva + $montantDebours, 2);

        Facture::create([
            'dossier_id' => $validated['dossier_id'],
            'numero_facture' => $this->generateInvoiceNumber(),
            'date_facture' => $validated['date_facture'],
            'montant_prestations' => $baseTva,
            'montant_debours' => $montantDebours,
            'base_tva' => $baseTva,
            'montant_tva' => $montantTva,
            'total_ttc' => $totalTtc,
            'statut_paiement' => $validated['statut'],
        ]);

        return redirect()
            ->route('factures.index')
            ->with('success', 'Facture créée avec succès.');
    }

    private function generateInvoiceNumber(): string
    {
        return 'FAC-' . now()->format('YmdHis');
    }
}
