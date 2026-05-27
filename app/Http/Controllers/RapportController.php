<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use Illuminate\Http\Request;

class RapportController extends Controller
{
    public function ca(Request $request)
    {
        $annee = (int) $request->get('annee', now()->year);

        $moisData = [];
        for ($m = 1; $m <= 12; $m++) {
            $debut = \Carbon\Carbon::create($annee, $m, 1)->startOfMonth();
            $fin   = \Carbon\Carbon::create($annee, $m, 1)->endOfMonth();
            $total = Paiement::whereBetween('periode', [$debut, $fin])->sum('montant');
            $moisData[$m] = [
                'label'  => mois_francais($m),
                'total'  => (float) $total,
            ];
        }

        $annees = range(now()->year - 3, now()->year + 1);

        return view('rapports.ca', compact('moisData', 'annee', 'annees'));
    }
}
