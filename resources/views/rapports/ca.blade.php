<x-layouts.app title="Rapport CA">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Rapport CA mensuel</h1>

        <form method="GET" action="{{ route('rapports.ca') }}" class="flex items-center gap-2">
            <select name="annee" class="input-field w-auto" onchange="this.form.submit()">
                @foreach($annees as $a)
                    <option value="{{ $a }}" {{ $a == $annee ? 'selected' : '' }}>{{ $a }}</option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- Total année --}}
    @php $totalAnnee = collect($moisData)->sum('total'); @endphp
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-stat-card
            label="CA total {{ $annee }}"
            :value="format_fcfa($totalAnnee)"
            icon="banknotes"
            color="blue" />
        <x-stat-card
            label="Moyenne mensuelle"
            :value="format_fcfa($totalAnnee / 12)"
            icon="chart-bar"
            color="green" />
        <x-stat-card
            label="Meilleur mois"
            :value="collect($moisData)->sortByDesc('total')->first()['label'] ?? '—'"
            icon="calculator"
            color="purple" />
    </div>

    <x-card :title="'Détail mensuel ' . $annee">
        <x-table>
            <x-slot:head>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase w-8">#</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Mois</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Loyers encaissés</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Part du CA annuel</th>
                <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Barre</th>
            </x-slot:head>

            @php $maxMois = collect($moisData)->max('total') ?: 1; @endphp

            @foreach($moisData as $num => $mois)
                @php $pct = $totalAnnee > 0 ? round($mois['total'] / $totalAnnee * 100, 1) : 0; @endphp
                <tr class="{{ $mois['total'] > 0 ? 'hover:bg-gray-50' : 'text-gray-300' }}">
                    <td class="px-4 py-3 text-xs text-gray-400">{{ str_pad($num, 2, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-3 font-medium {{ $mois['total'] > 0 ? 'text-gray-900' : 'text-gray-400' }}">
                        {{ $mois['label'] }}
                    </td>
                    <td class="px-4 py-3 text-right font-semibold {{ $mois['total'] > 0 ? 'text-gray-900' : 'text-gray-300' }}">
                        {{ $mois['total'] > 0 ? format_fcfa($mois['total']) : '—' }}
                    </td>
                    <td class="px-4 py-3 text-right text-gray-500 text-sm">
                        {{ $mois['total'] > 0 ? $pct . ' %' : '—' }}
                    </td>
                    <td class="px-4 py-3 w-32">
                        @if($mois['total'] > 0)
                            @php $barW = round($mois['total'] / $maxMois * 100); @endphp
                            <div class="h-2 rounded-full bg-gray-100 overflow-hidden">
                                <div class="h-full rounded-full bg-blue-500" style="width: {{ $barW }}%"></div>
                            </div>
                        @endif
                    </td>
                </tr>
            @endforeach

            <tr class="bg-gray-50 font-bold border-t-2 border-gray-200">
                <td colspan="2" class="px-4 py-3 text-sm text-gray-700">TOTAL {{ $annee }}</td>
                <td class="px-4 py-3 text-right text-gray-900">{{ format_fcfa($totalAnnee) }}</td>
                <td class="px-4 py-3 text-right text-gray-500">100 %</td>
                <td></td>
            </tr>
        </x-table>
    </x-card>

</x-layouts.app>
