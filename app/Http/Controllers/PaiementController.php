<?php

namespace App\Http\Controllers;

use App\Models\Contrat;
use App\Models\Paiement;
use App\Models\Recu;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaiementController extends Controller
{
    public function index()
    {
        return view('paiements.index');
    }

    public function create()
    {
        $contrats = Contrat::where('statut', 'actif')
            ->with(['locataire', 'bien'])
            ->get();
        return view('paiements.create', compact('contrats'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'contrat_id'            => 'required|exists:contrats,id',
            'periode'               => 'required|date',
            'montant'               => 'required|numeric|min:0',
            'montant_du'            => 'nullable|numeric|min:0',
            'mode_paiement'         => 'required|in:especes,virement,mobile_money,cheque',
            'avance'                => 'nullable|numeric|min:0',
            'acompte'               => 'nullable|numeric|min:0',
            'complement'            => 'nullable|numeric|min:0',
            'transaction_reference' => 'nullable|string|max:100',
            'statut'                => 'required|in:complet,partiel,avance',
            'encaisse_par'          => 'nullable|exists:users,id',
        ]);

        $agency = app('current_agency');
        $data['agency_id'] = $agency->id;

        $contrat              = Contrat::findOrFail($data['contrat_id']);
        $data['locataire_id'] = $contrat->locataire_id;

        if (!isset($data['encaisse_par'])) {
            $data['encaisse_par'] = auth()->id();
        }

        $paiement = Paiement::create($data);

        // Créer le reçu automatiquement
        $recu = Recu::create([
            'agency_id'  => $agency->id,
            'paiement_id'=> $paiement->id,
            'numero'     => 'REC-' . now()->year . '-' . str_pad($paiement->id, 5, '0', STR_PAD_LEFT),
        ]);

        return redirect()->route('paiements.show', $paiement)
            ->with('success', 'Paiement enregistré. Reçu ' . $recu->numero . ' généré.');
    }

    public function show(Paiement $paiement)
    {
        $paiement->load(['contrat.bien', 'contrat.locataire', 'recu']);
        return view('paiements.show', compact('paiement'));
    }

    public function recu(Paiement $paiement)
    {
        $paiement->load(['contrat.bien.proprietaire', 'contrat.locataire', 'recu']);
        $agency = app('current_agency');

        // Auto-create reçu if missing (e.g. legacy payments imported without one)
        $recu = $paiement->recu ?? Recu::create([
            'agency_id'   => $agency->id,
            'paiement_id' => $paiement->id,
            'numero'      => 'REC-' . now()->year . '-' . str_pad($paiement->id, 5, '0', STR_PAD_LEFT),
        ]);

        $logoBase64 = $this->agencyLogoBase64($agency);

        $pdf = Pdf::loadView('pdf.recu', compact('paiement', 'agency', 'recu', 'logoBase64'));

        $filename = 'recu-' . $recu->numero . '.pdf';

        return $pdf->download($filename);
    }

    /** Encode le logo de l'agence en base64 pour DomPDF (pas d'accès HTTP). */
    private function agencyLogoBase64($agency): ?string
    {
        if (!$agency?->logo) {
            return null;
        }

        if (!Storage::disk('public')->exists($agency->logo)) {
            return null;
        }

        $content = Storage::disk('public')->get($agency->logo);
        $mime    = Storage::disk('public')->mimeType($agency->logo);

        return 'data:' . $mime . ';base64,' . base64_encode($content);
    }
}
