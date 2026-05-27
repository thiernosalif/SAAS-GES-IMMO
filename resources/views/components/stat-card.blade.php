@props([
    'label' => '',
    'value' => '',
    'icon' => 'chart-bar',
    'variation' => null,
    'variationLabel' => null,
    'color' => 'blue',
])

@php
    $colors = [
        'blue' => 'bg-blue-50 text-blue-600',
        'green' => 'bg-green-50 text-green-600',
        'orange' => 'bg-orange-50 text-orange-600',
        'red' => 'bg-red-50 text-red-600',
        'purple' => 'bg-purple-50 text-purple-600',
    ];
    $iconBg = $colors[$color] ?? $colors['blue'];
@endphp

<div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
    <div class="flex items-start justify-between">
        <div class="flex-1 min-w-0">
            <p class="text-xs font-medium text-gray-500 uppercase tracking-wide truncate">{{ $label }}</p>
            <p class="mt-1 text-2xl font-bold text-gray-900 truncate">{{ $value }}</p>
            @if($variation !== null)
                <p class="mt-1 text-xs {{ $variation >= 0 ? 'text-green-600' : 'text-red-600' }}">
                    {{ $variation >= 0 ? '▲' : '▼' }} {{ abs($variation) }}%
                    @if($variationLabel)
                        <span class="text-gray-400">{{ $variationLabel }}</span>
                    @endif
                </p>
            @endif
        </div>
        <div class="ml-3 w-10 h-10 rounded-lg {{ $iconBg }} flex items-center justify-center shrink-0">
            <x-icon name="{{ $icon }}" class="w-5 h-5" />
        </div>
    </div>
</div>
