@props([
    'id' => null,
    'label' => null,
    'type' => 'text',
    'placeholder' => '',
    'helper' => null,
    'error' => null,
    'icon' => null,
    'prefix' => null,
])

@php
    $inputId = $id ?? 'input_' . Str::random(8);
@endphp

<div class="space-y-1.5">
    @if($label)
        <label for="{{ $inputId }}" class="block text-xs font-semibold {{ $error ? 'text-rose-700 dark:text-rose-400' : 'text-slate-700 dark:text-slate-300' }}">
            {{ $label }}
        </label>
    @endif

    <div class="relative rounded-lg">
        @if($icon)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500">
                <i class="bx {{ $icon }} text-base"></i>
            </div>
        @elseif($prefix)
            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                <span class="text-xs font-bold text-slate-400 dark:text-slate-500">{{ $prefix }}</span>
            </div>
        @endif

        <input 
            id="{{ $inputId }}"
            type="{{ $type }}"
            placeholder="{{ $placeholder }}"
            {{ $attributes->merge([
                'class' => 'w-full rounded-xl border text-xs shadow-xs focus:ring-2 focus:ring-indigo-500/20 transition ' . 
                ($icon ? 'pl-9 ' : ($prefix ? 'pl-14 ' : 'px-3.5 ')) . 
                'py-2.5 ' . 
                ($error 
                    ? 'border-rose-300 dark:border-rose-800 bg-rose-50/30 dark:bg-rose-950/30 text-rose-900 dark:text-rose-200 focus:border-rose-500' 
                    : 'border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white focus:border-indigo-500 dark:focus:border-indigo-400')
            ]) }}
        >
    </div>

    @if($error)
        <p class="text-[11px] text-rose-600 dark:text-rose-400 font-medium">{{ $error }}</p>
    @elseif($helper)
        <p class="text-[11px] text-slate-400 dark:text-slate-500">{{ $helper }}</p>
    @endif
</div>
