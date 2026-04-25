@props(['title', 'icon', 'id'])

<div id="{{ $id }}" {{ $attributes->merge(['class' => 'bg-Fondo p-8 rounded-3xl flex flex-col items-center justify-center text-center  
    shadow-[1px_2px_1px_rgba(0,0,0,0.3)] 
    border border-gray-400 ]']) }}>
    
    <div class="mb-4 text-gray-700">
        <x-dynamic-component :component="'lucide-' . $icon" class="w-16 h-16 stroke-[1.2]" />
    </div>

    <h3 class="text-4xl font-bold pb-5 text-gray-900 mb-1 tracking-tight" >
        —
    </h3>

    <p class="text-gray-500 font-medium text-xl">
        {{ $title }}
    </p>
</div>