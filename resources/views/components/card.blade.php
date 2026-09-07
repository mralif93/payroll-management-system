@props([
    'title' => null,
    'subtitle' => null,
    'tag' => null,
    'footer' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-colors']) }}>
    @if($title || $tag)
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-white dark:bg-slate-900">
            <div>
                @if($title)
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if($tag)
                <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">{{ $tag }}</span>
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if(isset($footerSlot))
        <div class="px-6 py-3.5 bg-slate-50/80 dark:bg-slate-850 border-t border-slate-100 dark:border-slate-800">
            {{ $footerSlot }}
        </div>
    @endif
</div>

