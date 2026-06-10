<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::latest()->get();

        return view('clients.index', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenom' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', Rule::unique('clients', 'email')],
            'telephone' => ['required', 'string', 'max:20'],
            'ice' => ['nullable', 'string', 'max:20', Rule::unique('clients', 'ice')],
            'adresse' => ['nullable', 'string', 'max:500'],
            'ville' => ['nullable', 'string', 'max:100'],
        ], [
            'nom.required' => 'Le nom du client est obligatoire.',
            'telephone.required' => 'Le téléphone est obligatoire.',
            'email.unique' => 'Cette adresse email existe déjà.',
            'ice.unique' => 'Cet identifiant ICE existe déjà.',
        ]);

        Client::create($validated);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Client ajouté avec succès.');
    }
}
