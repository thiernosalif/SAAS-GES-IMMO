<x-layouts.app title="Comptabilité">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Comptabilité caisse</h1>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    {{-- KPIs --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <x-stat-card
            label="Solde courant"
            :value="format_fcfa($solde)"
            icon="banknotes"
            :color="$solde >= 0 ? 'green' : 'red'" />
        <x-stat-card
            label="Entrées (mois)"
            :value="format_fcfa($totalEntrees)"
            icon="arrow-up"
            color="green" />
        <x-stat-card
            label="Sorties (mois)"
            :value="format_fcfa($totalSorties)"
            icon="arrow-down"
            color="red" />
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Tableau des mouvements --}}
        <div class="xl:col-span-2">
            @livewire('comptabilite.comptabilite-table')
        </div>

        {{-- Formulaire d'ajout --}}
        <x-card title="Enregistrer un mouvement">
            <form action="{{ route('comptabilite.store') }}" method="POST" class="space-y-4">
                @csrf

                <x-form.select
                    label="Type"
                    name="type"
                    required
                    :options="['entree' => '↑ Entrée', 'sortie' => '↓ Sortie']"
                    :error="$errors->first('type')" />

                <x-form.input
                    label="Montant (FCFA)"
                    name="montant"
                    type="number"
                    step="1"
                    :value="old('montant')"
                    required
                    :error="$errors->first('montant')" />

                <x-form.input
                    label="Motif"
                    name="motif"
                    :value="old('motif')"
                    required
                    :error="$errors->first('motif')" />

                <x-form.input
                    label="Catégorie"
                    name="categorie"
                    :value="old('categorie')"
                    hint="Ex: loyer, charge, réparation…"
                    :error="$errors->first('categorie')" />

                <button type="submit" class="btn-primary w-full">Enregistrer</button>
            </form>
        </x-card>
    </div>

</x-layouts.app>
