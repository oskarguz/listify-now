@props([
    'type' => 'button',
    'variant' => 'primary',
    'textColor' => 'white',
])

@php
    $colors = [
        'primary' => 'bg-contrast ring-white hover:ring-orange-400',
        'secondary' => 'bg-background-secondary ring ring-white hover:ring-orange-400',
        'green' => 'bg-green-600 ring ring-green-400 hover:ring-green-500',
        'transparent' => '',
        'login' => 'bg-orange-400 ring ring-white hover:ring-background-secondary hover:text-background-secondary',
    ];
@endphp

@if ($type === 'link')
    <a {{ $attributes->merge(['class' => "$colors[$variant] text-$textColor font-bold py-2 px-4 cursor-pointer"]) }}>
        {{ $slot }}
    </a>
@else
    <button {{ $attributes->merge(['class' => "$colors[$variant] text-$textColor font-bold py-2 px-4 cursor-pointer"]) }}>
        {{ $slot }}
    </button>
@endif
