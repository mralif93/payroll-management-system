@props([
    'id' => 'theme-toggle-btn',
])

<!-- Clean Animated Toggle for Dark / Light Mode -->
<button 
    id="{{ $id }}"
    type="button" 
    onclick="toggleDarkMode()" 
    {{ $attributes->merge([
        'class' => 'relative inline-flex items-center w-12 h-6.5 rounded-full p-0.5 bg-slate-200 dark:bg-slate-700 border border-slate-300 dark:border-slate-600 transition-colors duration-300 ease-in-out cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-500/40 select-none shadow-inner shrink-0'
    ]) }}
    title="Toggle Light / Dark Mode"
    aria-label="Toggle Light and Dark Mode"
>
    <!-- Background Track with Static Sun and Moon Icons -->
    <div class="w-full flex items-center justify-between px-1.5 pointer-events-none">
        <i class="bx bx-sun text-[11px] text-amber-500"></i>
        <i class="bx bx-moon text-[11px] text-indigo-400"></i>
    </div>

    <!-- Sliding Knob (Left for Light, Right for Dark) -->
    <div class="absolute left-0.5 top-0.5 w-5.5 h-5.5 rounded-full bg-white dark:bg-slate-900 shadow-sm border border-slate-200 dark:border-slate-800 transform transition-transform duration-300 ease-in-out dark:translate-x-5.5 pointer-events-none flex items-center justify-center">
        <!-- Clean minimal center indicator dot -->
        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 dark:bg-indigo-400 transition-colors"></span>
    </div>
</button>
