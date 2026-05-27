<div>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <div class="relative flex-1">
            <x-icon name="magnifying-glass" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Motif, description…"
                class="input-field pl-9 w-full" />
        </div>
        <select wire:model.live="filterStatut" class="input-field w-auto">
            <option value="">Tous statuts</option>
            <option value="ouverte">Ouverte</option>
            <option value="en_cours">En cours</option>
            <option value="resolue">Résolue</option>
        </select>
        <select wire:model.live="perPage" class="input-field w-auto">
            <option value="10">10 / page</option>
            <option value="25">25 / page</option>
        </select>
    </div>

    <x-table>
        <x-slot:head>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Locataire</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Motif</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Actions</th>
        </x-slot:head>

        @forelse($reclamations as $r)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $r->locataire?->full_name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->motif }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $r->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    <x-badge :type="$r->statut" :label="str_replace('_', ' ', ucfirst($r->statut))" />
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('reclamations.show', $r) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <x-icon name="eye" class="w-4 h-4" />
                        </a>
                        <a href="{{ route('reclamations.edit', $r) }}" class="text-gray-400 hover:text-amber-600 transition-colors">
                            <x-icon name="pencil" class="w-4 h-4" />
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-10 text-center text-gray-400">Aucune réclamation trouvée.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-4">{{ $reclamations->links() }}</div>
</div>
