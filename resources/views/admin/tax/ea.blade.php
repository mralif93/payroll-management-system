<x-layouts.admin title="Year-End Tax Form EA (C.P.8A) Compiler">

    <div class="space-y-8">

        <!-- Header Banner & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Year-End Form EA Compiler</h1>
                    <x-badge variant="rose" dot="true">
                        Borang EA (C.P.8A)
                    </x-badge>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Annual statement of remuneration from employment under Section 83(1A) of the Income Tax Act 1967.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <form method="POST" action="{{ route('admin.tax-ea.compile') }}">
                    @csrf
                    <input type="hidden" name="tax_year" value="{{ $taxYear ?? date('Y') }}">
                    <x-button variant="primary" size="sm" icon="bx-refresh" type="submit">
                        Compile Annual EA ({{ $taxYear ?? date('Y') }})
                    </x-button>
                </form>
            </div>
        </div>

        <!-- Metric Highlights via UI Kit -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card 
                title="Total Compiled Forms"
                value="{{ $eaRecords->total() ?? 0 }}"
                change="Tax Year {{ $taxYear ?? date('Y') }}"
                changeType="neutral"
                icon="bx-file"
                color="rose"
            />
            <x-stat-card 
                title="Accumulated PCB (MTD)"
                value="RM 202,685.40"
                change="Direct LHDN reconcilation"
                changeType="positive"
                icon="bx-receipt"
                color="indigo"
            />
            <x-stat-card 
                title="Total KWSP Employee"
                value="RM 461,040.00"
                change="Form EA Section E1"
                changeType="positive"
                icon="bx-shield-quarter"
                color="emerald"
            />
        </div>

        <!-- Compiled Form EA Records Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bxs-file-pdf text-rose-600 dark:text-rose-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Annual Employee Remuneration Records</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Serial No</th>
                            <th class="p-3.5">Employee Name</th>
                            <th class="p-3.5">Gross Wages (Sec B1)</th>
                            <th class="p-3.5">PCB MTD (Sec D1)</th>
                            <th class="p-3.5">KWSP EE (Sec E1)</th>
                            <th class="p-3.5">PERKESO EE (Sec E2)</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($eaRecords as $record)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="p-3.5 font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $record->serial_no }}
                                </td>
                                <td class="p-3.5 font-semibold text-slate-900 dark:text-white">
                                    {{ $record->employee?->full_name }}
                                </td>
                                <td class="p-3.5 font-mono font-bold">
                                    RM {{ number_format($record->gross_salary_wages, 2) }}
                                </td>
                                <td class="p-3.5 font-mono text-rose-600 dark:text-rose-400 font-bold">
                                    RM {{ number_format($record->total_pcb_mtd, 2) }}
                                </td>
                                <td class="p-3.5 font-mono">
                                    RM {{ number_format($record->total_epf_employee, 2) }}
                                </td>
                                <td class="p-3.5 font-mono">
                                    RM {{ number_format($record->total_socso_employee, 2) }}
                                </td>
                                <td class="p-3.5 text-right">
                                    <x-action-button variant="rose" icon="bx-download">
                                        Download PDF
                                    </x-action-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    No compiled Form EA records found for Tax Year {{ $taxYear ?? date('Y') }}. Click "Compile Annual EA" above.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-layouts.admin>
