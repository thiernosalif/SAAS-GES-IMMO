<x-layouts.app :title="'Modifier — ' . $proprietaire->full_name">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('proprietaires.show', $proprietaire) }}" class="text-gray-400 hover:text-gray-700">
            <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Modifier : {{ $proprietaire->full_name }}</h1>
    </div>

    <x-card>
        <form action="{{ route('proprietaires.update', $proprietaire) }}" method="POST" class="space-y-5">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-form.input label="Prénom" name="prenom" :value="old('prenom', $proprietaire->prenom)" required
                    :error="$errors->first('prenom')" />
                <x-form.input label="Nom" name="nom" :value="old('nom', $proprietaire->nom)" required
                    :error="$errors->first('nom')" />
                <x-form.input label="CIN" name="cin" :value="old('cin', $proprietaire->cin)"
                    :error="$errors->first('cin')" />
                <x-form.input label="Téléphone" name="telephone" :value="old('telephone', $proprietaire->telephone)"
                    :error="$errors->first('telephone')" />
                <x-form.input label="Email" name="email" type="email" :value="old('email', $proprietaire->email)"
                    :error="$errors->first('email')" />
                <x-form.input label="Adresse" name="adresse" :value="old('adresse', $proprietaire->adresse)"
                    :error="$errors->first('adresse')" />
                <x-form.input label="Début mandat" name="date_deb_mandat" type="date"
                    :value="old('date_deb_mandat', $proprietaire->date_deb_mandat?->format('Y-m-d'))"
                    :error="$errors->first('date_deb_mandat')" />
                <x-form.input label="Fin mandat" name="date_fin_mandat" type="date"
                    :value="old('date_fin_mandat', $proprietaire->date_fin_mandat?->format('Y-m-d'))"
                    :error="$errors->first('date_fin_mandat')" />
                <x-form.input label="Taux commission (%)" name="taux_commission_specifique" type="number" step="0.01"
                    :value="old('taux_commission_specifique', $proprietaire->taux_commission_specifique)"
                    hint="Laisser vide pour utiliser le taux de l'agence"
                    :error="$errors->first('taux_commission_specifique')" />
            </div>

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Mettre à jour</button>
                <a href="{{ route('proprietaires.show', $proprietaire) }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>

</x-layouts.app>
