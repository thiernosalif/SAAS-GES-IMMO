<x-layouts.app :title="'Modifier — ' . $locataire->full_name">
    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('locataires.show', $locataire) }}" class="text-gray-400 hover:text-gray-700"><x-icon name="arrow-down" class="w-5 h-5 rotate-90" /></a>
        <h1 class="text-2xl font-bold text-gray-900">Modifier : {{ $locataire->full_name }}</h1>
    </div>
    <x-card>
        <form action="{{ route('locataires.update', $locataire) }}" method="POST" class="space-y-5">
            @csrf @method('PUT')
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input label="Prénom" name="prenom" :value="old('prenom', $locataire->prenom)" required :error="$errors->first('prenom')" />
                <x-form.input label="Nom" name="nom" :value="old('nom', $locataire->nom)" required :error="$errors->first('nom')" />
                <x-form.input label="CIN" name="cin" :value="old('cin', $locataire->cin)" :error="$errors->first('cin')" />
                <x-form.input label="Téléphone" name="telephone" :value="old('telephone', $locataire->telephone)" :error="$errors->first('telephone')" />
                <x-form.input label="Email" name="email" type="email" :value="old('email', $locataire->email)" :error="$errors->first('email')" />
                <x-form.input label="Mobile (référence)" name="mobileref" :value="old('mobileref', $locataire->mobileref)" :error="$errors->first('mobileref')" />
                <x-form.input label="Adresse" name="adresse" :value="old('adresse', $locataire->adresse)" :error="$errors->first('adresse')" class="sm:col-span-2" />
                <x-form.input label="Coordonnées professionnelles" name="coordonne_pro" :value="old('coordonne_pro', $locataire->coordonne_pro)" :error="$errors->first('coordonne_pro')" class="sm:col-span-2" />
            </div>
            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Mettre à jour</button>
                <a href="{{ route('locataires.show', $locataire) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>
</x-layouts.app>
