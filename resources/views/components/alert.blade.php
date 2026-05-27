@props(['type' => 'success', 'message' => ''])

@php
    $styles = [
        'success' => ['bg' => 'bg-green-50 border-green-200', 'text' => 'text-green-800', 'icon' => 'check'],
        'error'   => ['bg' => 'bg-red-50 border-red-200', 'text' => 'text-red-800', 'icon' => 'x-mark'],
        'warning' => ['bg' => 'bg-yellow-50 border-yellow-200', 'text' => 'text-yellow-800', 'icon' => 'exclamation-triangle'],
        'info'    => ['bg' => 'bg-blue-50 border-blue-200', 'text' => 'text-blue-800', 'icon' => 'information-circle'],
    ];
    $s = $styles[$type] ?? $styles['info'];
@endphp

<div {{ $attributes->class(["flex items-start gap-3 px-4 py-3 rounded-lg border", $s['bg'], $s['text']]) }}>
    <x-icon name="{{ $s['icon'] }}" class="w-5 h-5 mt-0.5 shrink-0" />
    <p class="text-sm">{{ $message ?: $slot }}</p>
</div>
