<x-layouts.superadmin :title="$agency->name">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('superadmin.agencies.index') }}" class="text-gray-400 hover:text-gray-700">
                <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
            </a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $agency->name }}</h1>
            <x-badge :type="$agency->is_active ? 'actif' : 'inactif'" :label="$agency->is_active ? 'Active' : 'Inactive'" />
        </div>
        <a href="{{ route('superadmin.agencies.edit', $agency) }}" class="btn-secondary inline-flex items-center gap-1">
            <x-icon name="pencil" class="w-4 h-4" /> Modifier
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    {{-- KPIs agence --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-6">
        <x-stat-card label="CA total" :value="format_fcfa($caTotal)" icon="banknotes" color="blue" />
        <x-stat-card label="CA mois" :value="format_fcfa($caMois)" icon="chart-bar" color="green" />
        <x-stat-card label="Contrats actifs" :value="(string) $contratsActifs" icon="document-text" color="purple" />
        <x-stat-card label="Utilisateurs" :value="(string) $agency->users_count" icon="users" color="orange" />
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Infos agence --}}
        <x-card title="Informations" class="xl:col-span-1">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Email</dt>
                    <dd class="font-medium text-gray-900">{{ $agency->email ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Téléphone</dt>
                    <dd class="font-medium text-gray-900">{{ $agency->telephone ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Adresse</dt>
                    <dd class="text-gray-700">{{ $agency->adresse ?? '—' }}, {{ $agency->ville ?? '' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Taux commission</dt>
                    <dd class="font-medium text-gray-900">{{ $agency->taux_commission }} %</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Plan</dt>
                    <dd class="font-medium text-gray-900">{{ $agency->plan ?? '—' }}</dd>
                </div>
                @if($agency->expires_at)
                <div>
                    <dt class="text-gray-500">Expiration</dt>
                    <dd class="font-medium {{ $agency->expires_at->isPast() ? 'text-red-600' : 'text-gray-900' }}">
                        {{ $agency->expires_at->format('d/m/Y') }}
                    </dd>
                </div>
                @endif
                <div>
                    <dt class="text-gray-500">Langue défaut</dt>
                    <dd class="font-medium text-gray-900">{{ $agency->locale_defaut ?? 'fr' }}</dd>
                </div>
            </dl>

            {{-- Antennes --}}
            <div class="mt-5 pt-4 border-t border-gray-100">
                <h4 class="text-xs font-semibold text-gray-500 uppercase mb-3">Antennes / Zones</h4>
                @foreach($zones as $zone)
                    <div class="flex items-center justify-between py-1.5 text-sm">
                        <span class="text-gray-700">{{ $zone->nom }}</span>
                        <span class="text-xs text-gray-400">{{ $zone->users_count }} user(s)</span>
                    </div>
                @endforeach
            </div>
        </x-card>

        {{-- Utilisateurs de l'agence --}}
        <x-card class="xl:col-span-2">
            <x-slot:title>Utilisateurs ({{ count($users) }})</x-slot:title>
            <x-slot:actions>
                <a href="{{ route('superadmin.users.create') }}?agency_id={{ $agency->id }}"
                   class="btn-primary text-xs py-1.5 inline-flex items-center gap-1">
                    <x-icon name="plus" class="w-3.5 h-3.5" /> Ajouter
                </a>
            </x-slot:actions>

            @if($users->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Aucun utilisateur.</p>
            @else
                <x-table>
                    <x-slot:head>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Nom</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Email</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Zone</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Rôle</th>
                        <th class="px-4 py-3"></th>
                    </x-slot:head>
                    @foreach($users as $u)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-2.5 font-medium text-gray-900 text-sm">{{ $u->prenom }} {{ $u->nom }}</td>
                            <td class="px-4 py-2.5 text-gray-500 text-sm">{{ $u->email }}</td>
                            <td class="px-4 py-2.5 text-gray-500 text-sm">{{ $u->zone?->nom ?? '—' }}</td>
                            <td class="px-4 py-2.5 text-sm">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700">
                                    {{ $u->role }}
                                </span>
                            </td>
                            <td class="px-4 py-2.5 text-right">
                                <a href="{{ route('superadmin.users.edit', $u) }}" class="text-gray-400 hover:text-amber-600">
                                    <x-icon name="pencil" class="w-4 h-4" />
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </x-table>
            @endif
        </x-card>
    </div>

</x-layouts.superadmin>
