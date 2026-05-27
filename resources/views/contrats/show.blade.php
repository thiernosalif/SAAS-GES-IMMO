<x-layouts.app :title="'Contrat #' . $contrat->id">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('contrats.index') }}" class="text-gray-400 hover:text-gray-700">
                <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Contrat #{{ $contrat->id }}</h1>
            <x-badge :type="$contrat->statut" :label="ucfirst($contrat->statut)" />
        </div>
        <div class="flex items-center gap-2">
            @if($contrat->statut === 'actif')
                <a href="{{ route('contrats.edit', $contrat) }}" class="btn-secondary inline-flex items-center gap-1">
                    <x-icon name="pencil" class="w-4 h-4" /> Modifier
                </a>
                <form method="POST" action="{{ route('contrats.resilier', $contrat) }}">
                    @csrf @method('PATCH')
                    <button type="submit"
                        onclick="return confirm('Résilier ce contrat ?')"
                        class="btn-danger inline-flex items-center gap-1">
                        <x-icon name="x-mark" class="w-4 h-4" /> Résilier
                    </button>
                </form>
            @endif
        </div>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    {{-- Alerte expiration proche --}}
    @if($contrat->statut === 'actif' && $contrat->isExpiringSoon())
        <x-alert type="warning" message="Ce contrat expire dans {{ $contrat->expiresInDays() }} jour(s) ({{ $contrat->date_fin->format('d/m/Y') }})." class="mb-4" />
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Infos contrat --}}
        <x-card title="Informations du contrat" class="xl:col-span-1">
            <dl class="space-y-3 text-sm">
                <div>
                    <dt class="text-gray-500">Type de logement</dt>
                    <dd class="font-medium text-gray-900">{{ $contrat->type_logement ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Loyer mensuel</dt>
                    <dd class="font-bold text-gray-900 text-base">{{ format_fcfa($contrat->loyer_mensuel) }}</dd>
                </div>
                @if($contrat->charges_mensuelles)
                <div>
                    <dt class="text-gray-500">Charges mensuelles</dt>
                    <dd class="font-medium text-gray-900">{{ format_fcfa($contrat->charges_mensuelles) }}</dd>
                </div>
                @endif
                @if($contrat->avance_loyer)
                <div>
                    <dt class="text-gray-500">Avance de loyer</dt>
                    <dd class="font-medium text-gray-900">{{ $contrat->avance_loyer }} mois</dd>
                </div>
                @endif
                @if($contrat->caution)
                <div>
                    <dt class="text-gray-500">Caution</dt>
                    <dd class="font-medium text-gray-900">{{ format_fcfa($contrat->caution) }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-gray-500">Date début</dt>
                    <dd class="font-medium text-gray-900">{{ $contrat->date_debut?->format('d/m/Y') ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Date fin</dt>
                    <dd class="font-medium text-gray-900">{{ $contrat->date_fin?->format('d/m/Y') ?? 'Indéterminée' }}</dd>
                </div>
            </dl>
        </x-card>

        <div class="xl:col-span-2 space-y-6">

            {{-- Locataire --}}
            <x-card title="Locataire">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900 text-base">{{ $contrat->locataire?->full_name ?? '—' }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $contrat->locataire?->telephone ?? '' }}
                            @if($contrat->locataire?->email) · {{ $contrat->locataire->email }} @endif
                        </p>
                    </div>
                    <a href="{{ route('locataires.show', $contrat->locataire) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Voir fiche →
                    </a>
                </div>
            </x-card>

            {{-- Bien --}}
            <x-card title="Bien loué">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-semibold text-gray-900 text-base">{{ $contrat->bien?->description ?? '—' }}</p>
                        <p class="text-sm text-gray-500 mt-1">
                            {{ $contrat->bien?->adresse ?? '' }}
                            @if($contrat->bien?->ville) — {{ $contrat->bien->ville }} @endif
                        </p>
                        <p class="text-sm text-gray-500">Propriétaire : {{ $contrat->bien?->proprietaire?->full_name ?? '—' }}</p>
                    </div>
                    <a href="{{ route('biens.show', $contrat->bien) }}" class="text-blue-600 hover:text-blue-800 text-sm">
                        Voir bien →
                    </a>
                </div>
            </x-card>
        </div>
    </div>

    {{-- Historique des paiements --}}
    <div class="mt-6">
        <x-card>
            <x-slot:title>Historique des paiements</x-slot:title>
            <x-slot:actions>
                <a href="{{ route('paiements.create') }}?contrat_id={{ $contrat->id }}" class="btn-primary inline-flex items-center gap-1 text-xs py-1.5">
                    <x-icon name="plus" class="w-3.5 h-3.5" /> Enregistrer un paiement
                </a>
            </x-slot:actions>

            @if($contrat->paiements->isEmpty())
                <p class="text-sm text-gray-400 text-center py-6">Aucun paiement enregistré.</p>
            @else
                <x-table>
                    <x-slot:head>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Période</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Montant dû</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Montant payé</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Mode</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-4 py-3 text-xs font-medium text-gray-500 uppercase text-right">Reçu</th>
                    </x-slot:head>
                    @foreach($contrat->paiements as $p)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 font-medium text-gray-900">{{ periode_label($p->periode) }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ format_fcfa($p->montant_du) }}</td>
                            <td class="px-4 py-3 font-medium text-gray-900">{{ format_fcfa($p->montant) }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $p->mode_libelle }}</td>
                            <td class="px-4 py-3"><x-badge :type="$p->statut" :label="$p->statut_libelle" /></td>
                            <td class="px-4 py-3 text-gray-500 text-xs">{{ $p->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3 text-right">
                                @if($p->recu)
                                    <a href="{{ route('paiements.recu', $p) }}" class="text-blue-600 hover:text-blue-800 text-xs inline-flex items-center gap-1">
                                        <x-icon name="document-arrow-down" class="w-3.5 h-3.5" /> PDF
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-table>
            @endif
        </x-card>
    </div>

</x-layouts.app>
