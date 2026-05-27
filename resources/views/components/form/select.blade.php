@props([
    'label' => '',
    'name' => '',
    'options' => [],
    'placeholder' => '— Choisir —',
    'required' => false,
    'error' => '',
])

<div {{ $attributes->only('class') }}>
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
        </label>
    @endif
    <select
        id="{{ $name }}"
        name="{{ $name }}"
        {{ $attributes->except('class')->merge(['class' => 'input-field bg-white ' . ($error ? 'border-red-400' : '')]) }}
    >
        @if($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        @foreach($options as $value => $text)
            <option value="{{ $value }}" {{ old($name) == $value ? 'selected' : '' }}>{{ $text }}</option>
        @endforeach
        {{ $slot }}
    </select>
    @if($error)
        <p class="mt-1 text-xs text-red-600">{{ $error }}</p>
    @endif
</div>
