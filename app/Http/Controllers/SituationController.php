<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Proprietaire;
use App\Models\Situation;
use App\Models\SituationLigne;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SituationController extends Controller
{
    public function index()
    {
        return view('situations.index');
    }

    public function create()
    {
        $proprietaires = Proprietaire::orderBy('nom')->get();
        return view('situations.create', compact('proprietaires'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'proprietaire_id' => 'required|exists:proprietaires,id',
            'periode_debut'   => 'required|date',
            'periode_fin'     => 'required|date|after_or_equal:periode_debut',
            'type'            => 'required|in:mensuelle,annuelle',
        ]);

        $agency         = app('current_agency');
        $proprietaireId = (int) $data['proprietaire_id'];
        $debut          = Carbon::parse($data['periode_debut'])->startOfDay();
        $fin            = Carbon::parse($data['periode_fin'])->endOfDay();

        // Récupérer les contrats actifs du propriétaire pendant la période
        $contrats = Contrat::where('statut', 'actif')
            ->whereHas('bien', fn($q) => $q->where('proprietaire_id', $proprietaireId))
            ->with(['bien', 'locataire'])
            ->get();

        $totalLoyersPercus = 0;
        $totalCharges      = 0;
        $lignes            = [];

        foreach ($contrats as $contrat) {
            $montantPercu = $contrat->paiements()
                ->whereBetween('periode', [$debut, $fin])
                ->sum('montant');

            $loyerDu = (float) $contrat->loyer_mensuel;
            $ecart   = (float) $montantPercu - $loyerDu;

            $proprietaire = Proprietaire::find($proprietaireId);
            $taux         = $proprietaire ? $proprietaire->taux_commission_effectif : 10.0;
            $commission   = round($montantPercu * $taux / 100, 2);

            $totalLoyersPercus += $montantPercu;
            $totalCharges      += (float) $contrat->charges_mensuelles;

            $lignes[] = [
                'contrat_id'      => $contrat->id,
                'locataire_nom'   => $contrat->locataire?->full_name ?? '—',
                'bien_description'=> $contrat->bien?->description ?? '—',
                'periode'         => $debut->format('Y-m-d'),
                'loyer_du'        => $loyerDu,
                'montant_percu'   => $montantPercu,
                'ecart'           => $ecart,
                'commission'      => $commission,
            ];
        }

        $commissionTotale = collect($lignes)->sum('commission');
        $netProprietaire  = $totalLoyersPercus - $commissionTotale;

        $situation = Situation::create([
            'agency_id'          => $agency->id,
            'proprietaire_id'    => $proprietaireId,
            'periode_debut'      => $debut->format('Y-m-d'),
            'periode_fin'        => $fin->format('Y-m-d'),
            'type'               => $data['type'],
            'total_loyers_percus'=> $totalLoyersPercus,
            'total_charges'      => $totalCharges,
            'commission_agence'  => $commissionTotale,
            'net_proprietaire'   => $netProprietaire,
            'statut'             => 'brouillon',
            'genere_par'         => auth()->id(),
        ]);

        foreach ($lignes as $ligne) {
            SituationLigne::create(array_merge($ligne, ['situation_id' => $situation->id]));
        }

        return redirect()->route('situations.show', $situation)
            ->with('success', 'Situation générée avec succès.');
    }

    public function show(Situation $situation)
    {
        $situation->load(['proprietaire', 'lignes', 'generePar']);
        return view('situations.show', compact('situation'));
    }

    public function pdf(Situation $situation)
    {
        $situation->load(['proprietaire', 'lignes', 'generePar']);
        $agency = app('current_agency');

        $pdf = Pdf::loadView('pdf.situation', compact('situation', 'agency'));

        return $pdf->download('situation-' . $situation->id . '.pdf');
    }
}
