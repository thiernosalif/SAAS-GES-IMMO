<div>
    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-4">
        <div class="relative flex-1">
            <x-icon name="magnifying-glass" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" />
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Référence, locataire…"
                class="input-field pl-9 w-full" />
        </div>
        <select wire:model.live="filterMode" class="input-field w-auto">
            <option value="">Tous modes</option>
            <option value="especes">Espèces</option>
            <option value="virement">Virement</option>
            <option value="mobile_money">Mobile Money</option>
            <option value="cheque">Chèque</option>
        </select>
        <select wire:model.live="filterStatut" class="input-field w-auto">
            <option value="">Tous statuts</option>
            <option value="complet">Complet</option>
            <option value="partiel">Partiel</option>
            <option value="avance">Avance</option>
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
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Période</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Montant</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Mode</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Actions</th>
        </x-slot:head>

        @forelse($paiements as $p)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3 font-medium text-gray-900">{{ $p->contrat?->locataire?->full_name ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->contrat?->bien?->description ?? '—' }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->periode ? periode_label($p->periode) : '—' }}</td>
                <td class="px-4 py-3 text-right font-medium text-gray-900">{{ format_fcfa($p->montant) }}</td>
                <td class="px-4 py-3 text-gray-600">{{ $p->mode_libelle }}</td>
                <td class="px-4 py-3"><x-badge :type="$p->statut" :label="$p->statut_libelle" /></td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('paiements.show', $p) }}" class="text-gray-400 hover:text-blue-600 transition-colors">
                            <x-icon name="eye" class="w-4 h-4" />
                        </a>
                        <a href="{{ route('paiements.recu', $p) }}" class="text-gray-400 hover:text-green-600 transition-colors" title="Télécharger reçu">
                            <x-icon name="document-arrow-down" class="w-4 h-4" />
                        </a>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-4 py-10 text-center text-gray-400">Aucun paiement trouvé.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-4">{{ $paiements->links() }}</div>
</div>
