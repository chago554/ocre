@props(['active', 'icon'])

@php
    $classes =
        $active ?? false
            ? 'flex items-center gap-4 px-3 py-2 rounded-lg transition-colors duration-200 bg-gray-200 text-gray-900 font-bold'
            : 'flex items-center gap-4 px-3 py-2 rounded-lg transition-colors duration-200 text-gray-500 hover:bg-gray-200 hover:text-gray-700';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    @if (isset($icon))
        <x-dynamic-component :component="'lucide-' . $icon" class="w-6 h-6 shrink-0" />
    @endif
    
    <span x-show="open" x-transition.opacity class="whitespace-nowrap">
        {{ $slot }}
    </span>
</a>
