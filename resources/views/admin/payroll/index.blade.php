<x-layouts.admin title="Monthly Payroll Runs & Batch Batches">

    <div class="space-y-8">

        <!-- Header Banner & Batch Creator Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Monthly Payroll Runs</h1>
                    <x-badge variant="purple" dot="true">
                        Active 2026 Statutory Rules
                    </x-badge>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Execute automated Malaysian payroll calculation batches, statutory compliance, and multi-tier approval.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <x-button variant="primary" size="sm" icon="bx-plus" onclick="document.getElementById('payroll-run-modal').showModal()">
                    New Payroll Run
                </x-button>
            </div>
        </div>

        <!-- Metric Highlights via UI Kit -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Current Net Pool"
                value="RM 148,250.00"
                change="48 Employees included"
                changeType="positive"
                icon="bx-wallet-alt"
                color="indigo"
            />
            <x-stat-card 
                title="KWSP / EPF Pool"
                value="RM 38,420.00"
                change="EE + ER Total"
                changeType="neutral"
                icon="bx-shield-quarter"
                color="blue"
            />
            <x-stat-card 
                title="PERKESO & SKBBK"
                value="RM 4,115.60"
                change="Lindung 24 Jam integrated"
                changeType="neutral"
                icon="bx-plus-medical"
                color="purple"
            />
            <x-stat-card 
                title="LHDN PCB Tax MTD"
                value="RM 16,890.45"
                change="CP39 Batch Ready"
                changeType="positive"
                icon="bx-receipt"
                color="rose"
            />
        </div>

        <!-- Payroll Runs Batch History Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bx-calendar-check text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Historical Payroll Batches</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Batch Reference</th>
                            <th class="p-3.5">Period</th>
                            <th class="p-3.5">Headcount</th>
                            <th class="p-3.5">Gross Wages</th>
                            <th class="p-3.5">Statutory (EE / ER)</th>
                            <th class="p-3.5">Net Disbursement</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($payrollRuns as $run)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="p-3.5 font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $run->batch_no }}
                                </td>
                                <td class="p-3.5 font-medium text-slate-800 dark:text-slate-200">
                                    {{ date("F Y", mktime(0, 0, 0, (int)$run->period_month, 1, (int)$run->period_year)) }}
                                </td>
                                <td class="p-3.5 font-mono">
                                    {{ $run->total_headcount }} Staff
                                </td>
                                <td class="p-3.5 font-mono">
                                    RM {{ number_format($run->total_gross_amount, 2) }}
                                </td>
                                <td class="p-3.5 font-mono">
                                    RM {{ number_format($run->total_statutory_employee, 2) }} / RM {{ number_format($run->total_statutory_employer, 2) }}
                                </td>
                                <td class="p-3.5 font-mono font-bold text-slate-900 dark:text-white">
                                    RM {{ number_format($run->total_net_disbursement, 2) }}
                                </td>
                                <td class="p-3.5">
                                    @if($run->status === 'approved')
                                        <x-badge variant="emerald" dot="true">Approved</x-badge>
                                    @elseif($run->status === 'paid')
                                        <x-badge variant="blue" dot="true">Disbursed</x-badge>
                                    @else
                                        <x-badge variant="amber" dot="true">Draft Review</x-badge>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right">
                                    <x-action-button variant="indigo" icon="bx-show" href="{{ route('admin.payroll.show', $run) }}">
                                        View Batch
                                    </x-action-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">
                                    No payroll batches found. Click "New Payroll Run" to initiate the monthly run.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- New Payroll Run Modal -->
    <x-modal id="payroll-run-modal" title="Initiate New Payroll Processing Batch" size="md">
        <form method="POST" action="{{ route('admin.payroll.store') }}" class="space-y-4 text-left">
            @csrf
            <input type="hidden" name="company_id" value="1">

            <div class="grid grid-cols-2 gap-4">
                <x-input label="Period Year" name="period_year" type="number" value="{{ date('Y') }}" required />
                <x-input label="Period Month" name="period_month" type="text" value="{{ date('m') }}" required placeholder="08" />
            </div>

            <x-input label="Cut-Off Date" name="cutoff_date" type="date" value="{{ date('Y-m-25') }}" required />
            <x-input label="Payment Disbursement Date" name="payment_date" type="date" value="{{ date('Y-m-28') }}" required />

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="document.getElementById('payroll-run-modal').close()">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Calculate &amp; Run Batch
                </x-button>
            </div>
        </form>
    </x-modal>

</x-layouts.admin>
