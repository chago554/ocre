<button {{ $attributes->merge([
    'type' => 'submit', 
    'class' => 'btn bg-primary hover:bg-primary/90 w-full text-Blanco font-bold text-lg rounded-xl shadow-md border-none transition ease-in-out duration-150'
]) }}>
    {{ $slot }}
</button>