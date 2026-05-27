<x-layouts.superadmin title="Dashboard Super Admin">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Vue globale</h1>
        <p class="text-sm text-gray-500 mt-1">Toutes les agences — {{ now()->format('d/m/Y') }}</p>
    </div>

    {{-- KPIs globaux --}}
    <div class="grid grid-cols-2 xl:grid-cols-4 gap-4 mb-8">
        <x-stat-card label="Agences actives"
            :value="$nbAgencesActives . ' / ' . $nbAgences"
            icon="building" color="blue" />
        <x-stat-card label="CA total (toutes agences)"
            :value="format_fcfa($caTotal)"
            icon="banknotes" color="green" />
        <x-stat-card label="CA mois en cours"
            :value="format_fcfa($caMois)"
            icon="chart-bar" color="purple" />
        <x-stat-card label="Utilisateurs"
            :value="(string) $nbUsers"
            icon="users" color="orange" />
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <x-stat-card label="Contrats actifs"
            :value="(string) $nbContratsActifs"
            icon="document-text" color="blue" />
        <x-stat-card label="Locataires"
            :value="(string) $nbLocataires"
            icon="user-group" color="green" />
        <x-stat-card label="Agences"
            :value="(string) $nbAgences"
            icon="building" color="orange" />
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        {{-- Tableau des agences --}}
        <x-card>
            <x-slot:title>Agences</x-slot:title>
            <x-slot:actions>
                <a href="{{ route('superadmin.agencies.create') }}" class="btn-primary text-xs py-1.5 inline-flex items-center gap-1">
                    <x-icon name="plus" class="w-3.5 h-3.5" /> Nouvelle agence
                </a>
            </x-slot:actions>

            <x-table>
                <x-slot:head>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Agence</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Biens</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">CA total</th>
                    <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-center">Statut</th>
                    <th class="px-4 py-3"></th>
                </x-slot:head>
                @foreach($agences as $a)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $a->name }}</p>
                            <p class="text-xs text-gray-400">{{ $a->ville }}</p>
                        </td>
                        <td class="px-4 py-3 text-center text-sm text-gray-600">{{ $a->biens_count }}</td>
                        <td class="px-4 py-3 text-right font-medium text-gray-900 text-sm">{{ format_fcfa($a->ca_total) }}</td>
                        <td class="px-4 py-3 text-center">
                            <x-badge :type="$a->is_active ? 'actif' : 'inactif'" :label="$a->is_active ? 'Active' : 'Inactive'" />
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('superadmin.agencies.show', $a) }}" class="text-blue-600 hover:text-blue-800 text-xs">
                                Voir →
                            </a>
                        </td>
                    </tr>
                @endforeach
            </x-table>
        </x-card>

        {{-- Derniers utilisateurs --}}
        <x-card title="Derniers utilisateurs créés">
            @if($derniersUsers->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Aucun utilisateur.</p>
            @else
                <div class="space-y-3">
                    @foreach($derniersUsers as $u)
                        <div class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($u->prenom, 0, 1)) }}{{ strtoupper(substr($u->nom, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $u->prenom }} {{ $u->nom }}</p>
                                    <p class="text-xs text-gray-400">{{ $u->agency?->name }} · {{ $u->role }}</p>
                                </div>
                            </div>
                            <a href="{{ route('superadmin.users.show', $u) }}" class="text-gray-400 hover:text-blue-600">
                                <x-icon name="eye" class="w-4 h-4" />
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </x-card>

    </div>

</x-layouts.superadmin>
