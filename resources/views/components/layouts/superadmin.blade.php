<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Super Admin' }} — SGI Immo</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar Super Admin --}}
    <aside class="w-60 bg-[#111827] flex flex-col shrink-0">
        <div class="h-16 flex items-center px-5 border-b border-white/10">
            <div>
                <span class="text-white font-bold text-base tracking-tight">SGI Immo</span>
                <span class="ml-2 text-xs bg-amber-500 text-white px-1.5 py-0.5 rounded font-semibold">ADMIN</span>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto">
            <a href="{{ route('superadmin.dashboard') }}"
               class="sidebar-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                <x-icon name="home" class="w-5 h-5 shrink-0" /> Dashboard
            </a>
            <hr class="border-white/10 my-2">
            <a href="{{ route('superadmin.agencies.index') }}"
               class="sidebar-link {{ request()->routeIs('superadmin.agencies.*') ? 'active' : '' }}">
                <x-icon name="building" class="w-5 h-5 shrink-0" /> Agences
            </a>
            <a href="{{ route('superadmin.users.index') }}"
               class="sidebar-link {{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}">
                <x-icon name="users" class="w-5 h-5 shrink-0" /> Utilisateurs
            </a>
            <hr class="border-white/10 my-2">
            <a href="{{ route('dashboard') }}" class="sidebar-link">
                <x-icon name="arrow-down" class="w-5 h-5 shrink-0 rotate-90" /> Vue agence
            </a>
        </nav>

        <div class="px-4 py-3 border-t border-white/10">
            <p class="text-xs text-white/40">v1.0.0 — Super Admin</p>
        </div>
    </aside>

    {{-- Main content --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
            <div>
                @isset($breadcrumb)
                    {{ $breadcrumb }}
                @else
                    <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Super Admin' }}</h1>
                @endisset
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-amber-600 font-medium">Super Administrateur</span>
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900">
                        <div class="w-8 h-8 rounded-full bg-amber-500 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                        </div>
                        <span class="hidden md:block font-medium">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-50 py-1">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Déconnexion</button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Page content --}}
        <main class="flex-1 overflow-y-auto p-6">
            @if(session('success'))
                <x-alert type="success" :message="session('success')" class="mb-4" />
            @endif
            @if(session('error'))
                <x-alert type="error" :message="session('error')" class="mb-4" />
            @endif

            {{ $slot }}
        </main>
    </div>
</div>

@livewireScripts
</body>
</html>
