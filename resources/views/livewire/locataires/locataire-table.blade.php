<div>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <div class="relative flex-1">
            <x-icon name="magnifying-glass" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Rechercher par nom, CIN, téléphone…"
                class="input-field pl-9 w-full" />
        </div>
        <select wire:model.live="perPage" class="input-field w-auto">
            <option value="10">10 / page</option>
            <option value="25">25 / page</option>
            <option value="50">50 / page</option>
        </select>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    <x-table>
        <x-slot:head>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase cursor-pointer" wire:click="sortBy('nom')">
                Nom @if($sortField==='nom') {{ $sortDirection==='asc' ? '↑' : '↓' }} @endif
            </th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">CIN</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Téléphone</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Contrat</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Actions</th>
        </x-slot:head>

        @forelse($locataires as $loc)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $loc->full_name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $loc->cin ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $loc->telephone ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $loc->email ?? '—' }}</td>
                <td class="px-4 py-3">
                    @if($loc->contratActif)
                        <x-badge type="actif" label="Actif" />
                    @else
                        <x-badge type="inactif" label="Sans contrat" />
                    @endif
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('locataires.show', $loc) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <x-icon name="eye" class="w-4 h-4" />
                        </a>
                        <a href="{{ route('locataires.edit', $loc) }}" class="text-gray-400 hover:text-amber-600 transition-colors">
                            <x-icon name="pencil" class="w-4 h-4" />
                        </a>
                        <button wire:click="delete({{ $loc->id }})" wire:confirm="Supprimer ce locataire ?"
                            class="text-gray-400 hover:text-red-600 transition-colors">
                            <x-icon name="trash" class="w-4 h-4" />
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400">Aucun locataire trouvé.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-4">{{ $locataires->links() }}</div>
</div>
