<x-layouts.app :title="'Modifier réclamation #' . $reclamation->id">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('reclamations.show', $reclamation) }}" class="text-gray-400 hover:text-gray-700">
            <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Modifier réclamation #{{ $reclamation->id }}</h1>
    </div>

    <x-card>
        <form action="{{ route('reclamations.update', $reclamation) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="sm:col-span-2">
                    <label for="locataire_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Locataire <span class="text-red-500">*</span>
                    </label>
                    <select id="locataire_id" name="locataire_id"
                        class="input-field bg-white w-full {{ $errors->has('locataire_id') ? 'border-red-400' : '' }}">
                        @foreach($locataires as $l)
                            <option value="{{ $l->id }}" {{ old('locataire_id', $reclamation->locataire_id) == $l->id ? 'selected' : '' }}>
                                {{ $l->full_name }} — {{ $l->telephone ?? '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('locataire_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <x-form.input
                    label="Motif"
                    name="motif"
                    :value="old('motif', $reclamation->motif)"
                    required
                    :error="$errors->first('motif')"
                    class="sm:col-span-2" />

                <x-form.select
                    label="Statut"
                    name="statut"
                    required
                    :options="['ouverte' => 'Ouverte', 'en_cours' => 'En cours', 'resolue' => 'Résolue']"
                    :error="$errors->first('statut')">
                    @foreach(['ouverte' => 'Ouverte', 'en_cours' => 'En cours', 'resolue' => 'Résolue'] as $val => $label)
                        <option value="{{ $val }}" {{ old('statut', $reclamation->statut) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </x-form.select>
            </div>

            <div>
                <label for="description" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="description" name="description" rows="4"
                    class="input-field w-full {{ $errors->has('description') ? 'border-red-400' : '' }}">{{ old('description', $reclamation->description) }}</textarea>
                @error('description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('reclamations.show', $reclamation) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>

</x-layouts.app>
