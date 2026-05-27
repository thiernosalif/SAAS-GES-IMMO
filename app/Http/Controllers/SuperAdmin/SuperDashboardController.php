<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Contrat;
use App\Models\Locataire;
use App\Models\Paiement;
use App\Models\User;

class SuperDashboardController extends Controller
{
    public function index()
    {
        $nbAgences        = Agency::count();
        $nbAgencesActives = Agency::where('is_active', true)->count();
        $nbUsers          = User::whereNotNull('agency_id')->count();
        $nbContratsActifs = Contrat::withoutGlobalScopes()->where('statut', 'actif')->count();
        $nbLocataires     = Locataire::withoutGlobalScopes()->count();

        $caTotal = Paiement::withoutGlobalScopes()->sum('montant');
        $caMois  = Paiement::withoutGlobalScopes()
            ->whereBetween('periode', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('montant');

        // Stats par agence avec CA
        $agences = Agency::withCount(['users', 'biens', 'contrats', 'locataires'])
            ->orderByDesc('created_at')
            ->get()
            ->each(function ($agence) {
                $agence->ca_total = Paiement::withoutGlobalScopes()
                    ->where('agency_id', $agence->id)
                    ->sum('montant');
                $agence->ca_mois = Paiement::withoutGlobalScopes()
                    ->where('agency_id', $agence->id)
                    ->whereBetween('periode', [now()->startOfMonth(), now()->endOfMonth()])
                    ->sum('montant');
            });

        $derniersUsers = User::whereNotNull('agency_id')
            ->with('agency')
            ->orderByDesc('created_at')
            ->limit(8)
            ->get();

        return view('superadmin.dashboard', compact(
            'nbAgences', 'nbAgencesActives',
            'nbUsers', 'nbContratsActifs', 'nbLocataires',
            'caTotal', 'caMois',
            'agences', 'derniersUsers'
        ));
    }
}
