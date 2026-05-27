<x-layouts.app title="Situations propriétaires">

    <div class="flex items-center justify-between mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Situations propriétaires</h1>
        <a href="{{ route('situations.create') }}" class="btn-primary inline-flex items-center gap-1">
            <x-icon name="plus" class="w-4 h-4" /> Générer une situation
        </a>
    </div>

    @if(session('success'))
        <x-alert type="success" :message="session('success')" class="mb-4" />
    @endif

    @livewire('situations.situation-table')

</x-layouts.app>
