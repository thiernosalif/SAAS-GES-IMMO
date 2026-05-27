<x-layouts.app title="Nouveau bien">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('biens.index') }}" class="text-gray-400 hover:text-gray-700">
            <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Nouveau bien</h1>
    </div>

    <x-card>
        <form action="{{ route('biens.store') }}" method="POST" class="space-y-5">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input label="Description" name="description" :value="old('description')" required
                    :error="$errors->first('description')" class="sm:col-span-2" />

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Propriétaire <span class="text-red-500">*</span></label>
                    <select name="proprietaire_id" class="input-field w-full bg-white {{ $errors->has('proprietaire_id') ? 'border-red-400' : '' }}">
                        <option value="">— Choisir —</option>
                        @foreach($proprietaires as $p)
                            <option value="{{ $p->id }}" {{ old('proprietaire_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->full_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('proprietaire_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zone</label>
                    <select name="zone_id" class="input-field w-full bg-white">
                        <option value="">— Choisir —</option>
                        @foreach($zones as $z)
                            <option value="{{ $z->id }}" {{ old('zone_id') == $z->id ? 'selected' : '' }}>{{ $z->nom }}</option>
                        @endforeach
                    </select>
                </div>

                <x-form.select label="Type" name="type" required :error="$errors->first('type')"
                    :options="['appartement'=>'Appartement','chambre'=>'Chambre','studio'=>'Studio','villa'=>'Villa','bureau'=>'Bureau','magasin'=>'Magasin','autre'=>'Autre']" />

                <x-form.input label="Adresse" name="adresse" :value="old('adresse')" :error="$errors->first('adresse')" />
                <x-form.input label="Ville" name="ville" :value="old('ville')" :error="$errors->first('ville')" />
                <x-form.input label="Quartier" name="quartier" :value="old('quartier')" :error="$errors->first('quartier')" />
                <x-form.input label="Nombre d'unités" name="nombre_unites" type="number" :value="old('nombre_unites', 1)" :error="$errors->first('nombre_unites')" />
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('biens.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>
</x-layouts.app>
