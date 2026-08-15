@props([
    'id' => 'modal',
    'title' => 'Modal Title',
    'subtitle' => null,
    'icon' => null,
    'iconBg' => 'bg-indigo-50 text-indigo-600',
    'maxWidth' => 'max-w-lg', // max-w-md, max-w-lg, max-w-xl, max-w-2xl
])

<div id="{{ $id }}" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="{{ $id }}-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="document.getElementById('{{ $id }}').classList.add('hidden')"></div>

    <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full {{ $maxWidth }} border border-slate-200 animate__animated animate__zoomIn animate__faster">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    @if($icon)
                        <div class="w-9 h-9 rounded-lg {{ $iconBg }} flex items-center justify-center font-bold">
                            <i class="bx {{ $icon }} text-lg"></i>
                        </div>
                    @endif
                    <div>
                        <h3 class="text-sm font-bold text-slate-900" id="{{ $id }}-title">{{ $title }}</h3>
                        @if($subtitle)
                            <p class="text-xs text-slate-500">{{ $subtitle }}</p>
                        @endif
                    </div>
                </div>
                <button type="button" onclick="document.getElementById('{{ $id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                    <i class="bx bx-x text-xl"></i>
                </button>
            </div>

            <div class="p-6">
                {{ $slot }}
            </div>

            @if(isset($footerSlot))
                <div class="bg-slate-50 px-6 py-3.5 border-t border-slate-200 flex items-center justify-end gap-2">
                    {{ $footerSlot }}
                </div>
            @endif
        </div>
    </div>
</div>
