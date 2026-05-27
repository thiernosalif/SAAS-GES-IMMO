<x-layouts.app title="Générer une situation">

    <div class="flex items-center gap-3 mb-6">
        <a href="{{ route('situations.index') }}" class="text-gray-400 hover:text-gray-700">
            <x-icon name="arrow-down" class="w-5 h-5 rotate-90" />
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Générer une situation propriétaire</h1>
    </div>

    <x-card>
        <form action="{{ route('situations.store') }}" method="POST" class="space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div class="sm:col-span-2">
                    <label for="proprietaire_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Propriétaire <span class="text-red-500">*</span>
                    </label>
                    <select id="proprietaire_id" name="proprietaire_id"
                        class="input-field bg-white w-full {{ $errors->has('proprietaire_id') ? 'border-red-400' : '' }}">
                        <option value="">— Sélectionner un propriétaire —</option>
                        @foreach($proprietaires as $p)
                            <option value="{{ $p->id }}" {{ old('proprietaire_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->full_name }}
                                @if($p->telephone) — {{ $p->telephone }} @endif
                            </option>
                        @endforeach
                    </select>
                    @error('proprietaire_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                <x-form.input
                    label="Période début"
                    name="periode_debut"
                    type="date"
                    :value="old('periode_debut', now()->startOfMonth()->format('Y-m-d'))"
                    required
                    :error="$errors->first('periode_debut')" />

                <x-form.input
                    label="Période fin"
                    name="periode_fin"
                    type="date"
                    :value="old('periode_fin', now()->endOfMonth()->format('Y-m-d'))"
                    required
                    :error="$errors->first('periode_fin')" />

                <x-form.select
                    label="Type de situation"
                    name="type"
                    required
                    :options="['mensuelle' => 'Mensuelle', 'annuelle' => 'Annuelle']"
                    :error="$errors->first('type')" />
            </div>

            <x-alert type="info" message="Le système calculera automatiquement les loyers perçus, la commission et le net à reverser pour tous les contrats actifs du propriétaire sur la période sélectionnée." />

            <div class="flex items-center gap-3 pt-2">
                <button type="submit" class="btn-primary">Générer la situation</button>
                <a href="{{ route('situations.index') }}" class="btn-secondary">Annuler</a>
            </div>
        </form>
    </x-card>

</x-layouts.app>
