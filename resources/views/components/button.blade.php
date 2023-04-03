@props([
    'type' => 'button',
    'variant' => 'primary',
    'textColor' => 'white',
])

@php
    $colors = [
        'primary' => 'bg-blue-500 hover:bg-blue-700',
        'secondary' => 'bg-gray-500 hover:bg-gray-700',
        'green' => 'bg-green-600 hover:bg-green-700',
        'transparent' => '',
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
