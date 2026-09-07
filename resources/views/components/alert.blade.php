@props([
    'type' => null,
    'variant' => 'info', // info, success, warning, danger
    'title' => null,
    'icon' => null,
    'dismissible' => false,
])

@php
    $selectedVariant = $type ?? $variant ?? 'info';
    $config = match($selectedVariant) {
        'success' => [
            'bg' => 'bg-emerald-50 dark:bg-emerald-950/40 border-emerald-200 dark:border-emerald-800/60 text-emerald-900 dark:text-emerald-200',
            'icon' => $icon ?? 'bx-check-circle',
            'iconColor' => 'text-emerald-600 dark:text-emerald-400',
        ],
        'warning' => [
            'bg' => 'bg-amber-50 dark:bg-amber-950/40 border-amber-200 dark:border-amber-800/60 text-amber-900 dark:text-amber-200',
            'icon' => $icon ?? 'bx-error-circle',
            'iconColor' => 'text-amber-600 dark:text-amber-400',
        ],
        'danger' => [
            'bg' => 'bg-rose-50 dark:bg-rose-950/40 border-rose-200 dark:border-rose-800/60 text-rose-900 dark:text-rose-200',
            'icon' => $icon ?? 'bx-x-circle',
            'iconColor' => 'text-rose-600 dark:text-rose-400',
        ],
        default => [
            'bg' => 'bg-blue-50 dark:bg-blue-950/40 border-blue-200 dark:border-blue-800/60 text-blue-900 dark:text-blue-200',
            'icon' => $icon ?? 'bx-info-circle',
            'iconColor' => 'text-blue-600 dark:text-blue-400',
        ],
    };
@endphp

<div {{ $attributes->merge(['class' => "relative flex p-4 rounded-xl border {$config['bg']} gap-3 items-start"]) }}>
    <i class="bx {{ $config['icon'] }} text-lg {{ $config['iconColor'] }} shrink-0 mt-0.5"></i>
    <div class="space-y-1 text-xs flex-1">
        @if($title)
            <h4 class="font-bold uppercase tracking-wider">{{ $title }}</h4>
        @endif
        <div class="leading-relaxed">
            {{ $slot }}
        </div>
    </div>
    @if($dismissible)
        <button type="button" onclick="this.closest('div').remove()" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer p-0.5 rounded-lg -mr-1 -mt-1 transition">
            <i class="bx bx-x text-base"></i>
        </button>
    @endif
</div>

