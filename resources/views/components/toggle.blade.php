@props([
    'id' => null,
    'name' => null,
    'label' => null,
    'description' => null,
    'checked' => false,
    'disabled' => false,
    'size' => 'md', // sm, md, lg
    'color' => 'indigo', // indigo, emerald, sky, purple, amber, rose
])

@php
    $inputId = $id ?? ($name ?? 'toggle_' . Str::random(8));

    // Size variants for Track
    $switchTrackSize = match($size) {
        'sm' => 'w-9 h-5',
        'lg' => 'w-14 h-8',
        default => 'w-11 h-6',
    };

    // Thumb size and exact translation distance for right slide
    $dotSize = match($size) {
        'sm' => 'w-4 h-4 peer-checked:translate-x-4',
        'lg' => 'w-7 h-7 peer-checked:translate-x-6',
        default => 'w-5 h-5 peer-checked:translate-x-5',
    };

    // Color variants for checked track
    $colorClass = match($color) {
        'emerald' => 'peer-checked:bg-emerald-600 dark:peer-checked:bg-emerald-500 peer-focus:ring-emerald-500/30',
        'sky' => 'peer-checked:bg-sky-600 dark:peer-checked:bg-sky-500 peer-focus:ring-sky-500/30',
        'purple' => 'peer-checked:bg-purple-600 dark:peer-checked:bg-purple-500 peer-focus:ring-purple-500/30',
        'amber' => 'peer-checked:bg-amber-500 dark:peer-checked:bg-amber-600 peer-focus:ring-amber-500/30',
        'rose' => 'peer-checked:bg-rose-600 dark:peer-checked:bg-rose-500 peer-focus:ring-rose-500/30',
        default => 'peer-checked:bg-indigo-600 dark:peer-checked:bg-indigo-500 peer-focus:ring-indigo-500/30',
    };
@endphp

<label for="{{ $inputId }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-between gap-3 ' . ($disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer select-none group')]) }}>
    @if($label || $description)
        <div class="flex flex-col">
            @if($label)
                <span class="text-xs font-semibold text-slate-800 dark:text-slate-200 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">{{ $label }}</span>
            @endif
            @if($description)
                <span class="text-[11px] text-slate-500 dark:text-slate-400 leading-tight">{{ $description }}</span>
            @endif
        </div>
    @endif

    <div class="relative inline-flex items-center shrink-0">
        <input 
            type="checkbox" 
            id="{{ $inputId }}"
            @if($name) name="{{ $name }}" @endif
            @checked($checked)
            @disabled($disabled)
            class="sr-only peer"
        >
        
        <!-- Switch Track Background (Smooth Color Morphing) -->
        <div class="{{ $switchTrackSize }} rounded-full bg-slate-200 dark:bg-slate-700 peer-focus:outline-none peer-focus:ring-2 {{ $colorClass }} transition-colors duration-300 ease-in-out shadow-inner"></div>
        
        <!-- Animated Switch Thumb / Knob (Sliding Left & Right with Cubic-Bezier Springs) -->
        <div class="absolute left-0.5 top-0.5 {{ $dotSize }} rounded-full bg-white shadow-md ring-0 transform transition-all duration-300 cubic-bezier(0.4, 0, 0.2, 1) peer-active:scale-95 pointer-events-none flex items-center justify-center">
            <!-- Subtle Center Detail Dot -->
            <div class="w-1.5 h-1.5 rounded-full bg-slate-300/60 dark:bg-slate-400/40 peer-checked:bg-indigo-400/50 transition-colors"></div>
        </div>
    </div>
</label>
