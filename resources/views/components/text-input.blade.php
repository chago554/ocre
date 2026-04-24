@props(['disabled' => false])

<input 
    @disabled($disabled) 
    {{ $attributes->merge([
        'class' => 'input w-full bg-[#E9EEEA] rounded border-none focus:ring-2 focus:ring-primary/50 text-gray-500'
    ]) }} 
/>