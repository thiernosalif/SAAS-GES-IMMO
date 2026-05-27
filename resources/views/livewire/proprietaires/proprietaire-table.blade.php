<div>
    {{-- Barre de recherche + perPage --}}
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <div class="relative flex-1">
            <x-icon name="magnifying-glass" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input
                type="text"
                wire:model.live.debounce.300ms="search"
                placeholder="Rechercher par nom, CIN, téléphone…"
                class="input-field pl-9 w-full"
            />
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
                Nom
                @if($sortField === 'nom') <span>{{ $sortDirection === 'asc' ? '↑' : '↓' }}</span> @endif
            </th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">CIN</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Téléphone</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Biens</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Actions</th>
        </x-slot:head>

        @forelse($proprietaires as $prop)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $prop->full_name }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $prop->cin ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $prop->telephone ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $prop->email ?? '—' }}</td>
                <td class="px-4 py-3 text-center">
                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-blue-100 text-blue-700 text-xs font-bold">
                        {{ $prop->biens_count }}
                    </span>
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('proprietaires.show', $prop) }}" class="text-gray-400 hover:text-blue-600 transition-colors" title="Voir">
                            <x-icon name="eye" class="w-4 h-4" />
                        </a>
                        <a href="{{ route('proprietaires.edit', $prop) }}" class="text-gray-400 hover:text-amber-600 transition-colors" title="Modifier">
                            <x-icon name="pencil" class="w-4 h-4" />
                        </a>
                        <button
                            wire:click="delete({{ $prop->id }})"
                            wire:confirm="Supprimer ce propriétaire ?"
                            class="text-gray-400 hover:text-red-600 transition-colors"
                            title="Supprimer"
                        >
                            <x-icon name="trash" class="w-4 h-4" />
                        </button>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="px-4 py-10 text-center text-gray-400">Aucun propriétaire trouvé.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-4">
        {{ $proprietaires->links() }}
    </div>
</div>
