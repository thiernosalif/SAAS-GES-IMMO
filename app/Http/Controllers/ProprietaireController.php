<?php

namespace App\Http\Controllers;

use App\Models\Proprietaire;
use Illuminate\Http\Request;

class ProprietaireController extends Controller
{
    public function index()
    {
        return view('proprietaires.index');
    }

    public function create()
    {
        return view('proprietaires.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cin'                       => 'nullable|string|max:30',
            'nom'                       => 'required|string|max:100',
            'prenom'                    => 'required|string|max:100',
            'adresse'                   => 'nullable|string|max:255',
            'telephone'                 => 'nullable|string|max:30',
            'email'                     => 'nullable|email|max:100',
            'date_deb_mandat'           => 'nullable|date',
            'date_fin_mandat'           => 'nullable|date|after_or_equal:date_deb_mandat',
            'taux_commission_specifique'=> 'nullable|numeric|min:0|max:100',
        ]);

        $agency = app('current_agency');
        $data['agency_id'] = $agency->id;

        Proprietaire::create($data);

        return redirect()->route('proprietaires.index')
            ->with('success', 'Propriétaire créé avec succès.');
    }

    public function show(Proprietaire $proprietaire)
    {
        $proprietaire->load('biens.contratActif');
        return view('proprietaires.show', compact('proprietaire'));
    }

    public function edit(Proprietaire $proprietaire)
    {
        return view('proprietaires.edit', compact('proprietaire'));
    }

    public function update(Request $request, Proprietaire $proprietaire)
    {
        $data = $request->validate([
            'cin'                       => 'nullable|string|max:30',
            'nom'                       => 'required|string|max:100',
            'prenom'                    => 'required|string|max:100',
            'adresse'                   => 'nullable|string|max:255',
            'telephone'                 => 'nullable|string|max:30',
            'email'                     => 'nullable|email|max:100',
            'date_deb_mandat'           => 'nullable|date',
            'date_fin_mandat'           => 'nullable|date|after_or_equal:date_deb_mandat',
            'taux_commission_specifique'=> 'nullable|numeric|min:0|max:100',
        ]);

        $proprietaire->update($data);

        return redirect()->route('proprietaires.show', $proprietaire)
            ->with('success', 'Propriétaire mis à jour.');
    }

    public function destroy(Proprietaire $proprietaire)
    {
        $proprietaire->delete();
        return redirect()->route('proprietaires.index')
            ->with('success', 'Propriétaire supprimé.');
    }
}
