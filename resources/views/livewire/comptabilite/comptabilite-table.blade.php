<div>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <div class="relative flex-1">
            <x-icon name="magnifying-glass" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Motif, catégorie…"
                class="input-field pl-9 w-full" />
        </div>
        <select wire:model.live="filterType" class="input-field w-auto">
            <option value="">Tous</option>
            <option value="entree">Entrées</option>
            <option value="sortie">Sorties</option>
        </select>
        <select wire:model.live="perPage" class="input-field w-auto">
            <option value="10">10 / page</option>
            <option value="25">25 / page</option>
        </select>
    </div>

    <x-table>
        <x-slot:head>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Type</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Motif</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Catégorie</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Montant</th>
        </x-slot:head>

        @forelse($mouvements as $m)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 text-gray-600">{{ $m->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3">
                    @if($m->type === 'entree')
                        <span class="inline-flex items-center gap-1 text-green-700 font-medium text-sm">
                            <x-icon name="arrow-up" class="w-4 h-4" /> Entrée
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 text-red-700 font-medium text-sm">
                            <x-icon name="arrow-down" class="w-4 h-4" /> Sortie
                        </span>
                    @endif
                </td>
                <td class="px-4 py-3 font-medium text-gray-900">{{ $m->motif }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $m->categorie ?? '—' }}</td>
                <td class="px-4 py-3 text-right font-semibold {{ $m->type === 'entree' ? 'text-green-700' : 'text-red-700' }}">
                    {{ $m->type === 'entree' ? '+' : '-' }}{{ format_fcfa($m->montant) }}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="px-4 py-10 text-center text-gray-400">Aucun mouvement.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-4">{{ $mouvements->links() }}</div>
</div>
