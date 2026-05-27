<x-layouts.app :title="'Paiement — ' . ($paiement->recu?->numero ?? '#' . $paiement->id)">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('paiements.index') }}" class="text-gray-400 hover:text-gray-700">
                <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
            </a>
            <h1 class="text-2xl font-bold text-gray-900">
                {{ $paiement->recu?->numero ?? 'Paiement #' . $paiement->id }}
            </h1>
            <x-badge :type="$paiement->statut" :label="$paiement->statut_libelle" />
        </div>
        <a href="{{ route('paiements.recu', $paiement) }}"
           class="btn-primary inline-flex items-center gap-1">
            <x-icon name="document-arrow-down" class="w-4 h-4" /> Télécharger le reçu PDF
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">

        <x-card title="Détail du paiement">
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Période</dt>
                    <dd class="font-semibold text-gray-900">{{ periode_label($paiement->periode) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Montant dû</dt>
                    <dd class="text-gray-700">{{ format_fcfa($paiement->montant_du) }}</dd>
                </div>
                <div class="flex justify-between border-t border-gray-100 pt-3">
                    <dt class="font-semibold text-gray-700">Montant payé</dt>
                    <dd class="font-bold text-gray-900 text-base">{{ format_fcfa($paiement->montant) }}</dd>
                </div>
                @if($paiement->avance)
                <div class="flex justify-between">
                    <dt class="text-gray-500">dont avance</dt>
                    <dd class="text-gray-700">{{ format_fcfa($paiement->avance) }}</dd>
                </div>
                @endif
                @if($paiement->acompte)
                <div class="flex justify-between">
                    <dt class="text-gray-500">dont acompte</dt>
                    <dd class="text-gray-700">{{ format_fcfa($paiement->acompte) }}</dd>
                </div>
                @endif
                <div class="flex justify-between border-t border-gray-100 pt-3">
                    <dt class="text-gray-500">Mode de paiement</dt>
                    <dd class="font-medium text-gray-900">{{ $paiement->mode_libelle }}</dd>
                </div>
                @if($paiement->transaction_reference && $paiement->transaction_reference !== 'none')
                <div class="flex justify-between">
                    <dt class="text-gray-500">Réf. transaction</dt>
                    <dd class="font-mono text-gray-900 text-xs">{{ $paiement->transaction_reference }}</dd>
                </div>
                @endif
                <div class="flex justify-between">
                    <dt class="text-gray-500">Date</dt>
                    <dd class="text-gray-700">{{ $paiement->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                @if($paiement->recu)
                <div class="flex justify-between border-t border-gray-100 pt-3">
                    <dt class="text-gray-500">N° reçu</dt>
                    <dd class="font-mono font-semibold text-blue-700">{{ $paiement->recu->numero }}</dd>
                </div>
                @endif
            </dl>
        </x-card>

        <div class="space-y-4">
            <x-card title="Locataire">
                <p class="font-semibold text-gray-900">{{ $paiement->contrat?->locataire?->full_name ?? '—' }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $paiement->contrat?->locataire?->telephone ?? '' }}</p>
                @if($paiement->contrat?->locataire)
                    <a href="{{ route('locataires.show', $paiement->contrat->locataire) }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2 inline-block">
                        Voir fiche locataire →
                    </a>
                @endif
            </x-card>

            <x-card title="Bien / Contrat">
                <p class="font-semibold text-gray-900">{{ $paiement->contrat?->bien?->description ?? '—' }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $paiement->contrat?->bien?->adresse ?? '' }}</p>
                <p class="text-sm text-gray-500">
                    Loyer : {{ format_fcfa($paiement->contrat?->loyer_mensuel) }}/mois
                </p>
                @if($paiement->contrat)
                    <a href="{{ route('contrats.show', $paiement->contrat) }}" class="text-blue-600 hover:text-blue-800 text-xs mt-2 inline-block">
                        Voir contrat →
                    </a>
                @endif
            </x-card>
        </div>
    </div>

</x-layouts.app>
