<x-layouts.app title="Nouvel utilisateur">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-gray-700">
            <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Nouvel utilisateur</h1>
    </div>

    <x-card>
        <form action="{{ route('users.store') }}" method="POST" class="space-y-5">
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

                {{-- Rôle --}}
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-1">
                        Rôle <span class="text-red-500">*</span>
                    </label>
                    <select id="role" name="role"
                        class="input-field bg-white w-full {{ $errors->has('role') ? 'border-red-400' : '' }}">
                        <option value="">— Sélectionner —</option>
                        <option value="agency_admin"  {{ old('role') === 'agency_admin'  ? 'selected' : '' }}>Admin agence</option>
                        <option value="gestionnaire"  {{ old('role') === 'gestionnaire'  ? 'selected' : '' }}>Gestionnaire</option>
                        <option value="readonly"      {{ old('role') === 'readonly'      ? 'selected' : '' }}>Lecture seule</option>
                    </select>
                    @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Zone --}}
                <div>
                    <label for="zone_id" class="block text-sm font-medium text-gray-700 mb-1">Zone / Antenne</label>
                    <select id="zone_id" name="zone_id" class="input-field bg-white w-full">
                        <option value="">— Toutes zones (admin) —</option>
                        @foreach($zones as $z)
                            <option value="{{ $z->id }}" {{ old('zone_id') == $z->id ? 'selected' : '' }}>
                                {{ $z->nom }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-400">Laisser vide pour un accès à toutes les zones de l'agence.</p>
                    @error('zone_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Créer l'utilisateur</button>
                <a href="{{ route('users.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>

</x-layouts.app>
