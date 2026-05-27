<x-layouts.app :title="'Situation — ' . $situation->proprietaire?->full_name">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('situations.index') }}" class="text-gray-400 hover:text-gray-700">
                <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Situation #{{ $situation->id }}</h1>
            <x-badge :type="$situation->statut" :label="ucfirst($situation->statut)" />
        </div>
        <a href="{{ route('situations.pdf', $situation) }}"
           class="btn-primary inline-flex items-center gap-1">
            <x-icon name="document-arrow-down" class="w-4 h-4" /> Télécharger PDF
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    {{-- Résumé --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <x-stat-card
            label="Total loyers perçus"
            :value="format_fcfa($situation->total_loyers_percus)"
            icon="banknotes"
            color="blue" />
        <x-stat-card
            label="Commission agence"
            :value="format_fcfa($situation->commission_agence)"
            icon="calculator"
            color="orange" />
        <x-stat-card
            label="Net à reverser"
            :value="format_fcfa($situation->net_proprietaire)"
            icon="chart-bar"
            color="green" />
        <x-stat-card
            label="Nb biens"
            :value="(string) $situation->lignes->count()"
            icon="building"
            color="purple" />
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <x-card title="Informations" class="xl:col-span-1">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Propriétaire</dt>
                    <dd class="font-semibold text-gray-900">{{ $situation->proprietaire?->full_name ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Période</dt>
                    <dd class="font-medium text-gray-900">
                        {{ $situation->periode_debut?->format('d/m/Y') }} → {{ $situation->periode_fin?->format('d/m/Y') }}
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Type</dt>
                    <dd class="font-medium text-gray-900">{{ ucfirst($situation->type) }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Générée par</dt>
                    <dd class="text-gray-700">{{ $situation->generePar?->prenom }} {{ $situation->generePar?->nom }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Date génération</dt>
                    <dd class="text-gray-700">{{ $situation->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </x-card>

        <x-card title="Détail par bien" class="xl:col-span-2">
            @if($situation->lignes->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Aucune ligne de détail.</p>
            @else
                <x-table>
                    <x-slot:head>
                        <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase">Locataire</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase">Bien</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase text-right">Loyer dû</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase text-right">Perçu</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase text-right">Écart</th>
                        <th class="px-3 py-3 text-xs font-medium text-gray-500 uppercase text-right">Commission</th>
                    </x-slot:head>
                    @foreach($situation->lignes as $ligne)
                        <tr class="hover:bg-gray-50">
                            <td class="px-3 py-2.5 text-sm text-gray-900">{{ $ligne->locataire_nom }}</td>
                            <td class="px-3 py-2.5 text-sm text-gray-600 max-w-[140px] truncate">{{ $ligne->bien_description }}</td>
                            <td class="px-3 py-2.5 text-sm text-right text-gray-700">{{ format_fcfa($ligne->loyer_du) }}</td>
                            <td class="px-3 py-2.5 text-sm text-right font-medium text-gray-900">{{ format_fcfa($ligne->montant_percu) }}</td>
                            <td class="px-3 py-2.5 text-sm text-right {{ $ligne->ecart >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $ligne->ecart >= 0 ? '+' : '' }}{{ format_fcfa($ligne->ecart) }}
                            </td>
                            <td class="px-3 py-2.5 text-sm text-right text-gray-600">{{ format_fcfa($ligne->commission) }}</td>
                        </tr>
                    @endforeach
                    <tr class="bg-gray-50 font-semibold border-t-2 border-gray-200">
                        <td colspan="3" class="px-3 py-3 text-sm text-gray-700">TOTAL</td>
                        <td class="px-3 py-3 text-sm text-right text-gray-900">{{ format_fcfa($situation->total_loyers_percus) }}</td>
                        <td class="px-3 py-3"></td>
                        <td class="px-3 py-3 text-sm text-right text-gray-700">{{ format_fcfa($situation->commission_agence) }}</td>
                    </tr>
                    <tr class="bg-green-50 font-bold">
                        <td colspan="5" class="px-3 py-3 text-sm text-green-800">Net à reverser au propriétaire</td>
                        <td class="px-3 py-3 text-base text-right text-green-800">{{ format_fcfa($situation->net_proprietaire) }}</td>
                    </tr>
                </x-table>
            @endif
        </x-card>
    </div>

</x-layouts.app>
