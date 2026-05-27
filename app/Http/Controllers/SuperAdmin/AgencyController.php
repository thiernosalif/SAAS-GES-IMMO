<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Contrat;
use App\Models\Paiement;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AgencyController extends Controller
{
    public function index()
    {
        $agencies = Agency::withCount(['users', 'biens', 'contrats', 'locataires'])
            ->orderByDesc('created_at')
            ->get()
            ->each(function ($a) {
                $a->ca_total = Paiement::withoutGlobalScopes()
                    ->where('agency_id', $a->id)->sum('montant');
            });

        return view('superadmin.agencies.index', compact('agencies'));
    }

    public function create()
    {
        return view('superadmin.agencies.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:150',
            'email'              => 'nullable|email|max:150',
            'telephone'          => 'nullable|string|max:30',
            'adresse'            => 'nullable|string|max:255',
            'ville'              => 'nullable|string|max:100',
            'pays'               => 'nullable|string|size:2',
            'locale_defaut'      => 'nullable|in:fr,en,pt-PT',
            'plan'               => 'nullable|string|max:50',
            'taux_commission'    => 'nullable|numeric|min:0|max:100',
            'is_active'          => 'boolean',
            'expires_at'         => 'nullable|date',
            'couleur_principale' => 'nullable|string|max:7',
            'logo'               => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $data['slug']            = Str::slug($data['name'] . '-' . now()->timestamp);
        $data['is_active']       = $request->boolean('is_active', true);
        $data['taux_commission'] = $data['taux_commission'] ?? 10;

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $agency = Agency::create($data);

        // Zone par défaut
        Zone::create([
            'agency_id' => $agency->id,
            'nom'       => 'Siège',
            'ville'     => $data['ville'] ?? '',
            'is_active' => true,
        ]);

        return redirect()->route('superadmin.agencies.show', $agency)
            ->with('success', 'Agence créée. Une zone "Siège" a été créée automatiquement.');
    }

    public function show(Agency $agency)
    {
        $agency->loadCount(['users', 'biens', 'contrats', 'locataires']);
        $zones          = $agency->zones()->withCount('users')->get();
        $caTotal        = Paiement::withoutGlobalScopes()->where('agency_id', $agency->id)->sum('montant');
        $caMois         = Paiement::withoutGlobalScopes()
            ->where('agency_id', $agency->id)
            ->whereBetween('periode', [now()->startOfMonth(), now()->endOfMonth()])
            ->sum('montant');
        $contratsActifs = Contrat::withoutGlobalScopes()
            ->where('agency_id', $agency->id)->where('statut', 'actif')->count();
        $users          = $agency->users()->with('zone')->orderBy('nom')->get();

        return view('superadmin.agencies.show', compact(
            'agency', 'zones', 'caTotal', 'caMois', 'contratsActifs', 'users'
        ));
    }

    public function edit(Agency $agency)
    {
        return view('superadmin.agencies.edit', compact('agency'));
    }

    public function update(Request $request, Agency $agency)
    {
        $data = $request->validate([
            'name'               => 'required|string|max:150',
            'email'              => 'nullable|email|max:150',
            'telephone'          => 'nullable|string|max:30',
            'adresse'            => 'nullable|string|max:255',
            'ville'              => 'nullable|string|max:100',
            'pays'               => 'nullable|string|size:2',
            'locale_defaut'      => 'nullable|in:fr,en,pt-PT',
            'plan'               => 'nullable|string|max:50',
            'taux_commission'    => 'nullable|numeric|min:0|max:100',
            'is_active'          => 'boolean',
            'expires_at'         => 'nullable|date',
            'couleur_principale' => 'nullable|string|max:7',
            'logo'               => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('logo')) {
            // Supprimer l'ancien logo
            if ($agency->logo) {
                Storage::disk('public')->delete($agency->logo);
            }
            $data['logo'] = $request->file('logo')->store('logos', 'public');
        }

        $agency->update($data);

        return redirect()->route('superadmin.agencies.show', $agency)
            ->with('success', 'Agence mise à jour.');
    }

    public function destroy(Agency $agency)
    {
        $agency->update(['is_active' => false]);

        return redirect()->route('superadmin.agencies.index')
            ->with('success', 'Agence désactivée.');
    }
}
