@props(['type' => 'primary', 'text'])

@php
    $styles = [
        'primary' => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        'success' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        'danger' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
        'warning' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
        'info' => 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
    ];
@endphp

<span {{ $attributes->merge(['class' => 'inline-flex items-center px-3 py-1 rounded-full text-sm font-medium ' . ($styles[$type] ?? $styles['primary'])]) }}>
    {{ $text ?? $slot }}
</span>
