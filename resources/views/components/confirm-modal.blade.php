@props([
    'id' => 'confirm-modal',
    'title' => 'Confirm Action',
    'message' => 'Are you sure you want to proceed with this operation? This action cannot be undone.',
    'confirmText' => 'Confirm & Proceed',
    'cancelText' => 'Cancel',
    'confirmVariant' => 'danger', // danger, primary, success, warning
    'icon' => 'bx-error-circle',
    'iconBg' => 'bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400',
])

@php
    $btnVariant = match($confirmVariant) {
        'primary' => 'primary',
        'success' => 'success',
        'warning' => 'warning',
        default => 'danger',
    };
@endphp

<div id="{{ $id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="{{ $id }}-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/70 backdrop-blur-xs transition-opacity" onclick="closeModal('{{ $id }}')"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left shadow-2xl transition-all sm:my-8 w-full max-w-md border border-slate-200 dark:border-slate-800 animate__animated animate__zoomIn animate__faster">
            
            <div class="p-6 text-center space-y-4">
                <!-- Icon Badge -->
                <div class="w-14 h-14 mx-auto rounded-2xl {{ $iconBg }} flex items-center justify-center text-3xl font-bold shadow-xs">
                    <i class="bx {{ $icon }}" id="{{ $id }}-icon"></i>
                </div>

                <!-- Title & Message -->
                <div class="space-y-1.5">
                    <h3 class="text-base font-extrabold text-slate-900 dark:text-white" id="{{ $id }}-title">{{ $title }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed" id="{{ $id }}-message">{{ $message }}</p>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="bg-slate-50/80 dark:bg-slate-850 px-6 py-3.5 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('{{ $id }}')">
                    {{ $cancelText }}
                </x-button>
                <form id="{{ $id }}-form" method="POST" action="" class="inline">
                    @csrf
                    <input type="hidden" name="_method" id="{{ $id }}-method" value="POST">
                    <x-button variant="{{ $btnVariant }}" size="sm" type="submit" id="{{ $id }}-btn">
                        {{ $confirmText }}
                    </x-button>
                </form>
            </div>
        </div>
    </div>
</div>
