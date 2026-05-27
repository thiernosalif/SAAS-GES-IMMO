<x-layouts.app title="Modifier contrat">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('contrats.show', $contrat) }}" class="text-gray-400 hover:text-gray-700"><x-icon name="arrow-down" class="w-5 h-5 rotate-90" /></a>
        <h1 class="text-2xl font-bold text-gray-900">Modifier contrat</h1>
    </div>
    <x-card>
        <form action="{{ route('contrats.update', $contrat) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Bien <span class="text-red-500">*</span></label>
                    <select name="bien_id" class="input-field w-full bg-white">
                        <option value="">— Choisir —</option>
                        @foreach($biens as $b)
                            <option value="{{ $b->id }}" {{ old('bien_id', $contrat->bien_id) == $b->id ? 'selected' : '' }}>
                                {{ $b->description }} ({{ $b->proprietaire?->full_name }})
                            </option>
                        @endforeach
                    </select>
                    @error('bien_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Locataire <span class="text-red-500">*</span></label>
                    <select name="locataire_id" class="input-field w-full bg-white">
                        <option value="">— Choisir —</option>
                        @foreach($locataires as $l)
                            <option value="{{ $l->id }}" {{ old('locataire_id', $contrat->locataire_id) == $l->id ? 'selected' : '' }}>{{ $l->full_name }}</option>
                        @endforeach
                    </select>
                    @error('locataire_id')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <x-form.input label="Type logement" name="type_logement" :value="old('type_logement', $contrat->type_logement)" :error="$errors->first('type_logement')" />
                <x-form.input label="Loyer mensuel (FCFA)" name="loyer_mensuel" type="number" step="1" :value="old('loyer_mensuel', $contrat->loyer_mensuel)" required :error="$errors->first('loyer_mensuel')" />
                <x-form.input label="Charges mensuelles (FCFA)" name="charges_mensuelles" type="number" step="1" :value="old('charges_mensuelles', $contrat->charges_mensuelles)" :error="$errors->first('charges_mensuelles')" />
                <x-form.input label="Avance loyer (FCFA)" name="avance_loyer" type="number" step="1" :value="old('avance_loyer', $contrat->avance_loyer)" :error="$errors->first('avance_loyer')" />
                <x-form.input label="Caution (FCFA)" name="caution" type="number" step="1" :value="old('caution', $contrat->caution)" :error="$errors->first('caution')" />
                <x-form.input label="Date début" name="date_debut" type="date" :value="old('date_debut', $contrat->date_debut?->format('Y-m-d'))" required :error="$errors->first('date_debut')" />
                <x-form.input label="Date fin" name="date_fin" type="date" :value="old('date_fin', $contrat->date_fin?->format('Y-m-d'))" :error="$errors->first('date_fin')" />
                <x-form.select label="Statut" name="statut" required :error="$errors->first('statut')"
                    :options="['actif'=>'Actif','resilie'=>'Résilié','expire'=>'Expiré']" />
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Mettre à jour</button>
                <a href="{{ route('contrats.show', $contrat) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>
</x-layouts.app>
