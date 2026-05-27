<x-layouts.app :title="$proprietaire->full_name">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('proprietaires.index') }}" class="text-gray-400 hover:text-gray-700">
                <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $proprietaire->full_name }}</h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('proprietaires.edit', $proprietaire) }}" class="btn-secondary inline-flex items-center gap-1">
                <x-icon name="pencil" class="w-4 h-4" /> Modifier
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <x-card title="Informations personnelles" class="xl:col-span-1">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">CIN</dt>
                    <dd class="font-medium text-gray-900">{{ $proprietaire->cin ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Téléphone</dt>
                    <dd class="font-medium text-gray-900">{{ $proprietaire->telephone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium text-gray-900">{{ $proprietaire->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Adresse</dt>
                    <dd class="font-medium text-gray-900">{{ $proprietaire->adresse ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Mandat</dt>
                    <dd class="font-medium text-gray-900">
                        @if($proprietaire->date_deb_mandat)
                            {{ $proprietaire->date_deb_mandat->format('d/m/Y') }}
                            @if($proprietaire->date_fin_mandat)
                                → {{ $proprietaire->date_fin_mandat->format('d/m/Y') }}
                            @endif
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Taux commission</dt>
                    <dd class="font-medium text-gray-900">{{ $proprietaire->taux_commission_effectif }} %</dd>
                </div>
            </dl>
        </x-card>

        <x-card title="Biens" class="xl:col-span-2">
            @if($proprietaire->biens->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Aucun bien associé.</p>
            @else
                <x-table>
                    <x-slot:head>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Type</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Quartier</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </x-slot:head>
                    @foreach($proprietaire->biens as $bien)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ $bien->description }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $bien->type_libelle }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $bien->quartier ?? '—' }}</td>
                            <td class="px-4 py-3">
                                @if($bien->contratActif)
                                    <x-badge type="actif" label="Occupé" />
                                @else
                                    <x-badge type="inactif" label="Libre" />
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <a href="{{ route('biens.show', $bien) }}" class="text-blue-600 hover:underline text-sm">Voir</a>
                            </td>
                        </tr>
                    @endforeach
                </x-table>
            @endif
        </x-card>

    </div>

</x-layouts.app>
