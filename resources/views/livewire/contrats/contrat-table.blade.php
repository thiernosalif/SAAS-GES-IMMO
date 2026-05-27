<div>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <div class="relative flex-1">
            <x-icon name="magnifying-glass" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Rechercher par nom du locataire…"
                class="input-field pl-9 w-full" />
        </div>
        <select wire:model.live="filterStatut" class="input-field w-auto">
            <option value="">Tous statuts</option>
            <option value="actif">Actif</option>
            <option value="resilie">Résilié</option>
            <option value="expire">Expiré</option>
        </select>
        <select wire:model.live="perPage" class="input-field w-auto">
            <option value="10">10 / page</option>
            <option value="25">25 / page</option>
            <option value="50">50 / page</option>
        </select>
    </div>

    <x-table>
        <x-slot:head>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Locataire</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Bien</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Loyer mensuel</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Début</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Fin</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Actions</th>
        </x-slot:head>

        @forelse($contrats as $contrat)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $contrat->locataire?->full_name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $contrat->bien?->description ?? '—' }}</td>
                <td class="px-4 py-3 font-medium text-gray-900">{{ format_fcfa($contrat->loyer_mensuel) }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $contrat->date_debut?->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $contrat->date_fin?->format('d/m/Y') ?? '—' }}</td>
                <td class="px-4 py-3">
                    <x-badge :type="$contrat->statut" :label="ucfirst($contrat->statut)" />
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('contrats.show', $contrat) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <x-icon name="eye" class="w-4 h-4" />
                        </a>
                        @if($contrat->statut === 'actif')
                            <a href="{{ route('contrats.edit', $contrat) }}" class="text-gray-400 hover:text-amber-600 transition-colors">
                                <x-icon name="pencil" class="w-4 h-4" />
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-4 py-10 text-center text-gray-400">Aucun contrat trouvé.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-4">{{ $contrats->links() }}</div>
</div>
