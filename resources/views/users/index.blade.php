<x-layouts.app title="Utilisateurs">

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Utilisateurs</h1>
            <p class="text-sm text-gray-500 mt-1">Membres de {{ $agency->name }}</p>
        </div>
        <a href="{{ route('users.create') }}" class="btn-primary inline-flex items-center gap-1">
            <x-icon name="plus" class="w-4 h-4" /> Nouvel utilisateur
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif
    @if(session('error'))
        <x-alert type="error" :message="session('error')" class="mb-4" />
    @endif

    <x-card>
        @if($users->isEmpty())
            <p class="text-sm text-gray-400 text-center py-10">Aucun utilisateur dans cette agence.</p>
        @else
            <div class="overflow-x-auto -mx-6 -my-6">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nom</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Rôle</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Zone / Antenne</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Téléphone</th>
                            <th class="px-4 py-3"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 {{ $user->id === auth()->id() ? 'bg-blue-50/40' : '' }}">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-blue-800 text-white flex items-center justify-center text-xs font-bold shrink-0">
                                            {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $user->prenom }} {{ $user->nom }}</p>
                                            @if($user->id === auth()->id())
                                                <p class="text-xs text-blue-600">Vous</p>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $user->email }}</td>
                                <td class="px-4 py-3">
                                    @php
                                        $roleColors = [
                                            'agency_admin' => 'bg-purple-100 text-purple-800',
                                            'gestionnaire' => 'bg-blue-100 text-blue-800',
                                            'readonly'     => 'bg-gray-100 text-gray-600',
                                        ];
                                        $roleLabels = [
                                            'agency_admin' => 'Admin agence',
                                            'gestionnaire' => 'Gestionnaire',
                                            'readonly'     => 'Lecture seule',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-600' }}">
                                        {{ $roleLabels[$user->role] ?? $user->role }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-gray-600">{{ $user->zone?->nom ?? '— toutes zones' }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $user->telephone ?? '—' }}</td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2 justify-end">
                                        <a href="{{ route('users.edit', $user) }}"
                                           class="text-xs text-blue-600 hover:text-blue-800 font-medium">Modifier</a>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('users.destroy', $user) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    onclick="return confirm('Supprimer {{ $user->prenom }} {{ $user->nom }} ?')"
                                                    class="text-xs text-red-500 hover:text-red-700 font-medium">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </x-card>

</x-layouts.app>
