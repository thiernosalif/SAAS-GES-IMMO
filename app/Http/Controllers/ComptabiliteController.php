<?php

namespace App\Http\Controllers;

use App\Models\Comptabilite;
use Illuminate\Http\Request;

class ComptabiliteController extends Controller
{
    public function index()
    {
        $agency    = app('current_agency');
        $debutMois = now()->startOfMonth();
        $finMois   = now()->endOfMonth();

        $totalEntrees = Comptabilite::where('type', 'entree')
            ->whereBetween('created_at', [$debutMois, $finMois])
            ->sum('montant');

        $totalSorties = Comptabilite::where('type', 'sortie')
            ->whereBetween('created_at', [$debutMois, $finMois])
            ->sum('montant');

        $solde = Comptabilite::where('type', 'entree')->sum('montant')
            - Comptabilite::where('type', 'sortie')->sum('montant');

        return view('comptabilite.index', compact('totalEntrees', 'totalSorties', 'solde'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'type'     => 'required|in:entree,sortie',
            'montant'  => 'required|numeric|min:0',
            'motif'    => 'required|string|max:255',
            'categorie'=> 'nullable|string|max:100',
        ]);

        $data['agency_id'] = app('current_agency')->id;
        $data['saisi_par'] = auth()->id();

        Comptabilite::create($data);

        return redirect()->route('comptabilite.index')
            ->with('success', 'Mouvement enregistré.');
    }
}
