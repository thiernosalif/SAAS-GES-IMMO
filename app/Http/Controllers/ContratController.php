<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Contrat;
use App\Models\Locataire;
use Illuminate\Http\Request;

class ContratController extends Controller
{
    public function index()
    {
        return view('contrats.index');
    }

    public function create()
    {
        $biens      = Bien::with('proprietaire')->orderBy('description')->get();
        $locataires = Locataire::orderBy('nom')->get();
        return view('contrats.create', compact('biens', 'locataires'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'bien_id'            => 'required|exists:biens,id',
            'locataire_id'       => 'required|exists:locataires,id',
            'type_logement'      => 'nullable|string|max:100',
            'loyer_mensuel'      => 'required|numeric|min:0',
            'charges_mensuelles' => 'nullable|numeric|min:0',
            'avance_loyer'       => 'nullable|numeric|min:0',
            'caution'            => 'nullable|numeric|min:0',
            'date_debut'         => 'required|date',
            'date_fin'           => 'nullable|date|after_or_equal:date_debut',
            'statut'             => 'required|in:actif,resilie,expire',
            'disponibilite'      => 'boolean',
        ]);

        $data['agency_id']          = app('current_agency')->id;
        $data['charges_mensuelles'] = $data['charges_mensuelles'] ?? 0;
        $data['disponibilite']      = $request->boolean('disponibilite');

        Contrat::create($data);

        return redirect()->route('contrats.index')
            ->with('success', 'Contrat créé avec succès.');
    }

    public function show(Contrat $contrat)
    {
        $contrat->load(['bien.proprietaire', 'locataire', 'paiements' => fn($q) => $q->latest()]);
        return view('contrats.show', compact('contrat'));
    }

    public function edit(Contrat $contrat)
    {
        $biens      = Bien::with('proprietaire')->orderBy('description')->get();
        $locataires = Locataire::orderBy('nom')->get();
        return view('contrats.edit', compact('contrat', 'biens', 'locataires'));
    }

    public function update(Request $request, Contrat $contrat)
    {
        $data = $request->validate([
            'bien_id'            => 'required|exists:biens,id',
            'locataire_id'       => 'required|exists:locataires,id',
            'type_logement'      => 'nullable|string|max:100',
            'loyer_mensuel'      => 'required|numeric|min:0',
            'charges_mensuelles' => 'nullable|numeric|min:0',
            'avance_loyer'       => 'nullable|numeric|min:0',
            'caution'            => 'nullable|numeric|min:0',
            'date_debut'         => 'required|date',
            'date_fin'           => 'nullable|date|after_or_equal:date_debut',
            'statut'             => 'required|in:actif,resilie,expire',
            'disponibilite'      => 'boolean',
        ]);

        $data['disponibilite'] = $request->boolean('disponibilite');
        $contrat->update($data);

        return redirect()->route('contrats.show', $contrat)
            ->with('success', 'Contrat mis à jour.');
    }

    public function resilier(Contrat $contrat)
    {
        $contrat->update(['statut' => 'resilie']);

        return redirect()->route('contrats.show', $contrat)
            ->with('success', 'Contrat résilié.');
    }
}
