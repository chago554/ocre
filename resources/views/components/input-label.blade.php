@props(['value'])

<label {{ $attributes->merge(['class' => 'label pt-0']) }}>
    <span class="label-text font-light text-black">
        {{ $value ?? $slot }}
    </span>
</label>