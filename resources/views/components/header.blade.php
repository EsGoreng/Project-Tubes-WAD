@props(['hide'])

@php
    $classes = $hide ?? false ? 'hidden' : '';
@endphp

<header {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</header>
