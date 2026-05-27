<?php

namespace App\Http\Controllers;

use App\Models\Locataire;
use App\Models\Reclamation;
use Illuminate\Http\Request;

class ReclamationController extends Controller
{
    public function index()
    {
        return view('reclamations.index');
    }

    public function create()
    {
        $locataires = Locataire::orderBy('nom')->get();
        return view('reclamations.create', compact('locataires'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'locataire_id' => 'required|exists:locataires,id',
            'motif'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'statut'       => 'required|in:ouverte,en_cours,resolue',
        ]);

        $data['agency_id'] = app('current_agency')->id;
        Reclamation::create($data);

        return redirect()->route('reclamations.index')
            ->with('success', 'Réclamation enregistrée.');
    }

    public function show(Reclamation $reclamation)
    {
        $reclamation->load(['locataire', 'traitePar']);
        return view('reclamations.show', compact('reclamation'));
    }

    public function edit(Reclamation $reclamation)
    {
        $locataires = Locataire::orderBy('nom')->get();
        return view('reclamations.edit', compact('reclamation', 'locataires'));
    }

    public function update(Request $request, Reclamation $reclamation)
    {
        $data = $request->validate([
            'locataire_id' => 'required|exists:locataires,id',
            'motif'        => 'required|string|max:255',
            'description'  => 'nullable|string',
            'statut'       => 'required|in:ouverte,en_cours,resolue',
        ]);

        if ($data['statut'] !== 'ouverte' && !$reclamation->traite_par) {
            $data['traite_par'] = auth()->id();
        }

        $reclamation->update($data);

        return redirect()->route('reclamations.show', $reclamation)
            ->with('success', 'Réclamation mise à jour.');
    }
}
