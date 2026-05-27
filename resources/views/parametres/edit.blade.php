<x-layouts.app title="Paramètres de l'agence">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Paramètres de l'agence</h1>
        <p class="text-sm text-gray-500 mt-1">Informations, logo et configuration de votre agence.</p>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    <form action="{{ route('parametres.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf @method('PUT')

        {{-- Informations générales --}}
        <x-card title="Informations générales">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <x-form.input label="Nom de l'agence" name="name"
                    :value="old('name', $agency->name)" required
                    :error="$errors->first('name')" class="sm:col-span-2" />

                <x-form.input label="Email" name="email" type="email"
                    :value="old('email', $agency->email)"
                    :error="$errors->first('email')" />

                <x-form.input label="Téléphone" name="telephone"
                    :value="old('telephone', $agency->telephone)"
                    :error="$errors->first('telephone')" />

                <x-form.input label="Adresse" name="adresse"
                    :value="old('adresse', $agency->adresse)"
                    :error="$errors->first('adresse')" />

                <x-form.input label="Ville" name="ville"
                    :value="old('ville', $agency->ville)"
                    :error="$errors->first('ville')" />

                <x-form.select label="Pays" name="pays"
                    :options="config('locales.pays_labels', [])">
                    @foreach(config('locales.pays_labels', []) as $code => $label)
                        <option value="{{ $code }}" {{ old('pays', $agency->pays) === $code ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </x-form.select>

                <x-form.select label="Langue par défaut" name="locale_defaut"
                    :options="['fr' => 'Français', 'en' => 'English', 'pt-PT' => 'Português']">
                    @foreach(['fr' => 'Français', 'en' => 'English', 'pt-PT' => 'Português'] as $code => $label)
                        <option value="{{ $code }}" {{ old('locale_defaut', $agency->locale_defaut) === $code ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </x-form.select>

            </div>
        </x-card>

        {{-- Facturation & Apparence --}}
        <x-card title="Facturation & Apparence">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Taux de commission (%)
                    </label>
                    <input type="number" name="taux_commission" step="0.01" min="0" max="100"
                        value="{{ old('taux_commission', $agency->taux_commission) }}"
                        class="input-field w-full {{ $errors->has('taux_commission') ? 'border-red-400' : '' }}">
                    <p class="mt-1 text-xs text-gray-400">Appliqué automatiquement aux situations propriétaires.</p>
                    @error('taux_commission') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Couleur principale</label>
                    <div class="flex items-center gap-3">
                        <input type="color" name="couleur_principale"
                            value="{{ old('couleur_principale', $agency->couleur_principale ?? '#1e3a5f') }}"
                            class="h-10 w-20 rounded border border-gray-300 cursor-pointer">
                        <span class="text-xs text-gray-400">Utilisée dans les en-têtes de l'interface.</span>
                    </div>
                    @error('couleur_principale') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Logo --}}
                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Logo de l'agence</label>

                    @if($agency->logo)
                        <div class="mb-3 flex items-center gap-4">
                            <img src="{{ Storage::url($agency->logo) }}"
                                 alt="Logo {{ $agency->name }}"
                                 class="h-20 w-auto rounded-lg border border-gray-200 bg-white p-2 object-contain shadow-sm">
                            <div class="text-xs text-gray-500">
                                <p class="font-medium text-gray-700">Logo actuel</p>
                                <p>Sélectionnez un nouveau fichier pour le remplacer.</p>
                            </div>
                        </div>
                    @endif

                    <input type="file" name="logo" accept="image/jpeg,image/png,image/webp"
                        class="block w-full text-sm text-gray-500
                               file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0
                               file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700
                               hover:file:bg-blue-100">
                    <p class="mt-1 text-xs text-gray-400">PNG, JPG ou WebP — max 2 Mo. Affiché sur les reçus et situations propriétaires.</p>
                    @error('logo') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

            </div>
        </x-card>

        {{-- Informations en lecture seule --}}
        <x-card title="Informations du compte">
            <dl class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <dt class="text-gray-500">Plan</dt>
                    <dd class="font-medium text-gray-900 mt-0.5">{{ $agency->plan ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="text-gray-500">Expiration</dt>
                    <dd class="font-medium text-gray-900 mt-0.5">
                        {{ $agency->expires_at ? $agency->expires_at->format('d/m/Y') : 'Illimitée' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-gray-500">Statut</dt>
                    <dd class="mt-0.5">
                        @if($agency->is_active)
                            <span class="inline-flex items-center gap-1 text-green-700 font-medium">
                                <span class="w-2 h-2 rounded-full bg-green-500"></span> Active
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 text-red-700 font-medium">
                                <span class="w-2 h-2 rounded-full bg-red-500"></span> Suspendue
                            </span>
                        @endif
                    </dd>
                </div>
            </dl>
        </x-card>

        <div class="flex items-center gap-3">
            <button type="submit" class="btn-primary">Enregistrer les modifications</button>
        </div>

    </form>

</x-layouts.app>
