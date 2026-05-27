<div>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <div class="relative flex-1">
            <x-icon name="magnifying-glass" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Rechercher par description, adresse, quartier…"
                class="input-field pl-9 w-full" />
        </div>
        <select wire:model.live="filterType" class="input-field w-auto">
            <option value="">Tous types</option>
            <option value="appartement">Appartement</option>
            <option value="chambre">Chambre</option>
            <option value="studio">Studio</option>
            <option value="villa">Villa</option>
            <option value="bureau">Bureau</option>
            <option value="magasin">Magasin</option>
            <option value="autre">Autre</option>
        </select>
        <select wire:model.live="filterDispo" class="input-field w-auto">
            <option value="">Toute dispo</option>
            <option value="libre">Libre</option>
            <option value="occupe">Occupé</option>
        </select>
        <select wire:model.live="perPage" class="input-field w-auto">
            <option value="10">10 / page</option>
            <option value="25">25 / page</option>
            <option value="50">50 / page</option>
        </select>
    </div>

    <x-table>
        <x-slot:head>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Description</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Type</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Propriétaire</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Quartier / Ville</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Disponibilité</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Actions</th>
        </x-slot:head>

        @forelse($biens as $bien)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $bien->description }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $bien->type_libelle }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $bien->proprietaire?->full_name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $bien->quartier ?? $bien->ville ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($bien->contratActif)
                        <x-badge type="actif" label="Occupé" />
                    @else
                        <x-badge type="inactif" label="Libre" />
                    @endif
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('biens.show', $bien) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <x-icon name="eye" class="w-4 h-4" />
                        </a>
                        <a href="{{ route('biens.edit', $bien) }}" class="text-gray-400 hover:text-amber-600 transition-colors">
                            <x-icon name="pencil" class="w-4 h-4" />
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400">Aucun bien trouvé.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-4">
        {{ $biens->links() }}
    </div>
</div>
