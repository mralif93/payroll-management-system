@props([
    'title' => '',
    'value' => '',
    'icon' => 'bx-trending-up',
    'iconColor' => null,
    'color' => 'indigo',
    'subtext' => null,
    'change' => null,
    'trend' => null,
    'trendUp' => true,
    'changeType' => 'positive', // positive, negative, neutral
])

@php
    $selectedColor = $iconColor ?? $color ?? 'indigo';
    $iconBg = match($selectedColor) {
        'sky', 'blue' => 'bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400',
        'emerald', 'green' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400',
        'amber', 'yellow' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400',
        'rose', 'red' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400',
        'purple' => 'bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400',
        default => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400',
    };

    $displaySubtext = $change ?? $trend ?? $subtext;
    $changeColor = match($changeType) {
        'negative' => 'text-rose-600 dark:text-rose-400',
        'neutral' => 'text-slate-500 dark:text-slate-400',
        default => 'text-emerald-600 dark:text-emerald-400',
    };
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs hover:shadow-md transition-all']) }}>
    <div class="flex items-center justify-between gap-2">
        <span class="text-[11px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider truncate">{{ $title }}</span>
        <div class="w-9 h-9 rounded-xl {{ $iconBg }} flex items-center justify-center shrink-0 shadow-xs">
            <i class="bx {{ $icon }} text-lg"></i>
        </div>
    </div>
    <div class="mt-3">
        <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $value }}</div>
        @if($displaySubtext)
            <div class="flex items-center gap-1.5 mt-1.5 text-xs font-medium {{ $changeColor }} truncate">
                <span>{{ $displaySubtext }}</span>
            </div>
        @endif
    </div>
</div>
