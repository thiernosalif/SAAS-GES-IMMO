<x-layouts.superadmin title="Nouvel utilisateur">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('superadmin.users.index') }}" class="text-gray-400 hover:text-gray-700">
            <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Nouvel utilisateur</h1>
    </div>

    <x-card>
        <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <x-form.input label="Prénom" name="prenom" :value="old('prenom')" required
                    :error="$errors->first('prenom')" />

                <x-form.input label="Nom" name="nom" :value="old('nom')" required
                    :error="$errors->first('nom')" />

                <x-form.input label="Email" name="email" type="email" :value="old('email')" required
                    :error="$errors->first('email')" />

                <x-form.input label="Téléphone" name="telephone" :value="old('telephone')"
                    :error="$errors->first('telephone')" />

                <x-form.input label="Mot de passe" name="password" type="password" required
                    :error="$errors->first('password')" />

                <x-form.input label="Confirmer le mot de passe" name="password_confirmation" type="password" required />

                {{-- Agence --}}
                <div>
                    <label for="agency_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Agence <span class="text-red-500">*</span>
                    </label>
                    <select id="agency_id" name="agency_id"
                        class="input-field bg-white w-full {{ $errors->has('agency_id') ? 'border-red-400' : '' }}">
                        <option value="">— Sélectionner —</option>
                        @foreach($agencies as $a)
                            <option value="{{ $a->id }}" {{ old('agency_id', request('agency_id')) == $a->id ? 'selected' : '' }}>
                                {{ $a->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('agency_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Zone --}}
                <div>
                    <label for="zone_id" class="block text-sm font-medium text-gray-700 mb-1">Zone / Antenne</label>
                    <select id="zone_id" name="zone_id"
                        class="input-field bg-white w-full {{ $errors->has('zone_id') ? 'border-red-400' : '' }}">
                        <option value="">— Toutes zones (admin) —</option>
                        @foreach($zones as $z)
                            <option value="{{ $z->id }}" {{ old('zone_id') == $z->id ? 'selected' : '' }}>
                                {{ $z->nom }} ({{ $z->agency?->name }})
                            </option>
                        @endforeach
                    </select>
                    @error('zone_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <x-form.select label="Rôle" name="role" required
                    :options="['agency_admin' => 'Admin agence', 'gestionnaire' => 'Gestionnaire', 'readonly' => 'Lecture seule']"
                    :error="$errors->first('role')" />

                <x-form.select label="Pays" name="pays"
                    :options="config('locales.pays_labels', [])"
                    :error="$errors->first('pays')" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Créer l'utilisateur</button>
                <a href="{{ route('superadmin.users.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>

</x-layouts.superadmin>
