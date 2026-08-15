@props([
    'title' => null,
    'subtitle' => null,
    'tag' => null,
    'footer' => false,
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden']) }}>
    @if($title || $tag)
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between bg-white">
            <div>
                @if($title)
                    <h3 class="text-sm font-bold text-slate-900">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="text-xs text-slate-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if($tag)
                <span class="px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-200">{{ $tag }}</span>
            @endif
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if(isset($footerSlot))
        <div class="px-6 py-3.5 bg-slate-50 border-t border-slate-200">
            {{ $footerSlot }}
        </div>
    @endif
</div>
