@php
    $nav = [
        ['route' => 'dashboard', 'label' => 'Dashboard', 'icon' => 'home'],
        ['divider' => true],
        ['route' => 'proprietaires.index', 'label' => 'Propriétaires', 'icon' => 'users'],
        ['route' => 'biens.index', 'label' => 'Biens', 'icon' => 'building'],
        ['route' => 'locataires.index', 'label' => 'Locataires', 'icon' => 'user-group'],
        ['route' => 'contrats.index', 'label' => 'Contrats', 'icon' => 'document-text'],
        ['divider' => true],
        ['route' => 'paiements.index', 'label' => 'Paiements', 'icon' => 'banknotes'],
        ['divider' => true],
        ['route' => 'situations.index', 'label' => 'Situations', 'icon' => 'chart-bar'],
        ['divider' => true],
        ['route' => 'comptabilite.index', 'label' => 'Comptabilité', 'icon' => 'calculator'],
        ['route' => 'reclamations.index', 'label' => 'Réclamations', 'icon' => 'chat-bubble-left'],
        ['divider' => true],
    ];

    if (auth()->user()?->isAgencyAdmin()) {
        $nav[] = ['route' => 'users.index',    'label' => 'Utilisateurs', 'icon' => 'user-plus'];
        $nav[] = ['route' => 'parametres.edit', 'label' => 'Paramètres',  'icon' => 'cog'];
    }
@endphp

<aside class="w-60 bg-[#1e3a5f] flex flex-col shrink-0">
    {{-- Logo --}}
    <div class="h-16 flex items-center px-5 border-b border-white/10">
        <span class="text-white font-bold text-lg tracking-tight">SGI Immo</span>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
        @foreach($nav as $item)
            @if(isset($item['divider']))
                <hr class="border-white/10 my-2">
            @else
                <a href="{{ route($item['route']) }}"
                   class="sidebar-link {{ request()->routeIs(str_replace('.index', '.*', $item['route'])) ? 'active' : '' }}">
                    <x-icon name="{{ $item['icon'] }}" class="w-5 h-5 shrink-0" />
                    {{ $item['label'] }}
                </a>
            @endif
        @endforeach
    </nav>

    {{-- Footer info --}}
    <div class="px-4 py-3 border-t border-white/10">
        <p class="text-xs text-white/40">v1.0.0 — SGI Immo</p>
    </div>
</aside>
