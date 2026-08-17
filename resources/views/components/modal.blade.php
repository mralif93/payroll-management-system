@props([
    'id' => 'modal',
    'title' => 'Modal Title',
    'subtitle' => null,
    'icon' => null,
    'iconBg' => 'bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400',
    'maxWidth' => null,
    'size' => 'lg', // sm, md, lg, xl, 2xl
])

@php
    $widthClass = $maxWidth ?? match($size) {
        'sm' => 'max-w-sm',
        'md' => 'max-w-md',
        'xl' => 'max-w-xl',
        '2xl' => 'max-w-2xl',
        default => 'max-w-lg',
    };
@endphp

<div id="{{ $id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="{{ $id }}-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs transition-opacity" onclick="closeModal('{{ $id }}')"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 w-full {{ $widthClass }} border border-slate-200 dark:border-slate-800 animate__animated animate__zoomIn animate__faster">
            <div class="px-6 py-4.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($icon)
                        <div class="w-9 h-9 rounded-xl {{ $iconBg }} flex items-center justify-center font-bold shadow-xs shrink-0">
                            <i class="bx {{ $icon }} text-lg"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white" id="{{ $id }}-title">{{ $title }}</h3>
                        @if($subtitle)
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>
                <button type="button" onclick="closeModal('{{ $id }}')" class="p-1 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <div class="p-6">
                {{ $slot }}
            </div>

            @if(isset($footerSlot))
                <div class="bg-slate-50/80 dark:bg-slate-850 px-6 py-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2">
                    {{ $footerSlot }}
                </div>
            @endif
        </div>
    </div>
</div>
