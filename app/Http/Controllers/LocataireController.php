<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use Illuminate\Http\Request;

class LocataireController extends Controller
{
    public function index()
    {
        return view('locataires.index');
    }

    public function create()
    {
        return view('locataires.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cin'          => 'nullable|string|max:30',
            'nom'          => 'required|string|max:100',
            'prenom'       => 'required|string|max:100',
            'adresse'      => 'nullable|string|max:255',
            'telephone'    => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:100',
            'coordonne_pro'=> 'nullable|string|max:255',
            'mobileref'    => 'nullable|string|max:30',
        ]);

        $data['agency_id'] = app('current_agency')->id;
        Locataire::create($data);

        return redirect()->route('locataires.index')
            ->with('success', 'Locataire créé avec succès.');
    }

    public function show(Locataire $locataire)
    {
        $locataire->load(['contrats.bien', 'paiements' => fn($q) => $q->latest()->limit(10)]);
        return view('locataires.show', compact('locataire'));
    }

    public function edit(Locataire $locataire)
    {
        return view('locataires.edit', compact('locataire'));
    }

    public function update(Request $request, Locataire $locataire)
    {
        $data = $request->validate([
            'cin'          => 'nullable|string|max:30',
            'nom'          => 'required|string|max:100',
            'prenom'       => 'required|string|max:100',
            'adresse'      => 'nullable|string|max:255',
            'telephone'    => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:100',
            'coordonne_pro'=> 'nullable|string|max:255',
            'mobileref'    => 'nullable|string|max:30',
        ]);

        $locataire->update($data);

        return redirect()->route('locataires.show', $locataire)
            ->with('success', 'Locataire mis à jour.');
    }

    public function destroy(Locataire $locataire)
    {
        $locataire->delete();
        return redirect()->route('locataires.index')
            ->with('success', 'Locataire supprimé.');
    }
}
