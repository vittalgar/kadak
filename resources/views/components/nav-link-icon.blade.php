@props(['active'])

@php
    $classes =
        $active ?? false
            ? 'bg-primary/10 text-primary group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors'
            : 'text-fg-soft hover:bg-bkg-soft hover:text-fg-alt group flex items-center px-2 py-2 text-sm font-medium rounded-md transition-colors';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }} wire:navigate>
    @if (isset($icon))
        <div class="mr-3 h-6 w-6 flex-shrink-0 text-fg-soft group-hover:text-fg-alt">
            {{ $icon }}
        </div>
    @endif
    {{ $slot }}
</a>
