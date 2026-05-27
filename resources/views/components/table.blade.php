@props(['class' => ''])

<div {{ $attributes->class(['overflow-x-auto rounded-lg border border-gray-200', $class]) }}>
    <table class="w-full text-sm text-left">
        @isset($head)
            <thead class="bg-gray-50 border-b border-gray-200">
                <tr>{{ $head }}</tr>
            </thead>
        @endisset
        <tbody class="divide-y divide-gray-100">
            {{ $slot }}
        </tbody>
    </table>
</div>
