@props([
    'label' => '',
    'name' => '',
    'required' => false,
    'error' => '',
])

<div {{ $attributes->only('class') }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <input
        type="date"
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->except('class')->merge(['class' => 'input-field ' . ($error ? 'border-red-400' : '')]) }}
    />
    @if($error)
        <p class="mt-1 text-xs text-red-600">{{ $error }}</p>
    @endif
</div>
