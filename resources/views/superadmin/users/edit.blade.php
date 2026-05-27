<x-layouts.superadmin :title="'Modifier — ' . $user->prenom . ' ' . $user->nom">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('superadmin.users.show', $user) }}" class="text-gray-400 hover:text-gray-700">
            <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">{{ $user->prenom }} {{ $user->nom }}</h1>
    </div>

    <x-card>
        <form action="{{ route('superadmin.users.update', $user) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <x-form.input label="Prénom" name="prenom" :value="old('prenom', $user->prenom)" required
                    :error="$errors->first('prenom')" />

                <x-form.input label="Nom" name="nom" :value="old('nom', $user->nom)" required
                    :error="$errors->first('nom')" />

                <x-form.input label="Email" name="email" type="email" :value="old('email', $user->email)" required
                    :error="$errors->first('email')" />

                <x-form.input label="Téléphone" name="telephone" :value="old('telephone', $user->telephone)"
                    :error="$errors->first('telephone')" />

                <x-form.input label="Nouveau mot de passe" name="password" type="password"
                    hint="Laisser vide pour ne pas changer"
                    :error="$errors->first('password')" />

                <x-form.input label="Confirmer le mot de passe" name="password_confirmation" type="password" />

                {{-- Agence --}}
                <div>
                    <label for="agency_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Agence <span class="text-red-500">*</span>
                    </label>
                    <select id="agency_id" name="agency_id"
                        class="input-field bg-white w-full {{ $errors->has('agency_id') ? 'border-red-400' : '' }}">
                        @foreach($agencies as $a)
                            <option value="{{ $a->id }}" {{ old('agency_id', $user->agency_id) == $a->id ? 'selected' : '' }}>
                                {{ $a->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('agency_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Zone --}}
                <div>
                    <label for="zone_id" class="block text-sm font-medium text-gray-700 mb-1">Zone / Antenne</label>
                    <select id="zone_id" name="zone_id" class="input-field bg-white w-full">
                        <option value="">— Toutes zones (admin) —</option>
                        @foreach($zones as $z)
                            <option value="{{ $z->id }}" {{ old('zone_id', $user->zone_id) == $z->id ? 'selected' : '' }}>
                                {{ $z->nom }} ({{ $z->agency?->name }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <x-form.select label="Rôle" name="role" required
                    :options="['agency_admin' => 'Admin agence', 'gestionnaire' => 'Gestionnaire', 'readonly' => 'Lecture seule']"
                    :error="$errors->first('role')">
                    @foreach(['agency_admin' => 'Admin agence', 'gestionnaire' => 'Gestionnaire', 'readonly' => 'Lecture seule'] as $val => $label)
                        <option value="{{ $val }}" {{ old('role', $user->role) === $val ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </x-form.select>

                <x-form.select label="Pays" name="pays"
                    :options="config('locales.pays_labels', [])">
                    @foreach(config('locales.pays_labels', []) as $code => $label)
                        <option value="{{ $code }}" {{ old('pays', $user->pays) === $code ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </x-form.select>
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Enregistrer</button>
                <a href="{{ route('superadmin.users.show', $user) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>

</x-layouts.superadmin>
