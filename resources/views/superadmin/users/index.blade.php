<x-layouts.superadmin title="Utilisateurs">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Utilisateurs</h1>
        <a href="{{ route('superadmin.users.create') }}" class="btn-primary inline-flex items-center gap-1">
            <x-icon name="plus" class="w-4 h-4" /> Nouvel utilisateur
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif
    @if(session('error'))
        <x-alert type="error" :message="session('error')" class="mb-4" />
    @endif

    <x-table>
        <x-slot:head>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Agence</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Zone</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Rôle</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Téléphone</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Créé le</th>
            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Actions</th>
        </x-slot:head>

        @forelse($users as $u)
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-3">
                    <div class="flex items-center gap-2.5">
                        <div class="w-7 h-7 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0">
                            {{ strtoupper(substr($u->prenom, 0, 1)) }}{{ strtoupper(substr($u->nom, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $u->prenom }} {{ $u->nom }}</p>
                            <p class="text-xs text-gray-400">{{ $u->email }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $u->agency?->name ?? '—' }}</td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $u->zone?->nom ?? '—' }}</td>
                <td class="px-4 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                        {{ $u->role }}
                    </span>
                </td>
                <td class="px-4 py-3 text-sm text-gray-600">{{ $u->telephone ?? '—' }}</td>
                <td class="px-4 py-3 text-xs text-gray-400">{{ $u->created_at->format('d/m/Y') }}</td>
                <td class="px-4 py-3 text-right">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('superadmin.users.show', $u) }}" class="text-gray-400 hover:text-blue-600" title="Voir">
                            <x-icon name="eye" class="w-4 h-4" />
                        </a>
                        <a href="{{ route('superadmin.users.edit', $u) }}" class="text-gray-400 hover:text-amber-600" title="Modifier">
                            <x-icon name="pencil" class="w-4 h-4" />
                        </a>
                        @if(!$u->isSuperAdmin())
                            <form method="POST" action="{{ route('superadmin.users.destroy', $u) }}">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Supprimer {{ $u->prenom }} {{ $u->nom }} ?')"
                                    class="text-gray-400 hover:text-red-600" title="Supprimer">
                                    <x-icon name="trash" class="w-4 h-4" />
                                </button>
                            </form>
                        @endif
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-4 py-10 text-center text-gray-400">Aucun utilisateur.</td>
            </tr>
        @endforelse
    </x-table>

    <div class="mt-4">
        {{ $users->links() }}
    </div>

</x-layouts.superadmin>
