@props([
    'title' => '',
    'value' => '',
    'icon' => 'bx-trending-up',
    'iconColor' => 'indigo', // indigo, sky, emerald, amber, rose, purple
    'subtext' => null,
    'trend' => null,
    'trendUp' => true,
])

@php
    $iconBg = match($iconColor) {
        'sky' => 'bg-sky-50 dark:bg-sky-950/60 text-sky-600 dark:text-sky-400',
        'emerald' => 'bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400',
        'amber' => 'bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400',
        'rose' => 'bg-rose-50 dark:bg-rose-950/60 text-rose-600 dark:text-rose-400',
        'purple' => 'bg-purple-50 dark:bg-purple-950/60 text-purple-600 dark:text-purple-400',
        default => 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400',
    };
@endphp

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-800/90 p-5 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs hover:shadow-md transition-all']) }}>
    <div class="flex items-center justify-between">
        <span class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">{{ $title }}</span>
        <div class="w-9 h-9 rounded-xl {{ $iconBg }} flex items-center justify-center">
            <i class="bx {{ $icon }} text-lg"></i>
        </div>
    </div>
    <div class="mt-3">
        <div class="text-2xl font-bold text-slate-900 dark:text-white tracking-tight">{{ $value }}</div>
        @if($trend || $subtext)
            <div class="flex items-center gap-1.5 mt-1 text-xs">
                @if($trend)
                    <span class="font-medium {{ $trendUp ? 'text-emerald-600 dark:text-emerald-400' : 'text-rose-600 dark:text-rose-400' }}">
                        {{ $trendUp ? '↑' : '↓' }} {{ $trend }}
                    </span>
                @endif
                @if($subtext)
                    <span class="text-slate-400 dark:text-slate-500 font-normal">{{ $subtext }}</span>
                @endif
            </div>
        @endif
    </div>
</div>
