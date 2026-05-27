<x-layouts.app title="Dashboard">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Tableau de bord</h1>
        <p class="text-sm text-gray-500 mt-1">
            @if(app()->bound('current_agency') && $agency)
                {{ $agency->name }}
                @if(auth()->user()->zone)
                    — {{ auth()->user()->zone->nom }}
                @endif
            @endif
        </p>
    </div>

    {{-- KPI cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <x-stat-card
            label="Loyers encaissés (mois)"
            :value="format_fcfa($loyersEncaissesMois)"
            icon="banknotes"
            color="blue"
        />
        <x-stat-card
            label="Taux de recouvrement"
            :value="$tauxRecouvrement . '%'"
            icon="chart-bar"
            color="green"
        />
        <x-stat-card
            label="Loyers en retard"
            :value="(string) $nbRetards"
            icon="exclamation-triangle"
            color="red"
        />
        <x-stat-card
            label="CA cumulé (année)"
            :value="format_fcfa($caCumuleAnnee)"
            icon="calculator"
            color="purple"
        />
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Derniers paiements --}}
        <x-card title="Derniers paiements">
            @if($derniersPaiements->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Aucun paiement récent.</p>
            @else
                <div class="overflow-x-auto -mx-6 -mb-6">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Locataire</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Bien</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Montant</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Statut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($derniersPaiements as $p)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 font-medium text-gray-900">{{ $p->contrat?->locataire?->full_name ?? '—' }}</td>
                                    <td class="px-4 py-2 text-gray-500 truncate max-w-[140px]">{{ $p->contrat?->bien?->description ?? '—' }}</td>
                                    <td class="px-4 py-2 text-right font-medium text-gray-900">{{ format_fcfa($p->montant) }}</td>
                                    <td class="px-4 py-2"><x-badge :type="$p->statut" :label="$p->statut_libelle" /></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>

        {{-- Loyers en retard --}}
        <x-card title="Loyers en retard ce mois">
            @if($contratsEnRetard->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Aucun retard ce mois.</p>
            @else
                <div class="overflow-x-auto -mx-6 -mb-6">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50 border-b border-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Locataire</th>
                                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500">Bien</th>
                                <th class="px-4 py-2 text-right text-xs font-medium text-gray-500">Loyer mensuel</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($contratsEnRetard as $c)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 font-medium text-gray-900">{{ $c->locataire?->full_name ?? '—' }}</td>
                                    <td class="px-4 py-2 text-gray-500 truncate max-w-[140px]">{{ $c->bien?->description ?? '—' }}</td>
                                    <td class="px-4 py-2 text-right text-red-600 font-medium">{{ format_fcfa($c->loyer_mensuel) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-card>

    </div>

</x-layouts.app>
