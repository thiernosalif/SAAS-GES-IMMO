<x-layouts.superadmin :title="$user->prenom . ' ' . $user->nom">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.users.index') }}" class="text-gray-400 hover:text-gray-700">
                <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $user->prenom }} {{ $user->nom }}</h1>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                {{ $user->role }}
            </span>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn-secondary inline-flex items-center gap-1">
                <x-icon name="pencil" class="w-4 h-4" /> Modifier
            </a>
            @if(!$user->isSuperAdmin())
                <form method="POST" action="{{ route('superadmin.users.destroy', $user) }}">
                    @csrf @method('DELETE')
                    <button type="submit"
                        onclick="return confirm('Supprimer définitivement cet utilisateur ?')"
                        class="btn-danger inline-flex items-center gap-1">
                        <x-icon name="trash" class="w-4 h-4" /> Supprimer
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <x-card title="Informations personnelles">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium text-gray-900">{{ $user->email }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Téléphone</dt>
                    <dd class="font-medium text-gray-900">{{ $user->telephone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Pays</dt>
                    <dd class="font-medium text-gray-900">{{ $user->pays ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Langue</dt>
                    <dd class="font-medium text-gray-900">{{ $user->locale ?? 'fr' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Créé le</dt>
                    <dd class="text-gray-700">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                </div>
            </dl>
        </x-card>

        <x-card title="Appartenance">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Agence</dt>
                    <dd class="font-semibold text-gray-900">
                        @if($user->agency)
                            <a href="{{ route('superadmin.agencies.show', $user->agency) }}" class="text-blue-600 hover:underline">
                                {{ $user->agency->name }}
                            </a>
                        @else
                            —
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Zone / Antenne</dt>
                    <dd class="font-medium text-gray-900">{{ $user->zone?->nom ?? '— (toutes zones)' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Rôle</dt>
                    <dd>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            {{ $user->role }}
                        </span>
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Permissions Spatie</dt>
                    <dd class="text-gray-700">
                        @foreach($user->getRoleNames() as $role)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 text-gray-600 mr-1">{{ $role }}</span>
                        @endforeach
                    </dd>
                </div>
            </dl>
        </x-card>
    </div>

</x-layouts.superadmin>
