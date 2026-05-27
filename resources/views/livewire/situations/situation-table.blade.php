<div>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <div class="relative flex-1">
            <x-icon name="magnifying-glass" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Nom du propriétaire…"
                class="input-field pl-9 w-full" />
        </div>
        <select wire:model.live="filterType" class="input-field w-auto">
            <option value="">Tous types</option>
            <option value="mensuelle">Mensuelle</option>
            <option value="annuelle">Annuelle</option>
        </select>
        <select wire:model.live="filterStatut" class="input-field w-auto">
            <option value="">Tous statuts</option>
            <option value="brouillon">Brouillon</option>
            <option value="validee">Validée</option>
            <option value="envoyee">Envoyée</option>
        </select>
        <select wire:model.live="perPage" class="input-field w-auto">
            <option value="10">10 / page</option>
            <option value="25">25 / page</option>
        </select>
    </div>

    <x-table>
        <x-slot:head>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Propriétaire</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Période</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Type</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Net propriétaire</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Actions</th>
        </x-slot:head>

        @forelse($situations as $s)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $s->proprietaire?->full_name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">
                    {{ $s->periode_debut?->format('d/m/Y') }} → {{ $s->periode_fin?->format('d/m/Y') }}
                </td>
                <td class="px-4 py-3 text-gray-600">{{ ucfirst($s->type) }}</td>
                <td class="px-4 py-3 text-right font-medium text-gray-900">{{ format_fcfa($s->net_proprietaire) }}</td>
                <td class="px-4 py-3"><x-badge :type="$s->statut" :label="ucfirst($s->statut)" /></td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('situations.show', $s) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <x-icon name="eye" class="w-4 h-4" />
                        </a>
                        <a href="{{ route('situations.pdf', $s) }}" class="text-gray-400 hover:text-green-600 transition-colors" title="PDF">
                            <x-icon name="document-arrow-down" class="w-4 h-4" />
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400">Aucune situation trouvée.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-4">{{ $situations->links() }}</div>
</div>
