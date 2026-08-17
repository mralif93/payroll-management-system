@php
    $isExportsTab = request()->routeIs('admin.exports.*') || request()->get('tab') === 'statutory';
@endphp

<x-layouts.admin :title="$isExportsTab ? 'Statutory Portals & Monthly Exporters' : 'Banking Autopay & Corporate Disbursement'">

    <div class="space-y-6">

        <!-- Header Banner & Dynamic Title -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2.5">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">
                        {{ $isExportsTab ? 'Statutory Agency Exporters' : 'Bank Autopay & Disbursement' }}
                    </h1>
                    @if($isExportsTab)
                        <x-badge variant="purple" dot="true">2026 Statutory Compliance</x-badge>
                    @else
                        <x-badge variant="emerald" dot="true">Bank Autopay Ready</x-badge>
                    @endif
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    {{ $isExportsTab ? 'Export official monthly submission files for KWSP EPF i-Akaun, PERKESO ASSIST (Act 4 + SKBBK), and LHDN e-CP39.' : 'Generate corporate electronic bulk salary payment files for Maybank2e, CIMB BizChannel, and Interbank DuitNow/IBG.' }}
                </p>
            </div>

            <!-- Active Batch Selector Badge -->
            @if($latestPayrollRun)
                <div class="flex items-center gap-2 bg-white dark:bg-slate-900 p-2 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
                    <span class="text-xs font-bold text-slate-500 dark:text-slate-400 pl-2">Active Cycle:</span>
                    <span class="px-2.5 py-1 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-mono font-bold text-xs">
                        {{ $latestPayrollRun->batch_no }} ({{ date("M Y", mktime(0,0,0, (int)$latestPayrollRun->period_month, 1)) }})
                    </span>
                </div>
            @endif
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
                        <form method="POST" action="{{ route('admin.banking.bank-file', $latestPayrollRun) }}">
                            @csrf
                            <input type="hidden" name="format_type" value="maybank2e_fixed">
                            <input type="hidden" name="download" value="1">
                            <x-button variant="warning" size="sm" class="w-full" icon="bx-download" type="submit">
                                Export Maybank2e (.txt)
                            </x-button>
                        </form>
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
                        <form method="POST" action="{{ route('admin.banking.bank-file', $latestPayrollRun) }}">
                            @csrf
                            <input type="hidden" name="format_type" value="cimb_bizchannel_csv">
                            <input type="hidden" name="download" value="1">
                            <x-button variant="danger" size="sm" class="w-full" icon="bx-download" type="submit">
                                Export CIMB CSV (.csv)
                            </x-button>
                        </form>
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
                        <form method="POST" action="{{ route('admin.banking.bank-file', $latestPayrollRun) }}">
                            @csrf
                            <input type="hidden" name="format_type" value="duitnow_txt">
                            <input type="hidden" name="download" value="1">
                            <x-button variant="primary" size="sm" class="w-full" icon="bx-download" type="submit">
                                Export DuitNow (.txt)
                            </x-button>
                        </form>
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
                        <form method="POST" action="{{ route('admin.banking.statutory-file', $latestPayrollRun) }}">
                            @csrf
                            <input type="hidden" name="statutory_body" value="epf">
                            <input type="hidden" name="download" value="1">
                            <x-button variant="primary" size="sm" class="w-full" icon="bx-download" type="submit">
                                Export KWSP File (.csv)
                            </x-button>
                        </form>
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
                        <form method="POST" action="{{ route('admin.banking.statutory-file', $latestPayrollRun) }}">
                            @csrf
                            <input type="hidden" name="statutory_body" value="socso">
                            <input type="hidden" name="download" value="1">
                            <x-button variant="secondary" size="sm" class="w-full" icon="bx-download" type="submit">
                                Export ASSIST File (.txt)
                            </x-button>
                        </form>
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
                        <form method="POST" action="{{ route('admin.banking.statutory-file', $latestPayrollRun) }}">
                            @csrf
                            <input type="hidden" name="statutory_body" value="lhdn_cp39">
                            <input type="hidden" name="download" value="1">
                            <x-button variant="danger" size="sm" class="w-full" icon="bx-download" type="submit">
                                Export CP39 (.txt)
                            </x-button>
                        </form>
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
        </div>

    </div>

</x-layouts.admin>

