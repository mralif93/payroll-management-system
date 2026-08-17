@props([
    'variant' => 'indigo', // indigo, sky/blue, emerald/green, amber/yellow, rose/danger, slate/secondary
    'icon' => 'bx-show',
    'size' => 'md', // sm, md
    'type' => 'button',
    'href' => null,
])

@php
    $colorConfig = match($variant) {
        'sky', 'blue' => [
            'btn' => 'border-sky-200 dark:border-sky-800/80 hover:border-sky-300 dark:hover:border-sky-700 bg-sky-50/40 dark:bg-sky-950/30 text-sky-700 dark:text-sky-300 hover:bg-sky-50 dark:hover:bg-sky-950/60',
            'circle' => 'bg-sky-100 dark:bg-sky-900/80 text-sky-600 dark:text-sky-400 group-hover:bg-sky-200 dark:group-hover:bg-sky-800',
        ],
        'emerald', 'green', 'success' => [
            'btn' => 'border-emerald-200 dark:border-emerald-800/80 hover:border-emerald-300 dark:hover:border-emerald-700 bg-emerald-50/40 dark:bg-emerald-950/30 text-emerald-700 dark:text-emerald-300 hover:bg-emerald-50 dark:hover:bg-emerald-950/60',
            'circle' => 'bg-emerald-100 dark:bg-emerald-900/80 text-emerald-600 dark:text-emerald-400 group-hover:bg-emerald-200 dark:group-hover:bg-emerald-800',
        ],
        'amber', 'yellow', 'warning' => [
            'btn' => 'border-amber-200 dark:border-amber-800/80 hover:border-amber-300 dark:hover:border-amber-700 bg-amber-50/40 dark:bg-amber-950/30 text-amber-700 dark:text-amber-300 hover:bg-amber-50 dark:hover:bg-amber-950/60',
            'circle' => 'bg-amber-100 dark:bg-amber-900/80 text-amber-600 dark:text-amber-400 group-hover:bg-amber-200 dark:group-hover:bg-amber-800',
        ],
        'rose', 'danger', 'red' => [
            'btn' => 'border-rose-200 dark:border-rose-800/80 hover:border-rose-300 dark:hover:border-rose-700 bg-rose-50/40 dark:bg-rose-950/30 text-rose-700 dark:text-rose-300 hover:bg-rose-50 dark:hover:bg-rose-950/60',
            'circle' => 'bg-rose-100 dark:bg-rose-900/80 text-rose-600 dark:text-rose-400 group-hover:bg-rose-200 dark:group-hover:bg-rose-800',
        ],
        'purple' => [
            'btn' => 'border-purple-200 dark:border-purple-800/80 hover:border-purple-300 dark:hover:border-purple-700 bg-purple-50/40 dark:bg-purple-950/30 text-purple-700 dark:text-purple-300 hover:bg-purple-50 dark:hover:bg-purple-950/60',
            'circle' => 'bg-purple-100 dark:bg-purple-900/80 text-purple-600 dark:text-purple-400 group-hover:bg-purple-200 dark:group-hover:bg-purple-800',
        ],
        'slate', 'secondary' => [
            'btn' => 'border-slate-200 dark:border-slate-700/80 hover:border-slate-300 dark:hover:border-slate-600 bg-slate-50/50 dark:bg-slate-800/50 text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800',
            'circle' => 'bg-slate-200/70 dark:bg-slate-700 text-slate-600 dark:text-slate-300 group-hover:bg-slate-300 dark:group-hover:bg-slate-600',
        ],
        default => [
            'btn' => 'border-indigo-200 dark:border-indigo-800/80 hover:border-indigo-300 dark:hover:border-indigo-700 bg-indigo-50/40 dark:bg-indigo-950/30 text-indigo-700 dark:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-950/60',
            'circle' => 'bg-indigo-100 dark:bg-indigo-900/80 text-indigo-600 dark:text-indigo-400 group-hover:bg-indigo-200 dark:group-hover:bg-indigo-800',
        ],
    };

    $containerClasses = 'inline-flex items-center gap-1.5 pl-1.5 pr-2.5 py-1 rounded-xl border text-xs font-bold transition-all duration-200 cursor-pointer shadow-2xs hover:shadow-xs active:scale-95 group ' . $colorConfig['btn'];
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $containerClasses]) }}>
        <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs transition-colors shrink-0 {{ $colorConfig['circle'] }}">
            <i class="bx {{ $icon }}"></i>
        </span>
        <span class="tracking-tight leading-none">{{ $slot }}</span>
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $containerClasses]) }}>
        <span class="w-5 h-5 rounded-full flex items-center justify-center text-xs transition-colors shrink-0 {{ $colorConfig['circle'] }}">
            <i class="bx {{ $icon }}"></i>
        </span>
        <span class="tracking-tight leading-none">{{ $slot }}</span>
    </button>
@endif
