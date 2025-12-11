@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'rounded-md bg-gray-900 px-3 py-2 text-sm font-medium text-white dark:bg-gray-950/50 transition duration-150 ease-in-out'
            : 'rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-white/5 hover:text-white transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
