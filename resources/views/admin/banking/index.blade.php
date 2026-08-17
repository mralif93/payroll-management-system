<x-layouts.admin title="Banking Autopay & Statutory Exporters">

    <div class="space-y-8">

        <!-- Header Banner & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Banking &amp; Statutory Exporters</h1>
                    <x-badge variant="emerald" dot="true">
                        Bank Autopay Ready
                    </x-badge>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Export electronic disbursement files (Maybank2e, CIMB BizChannel) and statutory files (EPF, SOCSO ASSIST, LHDN CP39).
                </p>
            </div>
        </div>

        <!-- 1. Corporate Bank Autopay Formats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 font-bold flex items-center justify-center text-lg">
                        <i class="bx bxs-bank"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Maybank2e Multi-Pay</h3>
                        <span class="text-[10px] text-slate-400">Fixed-width TXT Format</span>
                    </div>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Compliant Maybank2e MAS format with Header, Detail, and Trailer batch check records.
                </p>
                <x-button variant="primary" size="sm" class="w-full" icon="bx-download">
                    Download Maybank File
                </x-button>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 font-bold flex items-center justify-center text-lg">
                        <i class="bx bxs-credit-card"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">CIMB BizChannel</h3>
                        <span class="text-[10px] text-slate-400">Standard CSV Layout</span>
                    </div>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    CIMB Corporate BizChannel batch payroll format for bulk account credit and IBG payments.
                </p>
                <x-button variant="secondary" size="sm" class="w-full" icon="bx-download">
                    Download CIMB File
                </x-button>
            </div>

            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs space-y-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-pink-50 dark:bg-pink-950/80 text-pink-600 dark:text-pink-400 font-bold flex items-center justify-center text-lg">
                        <i class="bx bx-transfer"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">DuitNow &amp; IBG</h3>
                        <span class="text-[10px] text-slate-400">Universal Batch File</span>
                    </div>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Interbank GIRO disbursement file compatible with Public Bank, RHB, and Hong Leong.
                </p>
                <x-button variant="secondary" size="sm" class="w-full" icon="bx-download">
                    Download IBG Batch
                </x-button>
            </div>
        </div>

        <!-- 2. Malaysian Statutory Portals Exporters -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bx-export text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Statutory Agency Monthly File Formats</h2>
                </div>
            </div>

            <div class="p-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- KWSP -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900 dark:text-white text-xs">KWSP EPF i-Akaun</span>
                        <x-badge variant="indigo" size="sm">A-Format</x-badge>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        Monthly contribution schedule for EPF i-Akaun Majikan upload.
                    </p>
                    <x-button variant="primary" size="xs" class="w-full" icon="bx-download">
                        Export EPF CSV
                    </x-button>
                </div>

                <!-- PERKESO ASSIST -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900 dark:text-white text-xs">PERKESO ASSIST Portal</span>
                        <x-badge variant="purple" size="sm">June 2026</x-badge>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        Includes Act 4 base, EIS, and new SKBBK 24-hr non-employment injury fields.
                    </p>
                    <x-button variant="primary" size="xs" class="w-full" icon="bx-download">
                        Export ASSIST Text
                    </x-button>
                </div>

                <!-- LHDN CP39 -->
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 space-y-3">
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-slate-900 dark:text-white text-xs">LHDN e-CP39 MTD</span>
                        <x-badge variant="rose" size="sm">CP39 Format</x-badge>
                    </div>
                    <p class="text-[11px] text-slate-500 dark:text-slate-400">
                        Monthly Tax Deduction (Potongan Cukai Berjadual) e-Data CP39 file.
                    </p>
                    <x-button variant="primary" size="xs" class="w-full" icon="bx-download">
                        Export CP39 File
                    </x-button>
                </div>
            </div>
        </div>

    </div>

</x-layouts.admin>
