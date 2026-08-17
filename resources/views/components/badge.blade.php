@props([
    'variant' => 'slate', // slate, indigo, success/emerald, warning/amber, danger/rose, blue, purple
    'size' => 'md', // sm, md
    'dot' => false,
    'pulse' => false,
])

@php
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-[10px] font-semibold',
        default => 'px-2.5 py-0.5 text-xs font-semibold',
    };

    $baseClasses = "inline-flex items-center gap-1.5 rounded-full {$sizeClasses}";

    $variantClasses = match($variant) {
        'indigo' => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800',
        'success', 'emerald' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800',
        'warning', 'amber' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800',
        'danger', 'rose' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800',
        'blue' => 'bg-blue-50 dark:bg-blue-950/60 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800',
        'purple' => 'bg-purple-50 dark:bg-purple-950/60 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800',
        default => 'bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-700',
    };

    $dotColor = match($variant) {
        'indigo' => 'bg-indigo-500',
        'success', 'emerald' => 'bg-emerald-500',
        'warning', 'amber' => 'bg-amber-500',
        'danger', 'rose' => 'bg-rose-500',
        'blue' => 'bg-blue-500',
        'purple' => 'bg-purple-500',
        default => 'bg-slate-500',
    };
@endphp

<span {{ $attributes->merge(['class' => "{$baseClasses} {$variantClasses}"]) }}>
    @if($dot)
        <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }} {{ $pulse ? 'animate-pulse' : '' }}"></span>
    @endif
    {{ $slot }}
</span>
