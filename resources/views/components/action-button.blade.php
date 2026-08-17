@props([
    'variant' => 'indigo', // indigo, sky/blue, emerald/green, amber/yellow, rose/danger, slate/secondary, purple
    'icon' => 'bx-show',
    'size' => 'md', // sm, md, lg
    'title' => null, // Optional tooltip label
    'type' => 'button',
    'href' => null,
])

@php
    $sizeConfig = match($size) {
        'sm' => 'w-7 h-7 text-sm',
        'lg' => 'w-9 h-9 text-lg',
        default => 'w-8 h-8 text-base',
    };

    $colorConfig = match($variant) {
        'sky', 'blue' => 'bg-sky-50 dark:bg-sky-950/70 text-sky-600 dark:text-sky-400 hover:bg-sky-600 hover:text-white dark:hover:bg-sky-600 dark:hover:text-white',
        'emerald', 'green', 'success' => 'bg-emerald-50 dark:bg-emerald-950/70 text-emerald-600 dark:text-emerald-400 hover:bg-emerald-600 hover:text-white dark:hover:bg-emerald-600 dark:hover:text-white',
        'amber', 'yellow', 'warning' => 'bg-amber-50 dark:bg-amber-950/70 text-amber-600 dark:text-amber-400 hover:bg-amber-600 hover:text-white dark:hover:bg-amber-600 dark:hover:text-white',
        'rose', 'danger', 'red' => 'bg-rose-50 dark:bg-rose-950/70 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 dark:hover:text-white',
        'purple' => 'bg-purple-50 dark:bg-purple-950/70 text-purple-600 dark:text-purple-400 hover:bg-purple-600 hover:text-white dark:hover:bg-purple-600 dark:hover:text-white',
        'slate', 'secondary' => 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-700 hover:text-white dark:hover:bg-slate-700',
        default => 'bg-indigo-50 dark:bg-indigo-950/70 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 dark:hover:text-white',
    };

    $btnClasses = "inline-flex items-center justify-center rounded-full transition-all duration-200 cursor-pointer shadow-2xs hover:shadow-xs active:scale-90 {$sizeConfig} {$colorConfig}";
    $tooltip = $title ?? (trim($slot->toHtml()) !== '' ? trim($slot->toHtml()) : null);
@endphp

@if($href)
    <a href="{{ $href }}" @if($tooltip) title="{{ $tooltip }}" @endif {{ $attributes->merge(['class' => $btnClasses]) }}>
        <i class="bx {{ $icon }}"></i>
    </a>
@else
    <button type="{{ $type }}" @if($tooltip) title="{{ $tooltip }}" @endif {{ $attributes->merge(['class' => $btnClasses]) }}>
        <i class="bx {{ $icon }}"></i>
    </button>
@endif

