@props([
    'variant' => 'primary', // primary, secondary, dark, success, warning, danger, ghost
    'size' => 'md',        // sm, md, lg
    'icon' => null,        // boxicon class e.g. 'bx-plus'
    'iconPosition' => 'left', // left, right
    'type' => 'button',
    'href' => null,
])

@php
    $baseClasses = 'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-200 active:scale-95 cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none disabled:cursor-not-allowed';

    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs gap-1.5',
        'lg' => 'px-6 py-3 text-sm gap-2',
        default => 'px-4 py-2 text-xs gap-2',
    };

    $variantClasses = match($variant) {
        'secondary' => 'bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-200 shadow-xs hover:bg-slate-50 dark:hover:bg-slate-700 focus:ring-slate-400 dark:focus:ring-slate-600',
        'dark' => 'bg-slate-900 dark:bg-white text-white dark:text-slate-900 shadow-sm hover:bg-slate-800 dark:hover:bg-slate-100 focus:ring-slate-900 dark:focus:ring-white',
        'success' => 'bg-emerald-600 dark:bg-emerald-500 text-white shadow-sm hover:bg-emerald-700 dark:hover:bg-emerald-600 focus:ring-emerald-500',
        'warning' => 'bg-amber-500 dark:bg-amber-600 text-white shadow-sm hover:bg-amber-600 dark:hover:bg-amber-700 focus:ring-amber-500',
        'danger' => 'bg-rose-600 dark:bg-rose-500 text-white shadow-sm hover:bg-rose-700 dark:hover:bg-rose-600 focus:ring-rose-500',
        'ghost' => 'text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 focus:ring-slate-300 dark:focus:ring-slate-700',
        default => 'bg-indigo-600 dark:bg-indigo-500 text-white shadow-sm shadow-indigo-500/20 hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:ring-indigo-500',
    };

    $classes = "{$baseClasses} {$sizeClasses} {$variantClasses}";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="bx {{ $icon }} text-base"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="bx {{ $icon }} text-base"></i>
        @endif
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="bx {{ $icon }} text-base"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="bx {{ $icon }} text-base"></i>
        @endif
    </button>
@endif
