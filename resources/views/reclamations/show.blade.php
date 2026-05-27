<x-layouts.app :title="'Réclamation #' . $reclamation->id">

    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('reclamations.index') }}" class="text-gray-400 hover:text-gray-700">
                <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
            </a>
            <h1 class="text-2xl font-bold text-gray-900">Réclamation #{{ $reclamation->id }}</h1>
            <x-badge :type="$reclamation->statut" :label="ucfirst(str_replace('_', ' ', $reclamation->statut))" />
        </div>
        <a href="{{ route('reclamations.edit', $reclamation) }}" class="btn-secondary inline-flex items-center gap-1">
            <x-icon name="pencil" class="w-4 h-4" /> Modifier
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        <x-card title="Détail de la réclamation" class="xl:col-span-2">
            <dl class="space-y-4 text-sm">
                <div>
                    <dt class="text-gray-500 font-medium mb-1">Motif</dt>
                    <dd class="text-gray-900 font-semibold text-base">{{ $reclamation->motif }}</dd>
                </div>
                @if($reclamation->description)
                <div>
                    <dt class="text-gray-500 font-medium mb-1">Description</dt>
                    <dd class="text-gray-700 whitespace-pre-line">{{ $reclamation->description }}</dd>
                </div>
                @endif
                <div>
                    <dt class="text-gray-500">Date d'ouverture</dt>
                    <dd class="text-gray-900">{{ $reclamation->created_at->format('d/m/Y H:i') }}</dd>
                </div>
                @if($reclamation->traitePar)
                <div>
                    <dt class="text-gray-500">Traitée par</dt>
                    <dd class="text-gray-900">{{ $reclamation->traitePar->prenom }} {{ $reclamation->traitePar->nom }}</dd>
                </div>
                @endif
            </dl>
        </x-card>

        <x-card title="Locataire">
            <p class="font-semibold text-gray-900 text-base">{{ $reclamation->locataire?->full_name ?? '—' }}</p>
            <p class="text-sm text-gray-500 mt-1">{{ $reclamation->locataire?->telephone ?? '' }}</p>
            @if($reclamation->locataire?->email)
                <p class="text-sm text-gray-500">{{ $reclamation->locataire->email }}</p>
            @endif
            @if($reclamation->locataire)
                <a href="{{ route('locataires.show', $reclamation->locataire) }}"
                   class="text-blue-600 hover:text-blue-800 text-xs mt-3 inline-block">
                    Voir fiche locataire →
                </a>
            @endif
        </x-card>
    </div>

</x-layouts.app>
