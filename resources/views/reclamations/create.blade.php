<x-layouts.app title="Nouvelle réclamation">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('reclamations.index') }}" class="text-gray-400 hover:text-gray-700">
            <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Nouvelle réclamation</h1>
    </div>

    <x-card>
        <form action="{{ route('reclamations.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="sm:col-span-2">
                    <label for="locataire_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Locataire <span class="text-red-500">*</span>
                    </label>
                    <select id="locataire_id" name="locataire_id"
                        class="input-field bg-white w-full {{ $errors->has('locataire_id') ? 'border-red-400' : '' }}">
                        <option value="">— Sélectionner un locataire —</option>
                        @foreach($locataires as $l)
                            <option value="{{ $l->id }}" {{ old('locataire_id') == $l->id ? 'selected' : '' }}>
                                {{ $l->full_name }} — {{ $l->telephone ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('locataire_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <x-form.input
                    label="Motif"
                    name="motif"
                    :value="old('motif')"
                    required
                    :error="$errors->first('motif')"
                    class="sm:col-span-2" />

                <x-form.select
                    label="Statut initial"
                    name="statut"
                    required
                    :options="['ouverte' => 'Ouverte', 'en_cours' => 'En cours', 'resolue' => 'Résolue']"
                    :error="$errors->first('statut')" />
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="input-field w-full {{ $errors->has('description') ? 'border-red-400' : '' }}"
                    placeholder="Détails de la réclamation…">{{ old('description') }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('reclamations.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>

</x-layouts.app>
