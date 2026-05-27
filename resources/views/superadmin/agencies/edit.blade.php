<x-layouts.superadmin :title="'Modifier — ' . $agency->name">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('superadmin.agencies.show', $agency) }}" class="text-gray-400 hover:text-gray-700">
            <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Modifier — {{ $agency->name }}</h1>
    </div>

    <x-card>
        <form action="{{ route('superadmin.agencies.update', $agency) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <x-form.input label="Nom de l'agence" name="name" :value="old('name', $agency->name)" required
                    :error="$errors->first('name')" class="sm:col-span-2" />

                <x-form.input label="Email" name="email" type="email" :value="old('email', $agency->email)"
                    :error="$errors->first('email')" />

                <x-form.input label="Téléphone" name="telephone" :value="old('telephone', $agency->telephone)"
                    :error="$errors->first('telephone')" />

                <x-form.input label="Adresse" name="adresse" :value="old('adresse', $agency->adresse)"
                    :error="$errors->first('adresse')" />

                <x-form.input label="Ville" name="ville" :value="old('ville', $agency->ville)"
                    :error="$errors->first('ville')" />

                <x-form.select label="Pays" name="pays"
                    :options="config('locales.pays_labels', [])">
                    @foreach(config('locales.pays_labels', []) as $code => $label)
                        <option value="{{ $code }}" {{ old('pays', $agency->pays) === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select label="Langue par défaut" name="locale_defaut"
                    :options="['fr' => 'Français', 'en' => 'English', 'pt-PT' => 'Português']">
                    @foreach(['fr' => 'Français', 'en' => 'English', 'pt-PT' => 'Português'] as $code => $label)
                        <option value="{{ $code }}" {{ old('locale_defaut', $agency->locale_defaut) === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </x-form.select>

                <x-form.input label="Taux commission (%)" name="taux_commission" type="number"
                    step="0.01" :value="old('taux_commission', $agency->taux_commission)"
                    :error="$errors->first('taux_commission')" />

                <x-form.input label="Plan / Offre" name="plan" :value="old('plan', $agency->plan)"
                    :error="$errors->first('plan')" />

                <x-form.input label="Expiration" name="expires_at" type="date"
                    :value="old('expires_at', $agency->expires_at?->format('Y-m-d'))"
                    :error="$errors->first('expires_at')" />

                <x-form.input label="Couleur principale" name="couleur_principale" type="color"
                    :value="old('couleur_principale', $agency->couleur_principale ?? '#1e3a5f')"
                    :error="$errors->first('couleur_principale')" />
            </div>

            <div class="flex items-center gap-3">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="hidden" name="is_active" value="0">
                    <input type="checkbox" name="is_active" value="1"
                        {{ old('is_active', $agency->is_active) ? 'checked' : '' }}
                        class="w-4 h-4 rounded text-blue-600">
                    <span class="text-sm font-medium text-gray-700">Agence active</span>
                </label>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('superadmin.agencies.show', $agency) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>

</x-layouts.superadmin>
