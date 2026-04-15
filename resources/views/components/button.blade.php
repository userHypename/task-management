@props(['type' => 'button', 'variant' => 'primary'])

@php
    $classes = 'btn ' . ($variant === 'primary' ? 'btn-primary' : 'btn-ghost');
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</button>
