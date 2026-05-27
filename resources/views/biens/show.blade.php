<x-layouts.app :title="$bien->description">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('biens.index') }}" class="text-gray-400 hover:text-gray-700">
                <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $bien->description }}</h1>
        </div>
        <a href="{{ route('biens.edit', $bien) }}" class="btn-secondary inline-flex items-center gap-1">
            <x-icon name="pencil" class="w-4 h-4" /> Modifier
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <x-card title="Informations" class="xl:col-span-1">
            <dl class="space-y-3 text-sm">
                <div><dt class="text-gray-500">Type</dt><dd class="font-medium text-gray-900">{{ $bien->type_libelle }}</dd></div>
                <div><dt class="text-gray-500">Propriétaire</dt>
                    <dd class="font-medium text-gray-900">
                        <a href="{{ route('proprietaires.show', $bien->proprietaire) }}" class="text-blue-600 hover:underline">{{ $bien->proprietaire?->full_name ?? '—' }}</a>
                    </dd>
                </div>
                <div><dt class="text-gray-500">Zone</dt><dd class="font-medium text-gray-900">{{ $bien->zone?->nom ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Adresse</dt><dd class="font-medium text-gray-900">{{ $bien->adresse ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Ville</dt><dd class="font-medium text-gray-900">{{ $bien->ville ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Quartier</dt><dd class="font-medium text-gray-900">{{ $bien->quartier ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Disponibilité</dt>
                    <dd>
                        @if($bien->contratActif)
                            <x-badge type="actif" label="Occupé" />
                        @else
                            <x-badge type="inactif" label="Libre" />
                        @endif
                    </dd>
                </div>
            </dl>
        </x-card>

        <x-card title="Historique des contrats" class="xl:col-span-2">
            @if($bien->contrats->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Aucun contrat pour ce bien.</p>
            @else
                <x-table>
                    <x-slot:head>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Locataire</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Loyer</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Début</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Fin</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Action</th>
                    </x-slot:head>
                    @foreach($bien->contrats as $c)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $c->locataire?->full_name ?? '—' }}</td>
                            <td class="px-4 py-3 text-gray-900">{{ format_fcfa($c->loyer_mensuel) }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $c->date_debut?->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $c->date_fin?->format('d/m/Y') ?? '—' }}</td>
                            <td class="px-4 py-3"><x-badge :type="$c->statut" :label="ucfirst($c->statut)" /></td>
                            <td class="px-4 py-3">
                                <a href="{{ route('contrats.show', $c) }}" class="text-blue-600 hover:underline text-sm">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </x-table>
            @endif
        </x-card>
    </div>
</x-layouts.app>
