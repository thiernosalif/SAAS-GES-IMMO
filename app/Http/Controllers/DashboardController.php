<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Paiement;

class DashboardController extends Controller
{
    public function index()
    {
        // Super admin has no agency — send them to their own dashboard
        if (auth()->user()?->isSuperAdmin()) {
            return redirect()->route('superadmin.dashboard');
        }

        $agency     = app('current_agency');
        $debutMois  = now()->startOfMonth();
        $finMois    = now()->endOfMonth();
        $debutAnnee = now()->startOfYear();

        $loyersEncaissesMois = Paiement::whereBetween('periode', [$debutMois, $finMois])->sum('montant');
        $caCumuleAnnee       = Paiement::whereBetween('periode', [$debutAnnee, now()])->sum('montant');

        $nbContratsActifs = Contrat::where('statut', 'actif')->count();
        $nbPayesMois      = Contrat::where('statut', 'actif')
            ->whereHas('paiements', fn ($q) => $q->whereBetween('periode', [$debutMois, $finMois]))
            ->count();

        $tauxRecouvrement = $nbContratsActifs > 0
            ? round($nbPayesMois / $nbContratsActifs * 100, 1)
            : 0;
        $nbRetards = max(0, $nbContratsActifs - $nbPayesMois);

        $derniersPaiements = Paiement::with(['contrat.locataire', 'contrat.bien'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        $contratsEnRetard = Contrat::where('statut', 'actif')
            ->whereDoesntHave('paiements', fn ($q) => $q->whereBetween('periode', [$debutMois, $finMois]))
            ->with(['locataire', 'bien'])
            ->limit(15)
            ->get();

        return view('dashboard', compact(
            'agency',
            'loyersEncaissesMois',
            'caCumuleAnnee',
            'tauxRecouvrement',
            'nbRetards',
            'derniersPaiements',
            'contratsEnRetard'
        ));
    }
}
