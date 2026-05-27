@props(['type' => 'default', 'label' => ''])

@php
    $styles = [
        'actif'     => 'bg-green-100 text-green-800',
        'inactif'   => 'bg-gray-100 text-gray-600',
        'expire'    => 'bg-red-100 text-red-700',
        'resilie'   => 'bg-gray-100 text-gray-500',
        'retard'    => 'bg-red-100 text-red-700',
        'paye'      => 'bg-green-100 text-green-800',
        'partiel'   => 'bg-yellow-100 text-yellow-800',
        'avance'    => 'bg-blue-100 text-blue-800',
        'complet'   => 'bg-green-100 text-green-800',
        'ouverte'   => 'bg-red-100 text-red-700',
        'en_cours'  => 'bg-yellow-100 text-yellow-800',
        'resolue'   => 'bg-green-100 text-green-800',
        'brouillon' => 'bg-gray-100 text-gray-600',
        'validee'   => 'bg-green-100 text-green-800',
        'envoyee'   => 'bg-blue-100 text-blue-800',
        'default'   => 'bg-gray-100 text-gray-600',
    ];
    $style = $styles[$type] ?? $styles['default'];
@endphp

<span {{ $attributes->class(["inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium", $style]) }}>
    {{ $label ?: $slot }}
</span>
