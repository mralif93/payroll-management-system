@php
    $isExportsTab = request()->routeIs('admin.exports.*') || request()->get('tab') === 'statutory';
@endphp

<x-layouts.admin :title="$isExportsTab ? 'Statutory Portals & Monthly Exporters' : 'Banking Autopay & Corporate Disbursement'">

    <div class="space-y-6">

        <!-- Executive Page Hero Banner & Action Suite -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900 via-slate-900 to-indigo-950 text-white p-6 sm:p-7 shadow-lg shadow-indigo-950/20 border border-indigo-800/40">
            <!-- Background Decorative Glow -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-20 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <div class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 font-bold text-base shadow-xs">
                            <i class="bx bxs-bank"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                            {{ $isExportsTab ? 'Statutory Agency Exporters' : 'Bank Autopay & Disbursement' }}
                        </h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            {{ $isExportsTab ? 'KWSP / PERKESO / LHDN Ready' : 'Bank Autopay M2E/CIMB' }}
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        {{ $isExportsTab ? 'Export official monthly submission files for KWSP EPF i-Akaun, PERKESO ASSIST (Act 4 + SKBBK), and LHDN e-CP39.' : 'Generate corporate electronic bulk salary payment files for Maybank2e, CIMB BizChannel, and Interbank DuitNow/IBG.' }}
                    </p>
                </div>

                @if($latestPayrollRun)
                    <div class="flex items-center gap-2.5 bg-white/10 backdrop-blur-md px-4 py-2.5 rounded-xl border border-white/20 shrink-0">
                        <span class="text-xs font-bold text-indigo-200">Active Cycle:</span>
                        <span class="px-2.5 py-1 rounded-lg bg-indigo-600/80 text-white font-mono font-bold text-xs shadow-xs">
                            {{ $latestPayrollRun->batch_no }} ({{ date("M Y", mktime(0,0,0, (int)$latestPayrollRun->period_month, 1)) }})
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Navigation Tabs (Bank Autopay vs Statutory Exporters) -->
        <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-3">
            <a href="{{ route('admin.banking.index') }}" class="px-4 py-2 text-xs font-bold rounded-xl transition flex items-center gap-2 {{ !$isExportsTab ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <i class="bx bxs-bank text-sm"></i>
                <span>Bank Autopay Formats (M2E / CIMB)</span>
            </a>
            <a href="{{ route('admin.exports.index') }}" class="px-4 py-2 text-xs font-bold rounded-xl transition flex items-center gap-2 {{ $isExportsTab ? 'bg-indigo-600 text-white shadow-sm shadow-indigo-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-400 hover:bg-slate-200 dark:hover:bg-slate-700' }}">
                <i class="bx bx-export text-sm"></i>
                <span>Statutory Agency Portals (KWSP / PERKESO / CP39)</span>
                <span class="px-1.5 py-0.5 rounded text-[9px] font-extrabold {{ $isExportsTab ? 'bg-indigo-700 text-indigo-100' : 'bg-purple-100 text-purple-700 dark:bg-purple-950 dark:text-purple-300' }}">2026</span>
            </a>
        </div>

        <!-- Metric Highlights via UI Kit -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Autopay Payout Volume"
                value="RM {{ number_format($totalDisbursed, 2) }}"
                change="{{ $bankBatches->total() }} Batches Disbursed"
                changeType="positive"
                icon="bx-wallet-alt"
                color="indigo"
            />
            <x-stat-card 
                title="Statutory Submissions"
                value="RM {{ number_format($totalStatutoryExported, 2) }}"
                change="{{ $statutorySubmissions->total() }} Files Exported"
                changeType="neutral"
                icon="bx-shield-quarter"
                color="purple"
            />
            <x-stat-card 
                title="Supported Bank Formats"
                value="Maybank, CIMB, IBG"
                change="M2E Multi-Pay &amp; CSV"
                changeType="neutral"
                icon="bx-buildings"
                color="emerald"
            />
            <x-stat-card 
                title="Statutory Compliance"
                value="2026 Standards"
                change="KWSP, SOCSO Act 4, SKBBK"
                changeType="positive"
                icon="bx-check-shield"
                color="blue"
            />
        </div>

        <!-- 1. Corporate Bank Autopay Formats (Visible on Banking Tab) -->
        <div class="{{ $isExportsTab ? 'hidden' : 'space-y-3' }}">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs font-bold">1</div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Corporate Bank Bulk Autopay Formats</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Maybank2e -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between space-y-4 hover:border-amber-400 dark:hover:border-amber-600 transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 font-bold flex items-center justify-center text-xl">
                                <i class="bx bxs-bank"></i>
                            </div>
                            <x-badge variant="amber" size="sm">M2E Fixed-Width</x-badge>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">Maybank2e Multi-Pay</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Compliant Maybank2e MAS format with Header, Detail, and Trailer batch check records.
                            </p>
                        </div>
                    </div>

                    @if($latestPayrollRun)
                        <button 
                            type="button" 
                            onclick="confirmExport('{{ route('admin.banking.bank-file', $latestPayrollRun) }}', 'format_type', 'maybank2e_fixed', 'Maybank2e Multi-Pay (.txt)', 'Maybank Corporate MAS Fixed-Width Format', '{{ $latestPayrollRun->batch_no }}', 'RM {{ number_format($latestPayrollRun->total_net_disbursement, 2) }}', '{{ $latestPayrollRun->items->count() }} Employees')" 
                            class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-amber-500 hover:bg-amber-600 text-white shadow-sm flex items-center justify-center gap-1.5 transition cursor-pointer"
                        >
                            <i class="bx bx-download text-base"></i>
                            <span>Export Maybank2e (.txt)</span>
                        </button>
                    @else
                        <button disabled class="w-full py-2 text-xs font-semibold rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 cursor-not-allowed">
                            No Batch Available
                        </button>
                    @endif
                </div>

                <!-- CIMB BizChannel -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between space-y-4 hover:border-rose-400 dark:hover:border-rose-600 transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 font-bold flex items-center justify-center text-xl">
                                <i class="bx bxs-credit-card"></i>
                            </div>
                            <x-badge variant="rose" size="sm">CSV Standard</x-badge>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">CIMB BizChannel</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                CIMB Corporate BizChannel batch payroll format for bulk account credit and IBG payments.
                            </p>
                        </div>
                    </div>

                    @if($latestPayrollRun)
                        <button 
                            type="button" 
                            onclick="confirmExport('{{ route('admin.banking.bank-file', $latestPayrollRun) }}', 'format_type', 'cimb_bizchannel_csv', 'CIMB BizChannel CSV (.csv)', 'CIMB Corporate Batch Credit CSV Format', '{{ $latestPayrollRun->batch_no }}', 'RM {{ number_format($latestPayrollRun->total_net_disbursement, 2) }}', '{{ $latestPayrollRun->items->count() }} Employees')" 
                            class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-sm flex items-center justify-center gap-1.5 transition cursor-pointer"
                        >
                            <i class="bx bx-download text-base"></i>
                            <span>Export CIMB CSV (.csv)</span>
                        </button>
                    @else
                        <button disabled class="w-full py-2 text-xs font-semibold rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 cursor-not-allowed">
                            No Batch Available
                        </button>
                    @endif
                </div>

                <!-- DuitNow & IBG -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between space-y-4 hover:border-indigo-400 dark:hover:border-indigo-600 transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-xl">
                                <i class="bx bx-transfer"></i>
                            </div>
                            <x-badge variant="indigo" size="sm">Universal IBG</x-badge>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">DuitNow &amp; IBG Batch</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Interbank GIRO disbursement file compatible with Public Bank, RHB, and Hong Leong.
                            </p>
                        </div>
                    </div>

                    @if($latestPayrollRun)
                        <button 
                            type="button" 
                            onclick="confirmExport('{{ route('admin.banking.bank-file', $latestPayrollRun) }}', 'format_type', 'duitnow_txt', 'DuitNow & IBG Batch (.txt)', 'Universal Interbank Direct Credit File', '{{ $latestPayrollRun->batch_no }}', 'RM {{ number_format($latestPayrollRun->total_net_disbursement, 2) }}', '{{ $latestPayrollRun->items->count() }} Employees')" 
                            class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm flex items-center justify-center gap-1.5 transition cursor-pointer"
                        >
                            <i class="bx bx-download text-base"></i>
                            <span>Export DuitNow (.txt)</span>
                        </button>
                    @else
                        <button disabled class="w-full py-2 text-xs font-semibold rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 cursor-not-allowed">
                            No Batch Available
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- 2. Malaysian Statutory Portals Exporters (Visible on Statutory Tab) -->
        <div class="{{ !$isExportsTab ? 'hidden' : 'space-y-3' }}">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 rounded-lg bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">1</div>
                <h2 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Statutory Agency Monthly Upload Formats</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- KWSP -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between space-y-4 hover:border-indigo-400 transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-xl">
                                <i class="bx bx-shield-quarter"></i>
                            </div>
                            <x-badge variant="indigo" size="sm">KWSP Form A</x-badge>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">KWSP EPF i-Akaun</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Monthly contribution schedule for EPF i-Akaun Majikan upload with 11% / 12% / 13% rates.
                            </p>
                        </div>
                    </div>

                    @if($latestPayrollRun)
                        <button 
                            type="button" 
                            onclick="confirmExport('{{ route('admin.banking.statutory-file', $latestPayrollRun) }}', 'statutory_body', 'epf', 'KWSP EPF Form A (.csv)', 'EPF i-Akaun Majikan Upload Schedule', '{{ $latestPayrollRun->batch_no }}', 'RM {{ number_format($latestPayrollRun->items->sum('epf_employee') + $latestPayrollRun->items->sum('epf_employer'), 2) }}', '{{ $latestPayrollRun->items->count() }} Employees')" 
                            class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white shadow-sm flex items-center justify-center gap-1.5 transition cursor-pointer"
                        >
                            <i class="bx bx-download text-base"></i>
                            <span>Export KWSP File (.csv)</span>
                        </button>
                    @else
                        <button disabled class="w-full py-2 text-xs font-semibold rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 cursor-not-allowed">
                            No Batch Available
                        </button>
                    @endif
                </div>

                <!-- PERKESO ASSIST -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between space-y-4 hover:border-purple-400 transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 font-bold flex items-center justify-center text-xl">
                                <i class="bx bx-plus-medical"></i>
                            </div>
                            <x-badge variant="purple" size="sm">June 2026 SKBBK</x-badge>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">PERKESO ASSIST Portal</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Includes Act 4 base, EIS, and new SKBBK 24-hr non-employment injury fields.
                            </p>
                        </div>
                    </div>

                    @if($latestPayrollRun)
                        <button 
                            type="button" 
                            onclick="confirmExport('{{ route('admin.banking.statutory-file', $latestPayrollRun) }}', 'statutory_body', 'socso', 'PERKESO ASSIST Schedule (.txt)', 'Form 8A (Act 4 + EIS + June 2026 SKBBK)', '{{ $latestPayrollRun->batch_no }}', 'RM {{ number_format($latestPayrollRun->items->sum('socso_employee') + $latestPayrollRun->items->sum('skbbk_employee') + $latestPayrollRun->items->sum('socso_employer') + $latestPayrollRun->items->sum('eis_employee') + $latestPayrollRun->items->sum('eis_employer'), 2) }}', '{{ $latestPayrollRun->items->count() }} Employees')" 
                            class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-purple-600 hover:bg-purple-700 text-white shadow-sm flex items-center justify-center gap-1.5 transition cursor-pointer"
                        >
                            <i class="bx bx-download text-base"></i>
                            <span>Export ASSIST File (.txt)</span>
                        </button>
                    @else
                        <button disabled class="w-full py-2 text-xs font-semibold rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 cursor-not-allowed">
                            No Batch Available
                        </button>
                    @endif
                </div>

                <!-- LHDN CP39 -->
                <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col justify-between space-y-4 hover:border-rose-400 transition">
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <div class="w-10 h-10 rounded-xl bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400 font-bold flex items-center justify-center text-xl">
                                <i class="bx bx-receipt"></i>
                            </div>
                            <x-badge variant="rose" size="sm">LHDN e-Data</x-badge>
                        </div>
                        <div>
                            <h3 class="text-sm font-extrabold text-slate-900 dark:text-white">LHDN e-CP39 MTD</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                                Monthly Tax Deduction (Potongan Cukai Berjadual) e-Data CP39 electronic schedule.
                            </p>
                        </div>
                    </div>

                    @if($latestPayrollRun)
                        <button 
                            type="button" 
                            onclick="confirmExport('{{ route('admin.banking.statutory-file', $latestPayrollRun) }}', 'statutory_body', 'lhdn_cp39', 'LHDN e-CP39 MTD Schedule (.txt)', 'Monthly Tax Deduction CP39 Data Format', '{{ $latestPayrollRun->batch_no }}', 'RM {{ number_format($latestPayrollRun->items->sum('pcb_amount'), 2) }}', '{{ $latestPayrollRun->items->count() }} Employees')" 
                            class="w-full py-2 px-3 text-xs font-semibold rounded-xl bg-rose-600 hover:bg-rose-700 text-white shadow-sm flex items-center justify-center gap-1.5 transition cursor-pointer"
                        >
                            <i class="bx bx-download text-base"></i>
                            <span>Export CP39 (.txt)</span>
                        </button>
                    @else
                        <button disabled class="w-full py-2 text-xs font-semibold rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-400 cursor-not-allowed">
                            No Batch Available
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- 3. Recent Generation Logs Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-base">
                        <i class="bx bx-history"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Export &amp; Generation Audit Trail</h2>
                        <p class="text-[11px] text-slate-400">Chronological history of generated bank and statutory files</p>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Reference / File</th>
                            <th class="p-3.5">Type &amp; Destination</th>
                            <th class="p-3.5">Batch</th>
                            <th class="p-3.5">Total Payable</th>
                            <th class="p-3.5">Generated At</th>
                            <th class="p-3.5">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($bankBatches as $b)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="p-3.5 font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $b->batch_reference_no }}
                                </td>
                                <td class="p-3.5 font-semibold text-slate-900 dark:text-white uppercase text-[11px]">
                                    {{ str_replace('_', ' ', $b->format_type) }}
                                </td>
                                <td class="p-3.5 font-mono text-slate-600 dark:text-slate-400">
                                    {{ $b->payrollRun?->batch_no ?? '—' }}
                                </td>
                                <td class="p-3.5 font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                    RM {{ number_format($b->total_disbursement_amount, 2) }}
                                </td>
                                <td class="p-3.5 text-slate-500 dark:text-slate-400">
                                    {{ $b->created_at->format('d M Y, h:i A') }}
                                </td>
                                <td class="p-3.5">
                                    <x-badge variant="emerald" dot="true">Generated</x-badge>
                                </td>
                            </tr>
                        @empty
                            @forelse($statutorySubmissions as $s)
                                <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                    <td class="p-3.5 font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ $s->statutory_body }}
                                    </td>
                                    <td class="p-3.5 font-semibold text-slate-900 dark:text-white uppercase text-[11px]">
                                        {{ str_replace('_', ' ', $s->submission_type) }}
                                    </td>
                                    <td class="p-3.5 font-mono text-slate-600 dark:text-slate-400">
                                        {{ $s->payrollRun?->batch_no ?? '—' }}
                                    </td>
                                    <td class="p-3.5 font-mono font-bold text-purple-600 dark:text-purple-400">
                                        RM {{ number_format($s->total_payable_amount, 2) }}
                                    </td>
                                    <td class="p-3.5 text-slate-500 dark:text-slate-400">
                                        {{ $s->created_at->format('d M Y, h:i A') }}
                                    </td>
                                    <td class="p-3.5">
                                        <x-badge variant="purple" dot="true">Exported</x-badge>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="p-10 text-center text-slate-400">
                                        No recent file export logs recorded yet.
                                    </td>
                                </tr>
                            @endforelse
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($bankBatches->hasPages() || $statutorySubmissions->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $bankBatches->hasPages() ? $bankBatches->links() : $statutorySubmissions->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- EXPORT & DOWNLOAD CONFIRMATION MODAL -->
    <x-modal id="export-confirm-modal" title="Confirm File Generation &amp; Download" subtitle="Verify batch parameters before creating official export file" icon="bx-download" size="md">
        <form id="export-confirm-form" method="POST" action="">
            @csrf
            <input type="hidden" id="modal-param-name" name="" value="">
            <input type="hidden" name="download" value="1">

            <div class="space-y-4 text-left text-xs">
                
                <!-- Notice Banner -->
                <div class="p-3.5 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800 flex items-start gap-3 text-amber-800 dark:text-amber-200">
                    <i class="bx bx-info-circle text-lg text-amber-600 shrink-0 mt-0.5"></i>
                    <div>
                        <span class="font-bold block text-xs">Official Compliance File Generation</span>
                        <p class="text-[11px] text-amber-700 dark:text-amber-300 mt-0.5">
                            Generating this file will create an immutable audit record in the system and prepare direct download.
                        </p>
                    </div>
                </div>

                <!-- Structured Metadata Box -->
                <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-slate-50/50 dark:bg-slate-900/50 divide-y divide-slate-100 dark:divide-slate-800">
                    <div class="grid grid-cols-2 p-3 items-center">
                        <span class="font-bold text-slate-500 dark:text-slate-400">Export Format</span>
                        <span class="text-right font-bold text-slate-900 dark:text-white" id="modal-format-title">—</span>
                    </div>
                    <div class="grid grid-cols-2 p-3 items-center">
                        <span class="font-bold text-slate-500 dark:text-slate-400">Format Description</span>
                        <span class="text-right text-[11px] text-slate-600 dark:text-slate-300" id="modal-format-desc">—</span>
                    </div>
                    <div class="grid grid-cols-2 p-3 items-center">
                        <span class="font-bold text-slate-500 dark:text-slate-400">Payroll Cycle Batch</span>
                        <span class="text-right font-mono font-bold text-indigo-600 dark:text-indigo-400" id="modal-batch-no">—</span>
                    </div>
                    <div class="grid grid-cols-2 p-3 items-center">
                        <span class="font-bold text-slate-500 dark:text-slate-400">Total Records</span>
                        <span class="text-right font-mono font-semibold text-slate-700 dark:text-slate-300" id="modal-headcount">—</span>
                    </div>
                    <div class="grid grid-cols-2 p-3 items-center bg-indigo-50/40 dark:bg-indigo-950/20">
                        <span class="font-extrabold text-slate-900 dark:text-white">Total Amount</span>
                        <span class="text-right font-mono font-extrabold text-emerald-600 dark:text-emerald-400 text-sm" id="modal-amount">—</span>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-2.5 pt-3 border-t border-slate-100 dark:border-slate-800">
                    <x-button variant="secondary" size="sm" type="button" onclick="closeModal('export-confirm-modal')">
                        Cancel
                    </x-button>
                    <x-button variant="primary" size="sm" type="submit" icon="bx-download" onclick="setTimeout(() => closeModal('export-confirm-modal'), 600)">
                        Confirm &amp; Download File
                    </x-button>
                </div>
            </div>
        </form>
    </x-modal>

    <x-slot name="scripts">
        <script>
            function confirmExport(actionUrl, paramName, paramValue, formatTitle, formatDesc, batchNo, amount, headcount) {
                const form = document.getElementById('export-confirm-form');
                form.action = actionUrl;
                
                const hiddenInput = document.getElementById('modal-param-name');
                hiddenInput.name = paramName;
                hiddenInput.value = paramValue;

                document.getElementById('modal-format-title').textContent = formatTitle;
                document.getElementById('modal-format-desc').textContent = formatDesc;
                document.getElementById('modal-batch-no').textContent = batchNo;
                document.getElementById('modal-amount').textContent = amount;
                document.getElementById('modal-headcount').textContent = headcount;

                openModal('export-confirm-modal');
            }
        </script>
    </x-slot>

</x-layouts.admin>

