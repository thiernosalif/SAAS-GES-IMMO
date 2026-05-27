<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'SGI Immo') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">

<div class="flex h-screen overflow-hidden">

    {{-- Sidebar --}}
    <x-sidebar />

    {{-- Main content --}}
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        {{-- Topbar --}}
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
            <div class="flex items-center gap-3">
                @isset($breadcrumb)
                    {{ $breadcrumb }}
                @else
                    <h1 class="text-lg font-semibold text-gray-800">{{ $title ?? 'Dashboard' }}</h1>
                @endisset
            </div>
            <div class="flex items-center gap-4">
                {{-- Agency badge --}}
                @if(app()->bound('current_agency'))
                    <span class="text-sm text-gray-500">
                        {{ app('current_agency')->name }}
                        @if(auth()->user()->zone)
                            — <span class="font-medium text-primary-800">{{ auth()->user()->zone->nom }}</span>
                        @endif
                    </span>
                @endif

                {{-- User menu --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900">
                        <div class="w-8 h-8 rounded-full bg-primary-800 text-white flex items-center justify-center text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->prenom, 0, 1)) }}{{ strtoupper(substr(auth()->user()->nom, 0, 1)) }}
                        </div>
                        <span class="hidden md:block font-medium">{{ auth()->user()->prenom }} {{ auth()->user()->nom }}</span>
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-cloak
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 z-50 py-1">
                        <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Mon profil</a>
                        <hr class="my-1 border-gray-100">
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
