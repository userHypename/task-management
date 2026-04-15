@props(['active' => false])

@php
    $base = 'inline-flex items-center px-3 py-2 rounded-md text-sm font-medium';
    $activeClass = $active ? 'bg-white shadow-sm text-text' : 'text-gray-600 hover:text-text';
    $classes = $base . ' ' . $activeClass;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
