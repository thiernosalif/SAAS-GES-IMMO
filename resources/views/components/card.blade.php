@props(['title' => null, 'class' => ''])

<div {{ $attributes->class(['bg-white rounded-xl border border-gray-200 shadow-sm', $class]) }}>
    @if($title || isset($actions))
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            @if($title)
                <h3 class="font-semibold text-gray-800 text-sm">{{ $title }}</h3>
            @endif
            @isset($actions)
                <div class="flex items-center gap-2">{{ $actions }}</div>
            @endisset
        </div>
    @endif
    <div class="p-6">
        {{ $slot }}
    </div>
</div>
