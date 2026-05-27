<?php

namespace App\Http\Controllers;

use App\Models\Bien;
use App\Models\Proprietaire;
use App\Models\Zone;
use Illuminate\Http\Request;

class BienController extends Controller
{
    public function index()
    {
        return view('biens.index');
    }

    public function create()
    {
        $proprietaires = Proprietaire::orderBy('nom')->get();
        $zones         = Zone::orderBy('nom')->get();
        return view('biens.create', compact('proprietaires', 'zones'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proprietaire_id' => 'required|exists:proprietaires,id',
            'zone_id'         => 'nullable|exists:zones,id',
            'description'     => 'required|string|max:255',
            'adresse'         => 'nullable|string|max:255',
            'ville'           => 'nullable|string|max:100',
            'quartier'        => 'nullable|string|max:100',
            'type'            => 'required|in:appartement,chambre,studio,villa,bureau,magasin,autre',
            'nombre_unites'   => 'nullable|integer|min:1',
        ]);

        $data['agency_id'] = app('current_agency')->id;
        Bien::create($data);

        return redirect()->route('biens.index')
            ->with('success', 'Bien créé avec succès.');
    }

    public function show(Bien $bien)
    {
        $bien->load(['proprietaire', 'contrats.locataire', 'zone']);
        return view('biens.show', compact('bien'));
    }

    public function edit(Bien $bien)
    {
        $proprietaires = Proprietaire::orderBy('nom')->get();
        $zones         = Zone::orderBy('nom')->get();
        return view('biens.edit', compact('bien', 'proprietaires', 'zones'));
    }

    public function update(Request $request, Bien $bien)
    {
        $data = $request->validate([
            'proprietaire_id' => 'required|exists:proprietaires,id',
            'zone_id'         => 'nullable|exists:zones,id',
            'description'     => 'required|string|max:255',
            'adresse'         => 'nullable|string|max:255',
            'ville'           => 'nullable|string|max:100',
            'quartier'        => 'nullable|string|max:100',
            'type'            => 'required|in:appartement,chambre,studio,villa,bureau,magasin,autre',
            'nombre_unites'   => 'nullable|integer|min:1',
        ]);

        $bien->update($data);

        return redirect()->route('biens.show', $bien)
            ->with('success', 'Bien mis à jour.');
    }

    public function destroy(Bien $bien)
    {
        $bien->delete();
        return redirect()->route('biens.index')
            ->with('success', 'Bien supprimé.');
    }
}
