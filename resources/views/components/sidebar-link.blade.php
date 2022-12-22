@props(['active'])

@php
$classes = ($active ?? false)
? 'flex items-center w-full h-10 pl-4 text-white bg-indigo-600 rounded-lg cursor-pointer mb-1'
: 'flex items-center w-full h-10 pl-4 text-indigo-500 rounded-lg cursor-pointer hover:bg-indigo-600 hover:text-white mb-1';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
