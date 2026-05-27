<x-layouts.app title="Enregistrer un paiement">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('paiements.index') }}" class="text-gray-400 hover:text-gray-700">
            <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Enregistrer un paiement</h1>
    </div>

    <x-card>
        <form action="{{ route('paiements.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- Contrat --}}
                <div class="sm:col-span-2">
                    <label for="contrat_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Contrat (locataire) <span class="text-red-500">*</span>
                    </label>
                    <select id="contrat_id" name="contrat_id"
                        class="input-field bg-white w-full {{ $errors->has('contrat_id') ? 'border-red-400' : '' }}">
                        <option value="">— Sélectionner un contrat actif —</option>
                        @foreach($contrats as $c)
                            <option value="{{ $c->id }}" {{ (old('contrat_id', request('contrat_id')) == $c->id) ? 'selected' : '' }}>
                                {{ $c->locataire?->full_name ?? '?' }} — {{ $c->bien?->description ?? '?' }}
                                ({{ format_fcfa($c->loyer_mensuel) }}/mois)
                            </option>
                        @endforeach
                    </select>
                    @error('contrat_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Période --}}
                <x-form.input
                    label="Période (mois payé)"
                    name="periode"
                    type="date"
                    :value="old('periode', now()->startOfMonth()->format('Y-m-d'))"
                    required
                    hint="Saisir le 1er du mois concerné"
                    :error="$errors->first('periode')" />

                {{-- Mode de paiement --}}
                <x-form.select
                    label="Mode de paiement"
                    name="mode_paiement"
                    required
                    :options="['especes' => 'Espèces', 'virement' => 'Virement bancaire', 'mobile_money' => 'Mobile Money', 'cheque' => 'Chèque']"
                    :error="$errors->first('mode_paiement')" />

                {{-- Montant --}}
                <x-form.input
                    label="Montant payé (FCFA)"
                    name="montant"
                    type="number"
                    step="1"
                    :value="old('montant')"
                    required
                    :error="$errors->first('montant')" />

                {{-- Montant dû --}}
                <x-form.input
                    label="Montant dû (FCFA)"
                    name="montant_du"
                    type="number"
                    step="1"
                    :value="old('montant_du')"
                    hint="Laisser vide si égal au loyer"
                    :error="$errors->first('montant_du')" />

                {{-- Statut --}}
                <x-form.select
                    label="Statut"
                    name="statut"
                    required
                    :options="['complet' => 'Complet', 'partiel' => 'Partiel', 'avance' => 'Avance']"
                    :error="$errors->first('statut')" />

                {{-- Référence transaction --}}
                <x-form.input
                    label="Référence transaction"
                    name="transaction_reference"
                    :value="old('transaction_reference')"
                    hint="Numéro Wave, Orange Money, etc."
                    :error="$errors->first('transaction_reference')" />

                {{-- Avance --}}
                <x-form.input
                    label="Avance (FCFA)"
                    name="avance"
                    type="number"
                    step="1"
                    :value="old('avance')"
                    hint="Montant d'avance versé"
                    :error="$errors->first('avance')" />

                {{-- Acompte --}}
                <x-form.input
                    label="Acompte (FCFA)"
                    name="acompte"
                    type="number"
                    step="1"
                    :value="old('acompte')"
                    :error="$errors->first('acompte')" />

                {{-- Complément --}}
                <x-form.input
                    label="Complément (FCFA)"
                    name="complement"
                    type="number"
                    step="1"
                    :value="old('complement')"
                    :error="$errors->first('complement')" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer et générer le reçu</button>
                <a href="{{ route('paiements.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>

</x-layouts.app>
