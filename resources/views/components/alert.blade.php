@props(['type'=>'success'])

<div class="absolute z-50 max-w-lg top-2 right-2">
    <alert type="{{ $type }}">
        {{ $slot }}
    </alert>
</div>
