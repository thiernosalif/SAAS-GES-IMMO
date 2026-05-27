<x-layouts.app :title="$locataire->full_name">
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('locataires.index') }}" class="text-gray-400 hover:text-gray-700"><x-icon name="arrow-down" class="w-5 h-5 rotate-90" /></a>
            <h1 class="text-2xl font-bold text-gray-900">{{ $locataire->full_name }}</h1>
        </div>
        <a href="{{ route('locataires.edit', $locataire) }}" class="btn-secondary inline-flex items-center gap-1">
            <x-icon name="pencil" class="w-4 h-4" /> Modifier
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <x-card title="Informations" class="xl:col-span-1">
            <dl class="space-y-3 text-sm">
                <div><dt class="text-gray-500">CIN</dt><dd class="font-medium text-gray-900">{{ $locataire->cin ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Téléphone</dt><dd class="font-medium text-gray-900">{{ $locataire->telephone ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Email</dt><dd class="font-medium text-gray-900">{{ $locataire->email ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Mobile réf.</dt><dd class="font-medium text-gray-900">{{ $locataire->mobileref ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Adresse</dt><dd class="font-medium text-gray-900">{{ $locataire->adresse ?? '—' }}</dd></div>
                <div><dt class="text-gray-500">Coord. pro</dt><dd class="font-medium text-gray-900">{{ $locataire->coordonne_pro ?? '—' }}</dd></div>
            </dl>
        </x-card>

        <div class="xl:col-span-2 space-y-6">
            <x-card title="Contrats">
                @if($locataire->contrats->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-6">Aucun contrat.</p>
                @else
                    <x-table>
                        <x-slot:head>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Bien</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Loyer</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Début</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase"></th>
                        </x-slot:head>
                        @foreach($locataire->contrats as $c)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-gray-900">{{ $c->bien?->description ?? '—' }}</td>
                                <td class="px-4 py-3 text-right text-gray-900">{{ format_fcfa($c->loyer_mensuel) }}</td>
                                <td class="px-4 py-3 text-gray-600">{{ $c->date_debut?->format('d/m/Y') }}</td>
                                <td class="px-4 py-3"><x-badge :type="$c->statut" :label="ucfirst($c->statut)" /></td>
                                <td class="px-4 py-3"><a href="{{ route('contrats.show', $c) }}" class="text-blue-600 hover:underline text-sm">Voir</a></td>
                            </tr>
                        @endforeach
                    </x-table>
                @endif
            </x-card>

            <x-card title="Derniers paiements">
                @if($locataire->paiements->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-6">Aucun paiement.</p>
                @else
                    <x-table>
                        <x-slot:head>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Période</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Montant</th>
                            <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
                        </x-slot:head>
                        @foreach($locataire->paiements as $p)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-600">{{ $p->periode ? periode_label($p->periode) : '—' }}</td>
                                <td class="px-4 py-3 text-right font-medium text-gray-900">{{ format_fcfa($p->montant) }}</td>
                                <td class="px-4 py-3"><x-badge :type="$p->statut" :label="$p->statut_libelle" /></td>
                            </tr>
                        @endforeach
                    </x-table>
                @endif
            </x-card>
        </div>
    </div>
</x-layouts.app>
