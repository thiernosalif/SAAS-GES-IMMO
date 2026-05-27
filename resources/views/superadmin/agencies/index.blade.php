<x-layouts.superadmin title="Agences">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Agences</h1>
        <a href="{{ route('superadmin.agencies.create') }}" class="btn-primary inline-flex items-center gap-1">
            <x-icon name="plus" class="w-4 h-4" /> Nouvelle agence
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    <x-table>
        <x-slot:head>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Agence</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Contact</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Users</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Biens</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Contrats</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">CA total</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Statut</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Actions</th>
        </x-slot:head>

        @forelse($agencies as $a)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <p class="font-semibold text-gray-900">{{ $a->name }}</p>
                    <p class="text-xs text-gray-400">{{ $a->ville }} · Taux : {{ $a->taux_commission }}%</p>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">
                    <p>{{ $a->email ?? '—' }}</p>
                    <p class="text-xs text-gray-400">{{ $a->telephone ?? '' }}</p>
                </td>
                <td class="px-4 py-3 text-center text-sm font-medium text-gray-700">{{ $a->users_count }}</td>
                <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $a->biens_count }}</td>
                <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $a->contrats_count }}</td>
                <td class="px-4 py-3 text-right font-semibold text-gray-900 text-sm">{{ format_fcfa($a->ca_total) }}</td>
                <td class="px-4 py-3 text-center">
                    <x-badge :type="$a->is_active ? 'actif' : 'inactif'" :label="$a->is_active ? 'Active' : 'Inactive'" />
                </td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('superadmin.agencies.show', $a) }}" class="text-gray-400 hover:text-blue-600" title="Voir">
                            <x-icon name="eye" class="w-4 h-4" />
                        </a>
                        <a href="{{ route('superadmin.agencies.edit', $a) }}" class="text-gray-400 hover:text-amber-600" title="Modifier">
                            <x-icon name="pencil" class="w-4 h-4" />
                        </a>
                        @if($a->is_active)
                            <form method="POST" action="{{ route('superadmin.agencies.destroy', $a) }}">
                                @csrf @method('DELETE')
                                <button type="submit" onclick="return confirm('Désactiver cette agence ?')"
                                    class="text-gray-400 hover:text-red-600" title="Désactiver">
                                    <x-icon name="x-mark" class="w-4 h-4" />
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="px-4 py-10 text-center text-gray-400">Aucune agence.</td>
            </tr>
        @endforelse
    </x-table>

</x-layouts.superadmin>
