@props(['name', 'title' => '', 'maxWidth' => 'lg'])

@php
    $widths = [
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'lg' => 'max-w-lg',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
    ];
    $maxWidthClass = $widths[$maxWidth] ?? $widths['lg'];
@endphp

<div
    x-data="{ show: false }"
    x-on:open-modal.window="$event.detail == '{{ $name }}' ? show = true : null"
    x-on:close-modal.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    x-cloak
>
    <div x-show="show" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-black/40" @click="show = false"></div>

    <div x-show="show" x-transition:enter="ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-150" x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="relative bg-white rounded-xl shadow-xl w-full {{ $maxWidthClass }} z-10">

        @if($title)
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">{{ $title }}</h3>
                <button @click="show = false" class="text-gray-400 hover:text-gray-600">
                    <x-icon name="x-mark" class="w-5 h-5" />
                </button>
            </div>
        @endif

        <div class="p-6">{{ $slot }}</div>
    </div>
</div>
