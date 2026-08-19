<x-layouts.admin :title="'Payroll Batch Details — ' . $payrollRun->batch_no">

    <div class="space-y-6">

        <!-- Top Header & Actions Bar -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white dark:bg-slate-900 p-5 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs">
            <div class="flex items-center gap-3.5">
                <a href="{{ route('admin.payroll.index') }}" class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-950 dark:hover:text-indigo-400 flex items-center justify-center transition shrink-0" title="Back to Batches">
                    <i class="bx bx-arrow-back text-lg"></i>
                </a>
                <div class="min-w-0">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <h1 class="text-xl font-extrabold text-slate-900 dark:text-white font-mono tracking-tight">
                            {{ $payrollRun->batch_no }}
                        </h1>
                        @if($payrollRun->status === 'approved')
                            <x-badge variant="emerald" dot="true">Approved &amp; Locked</x-badge>
                        @elseif($payrollRun->status === 'paid')
                            <x-badge variant="blue" dot="true">Disbursed</x-badge>
                        @else
                            <x-badge variant="amber" dot="true">Draft Review</x-badge>
                        @endif
                    </div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5 flex items-center gap-2 flex-wrap">
                        <span>Period: <strong class="text-slate-800 dark:text-slate-200 font-semibold">{{ date("F Y", mktime(0, 0, 0, (int)$payrollRun->period_month, 1, (int)$payrollRun->period_year)) }}</strong></span>
                        <span>•</span>
                        <span>Payment Date: <span class="font-mono font-medium text-slate-700 dark:text-slate-300">{{ \Carbon\Carbon::parse($payrollRun->payment_date)->format('d M Y') }}</span></span>
                        <span>•</span>
                        <span>Employer: <strong class="text-slate-700 dark:text-slate-300">{{ $payrollRun->company?->name ?? 'Enterprise Inc' }}</strong></span>
                    </p>
                </div>
            </div>

            <!-- Contextual Batch Action Buttons -->
            <div class="flex items-center gap-2.5 shrink-0 flex-wrap">
                <a href="{{ route('admin.payroll.index') }}" class="px-3.5 py-2 text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 transition flex items-center gap-1.5">
                    <i class="bx bx-arrow-back text-sm"></i>
                    <span>Batches Roster</span>
                </a>

                <x-button variant="secondary" size="md" type="button" icon="bx-refresh" onclick="openModal('recalculate-modal')">
                    Recalculate &amp; Re-sync
                </x-button>

                @if($payrollRun->status === 'draft')
                    <form method="POST" action="{{ route('admin.payroll.approve', $payrollRun) }}">
                        @csrf
                        <x-button variant="success" size="md" type="submit" icon="bx-check-double">
                            Approve &amp; Lock Batch
                        </x-button>
                    </form>

                    <form method="POST" action="{{ route('admin.payroll.destroy', $payrollRun) }}" onsubmit="return confirm('Are you sure you want to permanently delete draft batch {{ $payrollRun->batch_no }}?')">
                        @csrf
                        @method('DELETE')
                        <x-button variant="danger" size="md" type="submit" icon="bx-trash">
                            Delete Draft
                        </x-button>
                    </form>
                @else
                    <a href="{{ route('admin.banking.index') }}" class="px-4 py-2 text-xs font-bold rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white hover:from-emerald-700 hover:to-teal-700 shadow-sm flex items-center gap-1.5 transition">
                        <i class="bx bxs-bank text-sm"></i>
                        <span>Generate Autopay Exporters</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Summary Metric Cards (UI Kit) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Gross Wages Pool"
                value="RM {{ number_format($payrollRun->total_gross_amount, 2) }}"
                change="{{ $payrollRun->total_headcount }} Staff Computed"
                changeType="neutral"
                icon="bx-money"
                color="indigo"
            />
            <x-stat-card 
                title="Employee Deductions"
                value="RM {{ number_format($payrollRun->total_statutory_employee, 2) }}"
                change="KWSP + SOCSO + EIS + PCB"
                changeType="neutral"
                icon="bx-receipt"
                color="rose"
            />
            <x-stat-card 
                title="Employer Contributions"
                value="RM {{ number_format($payrollRun->total_statutory_employer, 2) }}"
                change="Company Statutory Cost"
                changeType="neutral"
                icon="bx-buildings"
                color="purple"
            />
            <x-stat-card 
                title="Net Disbursement"
                value="RM {{ number_format($payrollRun->total_net_disbursement, 2) }}"
                change="Total Bank Payout"
                changeType="positive"
                icon="bx-wallet"
                color="emerald"
            />
        </div>

        <!-- Line Item Breakdown Table & Search -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            
            <!-- Table Header Toolbar -->
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base">
                        <i class="bx bx-list-ul"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Individual Employee Payslip Calculations</h2>
                        <p class="text-[11px] text-slate-400">Detailed statutory compliance itemized by Act 4, Act 800, and MTD</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <div class="relative">
                        <i class="bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                        <input 
                            type="text" 
                            id="search-table-input" 
                            placeholder="Filter employee or ID..." 
                            onkeyup="filterPayrollTable()" 
                            class="w-56 text-xs pl-8 pr-3 py-1.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 text-slate-800 dark:text-slate-200 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-indigo-500"
                        />
                    </div>
                    <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                        {{ $payrollRun->items->count() }} Records
                    </span>
                </div>
            </div>

            <!-- Responsive Table View -->
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs" id="payroll-items-table">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Employee Name &amp; ID</th>
                            <th class="p-3.5">Basic Salary</th>
                            <th class="p-3.5">Gross Pay</th>
                            <th class="p-3.5">EPF (EE / ER)</th>
                            <th class="p-3.5">SOCSO &amp; SKBBK</th>
                            <th class="p-3.5">EIS</th>
                            <th class="p-3.5">PCB Tax</th>
                            <th class="p-3.5 font-bold text-slate-900 dark:text-white">Net Take-Home</th>
                            <th class="p-3.5 text-right">Payslip</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($payrollRun->items as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition table-row-item">
                                <!-- Col 1: Employee Name & ID -->
                                <td class="p-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white flex items-center justify-center text-xs font-extrabold shadow-xs shrink-0">
                                            {{ strtoupper(substr($item->employee?->full_name ?? 'EM', 0, 2)) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-bold text-slate-900 dark:text-white block truncate employee-name">{{ $item->employee?->full_name ?? 'Staff Member' }}</span>
                                            <span class="text-[11px] font-mono text-slate-400 employee-no">{{ $item->employee?->employee_no ?? '—' }} • {{ $item->employee?->designation ?? 'Employee' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Col 2: Basic Salary -->
                                <td class="p-3.5 font-mono text-slate-600 dark:text-slate-400">
                                    RM {{ number_format($item->basic_salary, 2) }}
                                </td>

                                <!-- Col 3: Gross Pay -->
                                <td class="p-3.5 font-mono font-bold text-slate-900 dark:text-white">
                                    RM {{ number_format($item->gross_salary, 2) }}
                                </td>

                                <!-- Col 4: EPF (EE / ER) -->
                                <td class="p-3.5 font-mono text-[11px]">
                                    <span class="font-bold text-rose-600 dark:text-rose-400">RM {{ number_format($item->epf_employee, 2) }}</span>
                                    <span class="text-slate-400 font-normal"> / </span>
                                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">RM {{ number_format($item->epf_employer, 2) }}</span>
                                </td>

                                <!-- Col 5: SOCSO & SKBBK (EE / ER) -->
                                <td class="p-3.5 font-mono text-[11px]">
                                    <span class="font-bold text-rose-600 dark:text-rose-400">RM {{ number_format($item->socso_employee + $item->skbbk_employee, 2) }}</span>
                                    <span class="text-slate-400 font-normal"> / </span>
                                    <span class="font-semibold text-indigo-600 dark:text-indigo-400">RM {{ number_format($item->socso_employer, 2) }}</span>
                                </td>

                                <!-- Col 6: EIS (EE) -->
                                <td class="p-3.5 font-mono text-[11px] font-bold text-rose-600 dark:text-rose-400">
                                    RM {{ number_format($item->eis_employee, 2) }}
                                </td>

                                <!-- Col 7: PCB Tax -->
                                <td class="p-3.5 font-mono text-[11px]">
                                    @if($item->pcb_amount > 0)
                                        <span class="font-bold text-rose-600 dark:text-rose-400">RM {{ number_format($item->pcb_amount, 2) }}</span>
                                    @else
                                        <span class="text-slate-400">RM 0.00</span>
                                    @endif
                                </td>

                                <!-- Col 8: Net Pay -->
                                <td class="p-3.5 font-mono font-extrabold text-emerald-600 dark:text-emerald-400 text-sm">
                                    RM {{ number_format($item->net_salary, 2) }}
                                </td>

                                <!-- Col 9: Action Buttons -->
                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        <button 
                                            type="button" 
                                            onclick="openPayslipModal({{ json_encode($item) }}, {{ json_encode($item->employee) }}, '{{ $payrollRun->batch_no }}', '{{ date("F Y", mktime(0, 0, 0, (int)$payrollRun->period_month, 1, (int)$payrollRun->period_year)) }}', '{{ route('admin.payroll.payslip', [$payrollRun, $item]) }}')" 
                                            class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 flex items-center justify-center transition cursor-pointer" 
                                            title="Quick View Payslip"
                                        >
                                            <i class="bx bx-receipt text-base"></i>
                                        </button>
                                        <a 
                                            href="{{ route('admin.payroll.payslip', [$payrollRun, $item]) }}" 
                                            target="_blank"
                                            class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 dark:text-indigo-400 hover:bg-indigo-600 hover:text-white dark:hover:bg-indigo-600 dark:hover:text-white flex items-center justify-center transition cursor-pointer shadow-xs" 
                                            title="Open Printable PDF Payslip"
                                        >
                                            <i class="bx bx-printer text-base"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-12 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                        <i class="bx bx-receipt"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">No calculation records found</p>
                                    <p class="text-xs text-slate-400 mt-1">This payroll batch does not have computed employee line items.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- INDIVIDUAL PAYSLIP MODAL (Clear 3-Column Structured Breakdown: Employer | Employee | Sub-Total) -->
    <x-modal id="payslip-modal" title="Digital Payslip Statement" subtitle="Monthly Malaysian Statutory &amp; Net Disbursement Breakdown" icon="bx-receipt" size="xl">
        <div class="space-y-4 text-left text-xs">
            
            <!-- Employee Header Card Banner -->
            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3.5">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white flex items-center justify-center text-sm font-extrabold shadow-sm shrink-0" id="ps-emp-avatar">
                        EM
                    </div>
                    <div class="min-w-0">
                        <h3 class="text-sm font-extrabold text-slate-900 dark:text-white truncate" id="ps-emp-name">Employee Name</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-mono truncate" id="ps-emp-no">EMP-00101 • Designation</p>
                    </div>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Payroll Period</span>
                    <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 font-mono" id="ps-period">August 2026</span>
                </div>
            </div>

            <!-- Basic Salary & Gross Earnings Summary -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800">
                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-semibold text-slate-600 dark:text-slate-400">Monthly Basic Salary</div>
                    <div class="text-right font-mono font-bold text-slate-900 dark:text-white" id="ps-basic">RM 0.00</div>
                </div>

                <!-- Allowances Summary Header & Container -->
                <div class="p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition space-y-2">
                    <div class="flex items-center justify-between">
                        <div class="font-semibold text-slate-600 dark:text-slate-400 flex items-center gap-1.5">
                            <span>Allowances &amp; Claims</span>
                            <span id="ps-allowance-count-badge" class="px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400">0 items</span>
                        </div>
                        <div class="text-right font-mono font-bold text-indigo-600 dark:text-indigo-400" id="ps-allowances">RM 0.00</div>
                    </div>
                    
                    <!-- Itemized Allowance Breakdown List -->
                    <div id="ps-allowance-items-list" class="space-y-1.5 pl-3 border-l-2 border-indigo-200 dark:border-indigo-800">
                        <!-- Populated via JS dynamically -->
                    </div>
                </div>

                <div class="grid grid-cols-2 p-3 bg-slate-50/80 dark:bg-slate-800/50 hover:bg-slate-100/60 transition items-center">
                    <div class="font-bold text-slate-900 dark:text-white">Gross Wages Computed</div>
                    <div class="text-right font-mono font-extrabold text-slate-900 dark:text-white text-sm" id="ps-gross">RM 0.00</div>
                </div>
            </div>

            <!-- Statutory Contributions Matrix Table (Employer | Employee | Sub-Total) -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900">
                <div class="p-3 bg-slate-50/80 dark:bg-slate-800/50 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Statutory Contributions Matrix</span>
                    <span class="text-[10px] text-slate-400 font-mono">Malaysian Statutory Baseline</span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs font-sans">
                        <thead class="bg-slate-100/70 dark:bg-slate-800/40 text-[10.5px] font-bold text-slate-500 uppercase tracking-wider border-b border-slate-200/80 dark:border-slate-800">
                            <tr>
                                <th class="p-3">Statutory Scheme</th>
                                <th class="p-3 text-right text-indigo-600 dark:text-indigo-400">Employer</th>
                                <th class="p-3 text-right text-rose-600 dark:text-rose-400">Employee</th>
                                <th class="p-3 text-right text-slate-900 dark:text-white">Sub-Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80 font-mono">
                            <!-- EPF -->
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-3 font-sans font-semibold text-slate-800 dark:text-slate-200">
                                    KWSP / EPF
                                </td>
                                <td class="p-3 text-right text-indigo-600 dark:text-indigo-400" id="ps-epf-er">RM 0.00</td>
                                <td class="p-3 text-right text-rose-600 dark:text-rose-400 font-bold" id="ps-epf-ee">RM 0.00</td>
                                <td class="p-3 text-right font-bold text-slate-900 dark:text-white" id="ps-epf-subtotal">RM 0.00</td>
                            </tr>
                            <!-- SOCSO -->
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-3 font-sans font-semibold text-slate-800 dark:text-slate-200">
                                    PERKESO / SOCSO
                                </td>
                                <td class="p-3 text-right text-indigo-600 dark:text-indigo-400" id="ps-socso-er">RM 0.00</td>
                                <td class="p-3 text-right text-rose-600 dark:text-rose-400 font-bold" id="ps-socso-ee">RM 0.00</td>
                                <td class="p-3 text-right font-bold text-slate-900 dark:text-white" id="ps-socso-subtotal">RM 0.00</td>
                            </tr>
                            <!-- EIS -->
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-3 font-sans font-semibold text-slate-800 dark:text-slate-200">
                                    SIP / EIS
                                </td>
                                <td class="p-3 text-right text-indigo-600 dark:text-indigo-400" id="ps-eis-er">RM 0.00</td>
                                <td class="p-3 text-right text-rose-600 dark:text-rose-400 font-bold" id="ps-eis-ee">RM 0.00</td>
                                <td class="p-3 text-right font-bold text-slate-900 dark:text-white" id="ps-eis-subtotal">RM 0.00</td>
                            </tr>
                            <!-- PCB Tax -->
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition">
                                <td class="p-3 font-sans font-semibold text-slate-800 dark:text-slate-200">
                                    LHDN Income Tax (PCB / MTD)
                                </td>
                                <td class="p-3 text-right text-slate-400">—</td>
                                <td class="p-3 text-right text-rose-600 dark:text-rose-400 font-bold" id="ps-pcb">RM 0.00</td>
                                <td class="p-3 text-right font-bold text-slate-900 dark:text-white" id="ps-pcb-subtotal">RM 0.00</td>
                            </tr>
                            <!-- Totals Row -->
                            <tr class="bg-slate-50/90 dark:bg-slate-800/60 font-bold border-t-2 border-slate-200 dark:border-slate-700">
                                <td class="p-3 font-sans uppercase tracking-wider text-slate-700 dark:text-slate-300">Total Statutory</td>
                                <td class="p-3 text-right text-indigo-600 dark:text-indigo-400" id="ps-matrix-total-er">RM 0.00</td>
                                <td class="p-3 text-right text-rose-600 dark:text-rose-400" id="ps-matrix-total-ee">RM 0.00</td>
                                <td class="p-3 text-right text-slate-900 dark:text-white font-extrabold" id="ps-matrix-total-grand">RM 0.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Net Take-Home Salary Highlight -->
            <div class="rounded-xl border border-emerald-200 dark:border-emerald-800/80 bg-emerald-50/60 dark:bg-emerald-950/40 p-4 flex items-center justify-between">
                <div>
                    <span class="text-xs font-extrabold text-emerald-900 dark:text-emerald-300 uppercase tracking-wider block">Net Take-Home Pay (Disbursement)</span>
                    <span class="text-[11px] text-emerald-700 dark:text-emerald-400">Gross Wages (RM <span id="ps-net-gross">0.00</span>) - Employee Deductions (RM <span id="ps-net-deductions">0.00</span>)</span>
                </div>
                <div class="text-right">
                    <span class="text-xl font-mono font-black text-emerald-600 dark:text-emerald-400" id="ps-net">RM 0.00</span>
                </div>
            </div>

            <div class="flex items-center justify-between gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <a id="ps-pdf-link" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-sm shadow-indigo-600/20 transition cursor-pointer">
                    <i class="bx bx-printer text-base"></i>
                    <span>Print Official Payslip (PDF)</span>
                </a>
                <x-button variant="secondary" size="md" type="button" onclick="closeModal('payslip-modal')">
                    Close Payslip
                </x-button>
            </div>
        </div>
    </x-modal>

    <x-slot name="scripts">
        <script>
            function filterPayrollTable() {
                const query = document.getElementById('search-table-input').value.toLowerCase();
                const rows = document.querySelectorAll('.table-row-item');
                
                rows.forEach(row => {
                    const name = row.querySelector('.employee-name')?.textContent.toLowerCase() || '';
                    const no = row.querySelector('.employee-no')?.textContent.toLowerCase() || '';
                    if (name.includes(query) || no.includes(query)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            }

            function openPayslipModal(item, employee, batchNo, periodText, pdfUrl) {
                const fullName = employee?.full_name || 'Staff';
                document.getElementById('ps-emp-avatar').textContent = fullName.substring(0, 2).toUpperCase();
                document.getElementById('ps-emp-name').textContent = fullName;
                document.getElementById('ps-emp-no').textContent = (employee?.employee_no || '—') + ' • ' + (employee?.designation || 'Staff');
                document.getElementById('ps-period').textContent = periodText;
                if (pdfUrl) {
                    document.getElementById('ps-pdf-link').href = pdfUrl;
                }

                const basic = parseFloat(item.basic_salary || 0);
                const allowances = parseFloat(item.allowances_total || 0);
                const gross = parseFloat(item.gross_salary || 0);

                const epfEe = parseFloat(item.epf_employee || 0);
                const epfEr = parseFloat(item.epf_employer || 0);
                const epfSub = epfEe + epfEr;

                const socsoEe = parseFloat(item.socso_employee || 0) + parseFloat(item.skbbk_employee || 0);
                const socsoEr = parseFloat(item.socso_employer || 0);
                const socsoSub = socsoEe + socsoEr;

                const eisEe = parseFloat(item.eis_employee || 0);
                const eisEr = parseFloat(item.eis_employer || 0);
                const eisSub = eisEe + eisEr;

                const pcb = parseFloat(item.pcb_amount || 0);

                const totalEe = epfEe + socsoEe + eisEe + pcb;
                const totalEr = epfEr + socsoEr + eisEr;
                const grandTotal = totalEe + totalEr;
                const net = parseFloat(item.net_salary || (gross - totalEe));

                const fmt = num => 'RM ' + num.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                document.getElementById('ps-basic').textContent = fmt(basic);
                document.getElementById('ps-allowances').textContent = fmt(allowances);
                document.getElementById('ps-gross').textContent = fmt(gross);

                // Render Itemized Allowance Breakdown
                const allowanceListEl = document.getElementById('ps-allowance-items-list');
                const allowanceBadgeEl = document.getElementById('ps-allowance-count-badge');
                allowanceListEl.innerHTML = '';

                const salaryComponents = employee?.salary_components || [];
                const allowanceComponents = salaryComponents.filter(sc => (sc.salary_component?.type === 'allowance' || sc.type === 'allowance') && parseFloat(sc.amount) > 0);

                if (allowanceComponents.length > 0) {
                    allowanceBadgeEl.textContent = `${allowanceComponents.length} items`;
                    allowanceBadgeEl.className = 'px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400';
                    allowanceListEl.style.display = 'block';

                    allowanceComponents.forEach(comp => {
                        const name = comp.salary_component?.name || comp.name || 'Allowance';
                        const note = comp.notes || (comp.salary_component?.is_epf_subject ? 'Statutory-subject' : 'Tax/Statutory Exempt');
                        const amt = parseFloat(comp.amount || 0);

                        const rowDiv = document.createElement('div');
                        rowDiv.className = 'flex items-center justify-between text-[11px]';
                        rowDiv.innerHTML = `
                            <div class="flex items-center gap-1.5">
                                <i class="bx bx-check-circle text-xs text-indigo-500"></i>
                                <span class="font-medium text-slate-700 dark:text-slate-300">${name}</span>
                                <span class="text-[9.5px] text-slate-400">(${note})</span>
                            </div>
                            <span class="font-mono font-semibold text-slate-800 dark:text-slate-200">+ ${fmt(amt)}</span>
                        `;
                        allowanceListEl.appendChild(rowDiv);
                    });
                } else if (allowances > 0) {
                    allowanceBadgeEl.textContent = '1 item';
                    allowanceListEl.style.display = 'block';
                    allowanceListEl.innerHTML = `
                        <div class="flex items-center justify-between text-[11px]">
                            <div class="flex items-center gap-1.5">
                                <i class="bx bx-check-circle text-xs text-indigo-500"></i>
                                <span class="font-medium text-slate-700 dark:text-slate-300">Total Fixed Allowances</span>
                            </div>
                            <span class="font-mono font-semibold text-slate-800 dark:text-slate-200">+ ${fmt(allowances)}</span>
                        </div>
                    `;
                } else {
                    allowanceBadgeEl.textContent = 'None';
                    allowanceBadgeEl.className = 'px-1.5 py-0.5 rounded-md text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-400';
                    allowanceListEl.style.display = 'none';
                }

                // EPF
                document.getElementById('ps-epf-er').textContent = fmt(epfEr);
                document.getElementById('ps-epf-ee').textContent = fmt(epfEe);
                document.getElementById('ps-epf-subtotal').textContent = fmt(epfSub);

                // SOCSO
                document.getElementById('ps-socso-er').textContent = fmt(socsoEr);
                document.getElementById('ps-socso-ee').textContent = fmt(socsoEe);
                document.getElementById('ps-socso-subtotal').textContent = fmt(socsoSub);

                // EIS
                document.getElementById('ps-eis-er').textContent = fmt(eisEr);
                document.getElementById('ps-eis-ee').textContent = fmt(eisEe);
                document.getElementById('ps-eis-subtotal').textContent = fmt(eisSub);

                // PCB
                document.getElementById('ps-pcb').textContent = fmt(pcb);
                document.getElementById('ps-pcb-subtotal').textContent = fmt(pcb);

                // Totals
                document.getElementById('ps-matrix-total-er').textContent = fmt(totalEr);
                document.getElementById('ps-matrix-total-ee').textContent = fmt(totalEe);
                document.getElementById('ps-matrix-total-grand').textContent = fmt(grandTotal);

                // Net Section
                document.getElementById('ps-net-gross').textContent = gross.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('ps-net-deductions').textContent = totalEe.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('ps-net').textContent = fmt(net);

                openModal('payslip-modal');
            }
        </script>

        <!-- 3. RECALCULATE & RE-SYNC CONFIRMATION MODAL -->
        <x-modal id="recalculate-modal" title="Confirm Batch Recalculation" subtitle="Re-run calculation engine for {{ $payrollRun->batch_no }}" icon="bx-refresh" iconBg="bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400" size="md">
            <div class="space-y-4 text-left">
                <div class="p-4 rounded-xl bg-amber-50 dark:bg-amber-950/40 border border-amber-200 dark:border-amber-800/60 text-amber-800 dark:text-amber-300 flex items-start gap-3">
                    <i class="bx bx-error-circle text-xl text-amber-600 dark:text-amber-400 shrink-0 mt-0.5"></i>
                    <div class="text-xs space-y-1">
                        <span class="font-bold block">Re-sync Statutory &amp; Leave Deductions</span>
                        <p class="leading-relaxed">This action will re-fetch active employees, re-calculate unpaid leaves (ORP), re-compute KWSP/SOCSO/EIS/PCB, and revert the batch status to <strong class="text-amber-900 dark:text-amber-200">Draft Review</strong>.</p>
                    </div>
                </div>

                <div class="space-y-2 text-xs text-slate-600 dark:text-slate-300">
                    <span class="font-semibold text-slate-800 dark:text-slate-200 block">The following steps will be executed:</span>
                    <ul class="list-disc pl-5 space-y-1 text-slate-500 dark:text-slate-400">
                        <li>Purge and regenerate all {{ $payrollRun->total_headcount }} employee line items.</li>
                        <li>Apply updated statutory profile rates (EPF 11%/9%/2%, SOCSO Cat 1/2, EIS, PCB).</li>
                        <li>Update batch financial totals and unlock for further adjustments.</li>
                        <li>Log recalculation event to immutable audit trail.</li>
                    </ul>
                </div>

                <div class="pt-4 border-t border-slate-100 dark:border-slate-800 flex items-center justify-end gap-2.5">
                    <x-button variant="secondary" size="md" type="button" onclick="closeModal('recalculate-modal')">
                        Cancel
                    </x-button>

                    <form method="POST" action="{{ route('admin.payroll.recalculate', $payrollRun) }}">
                        @csrf
                        <x-button variant="primary" size="md" type="submit" icon="bx-refresh">
                            Confirm &amp; Recalculate
                        </x-button>
                    </form>
                </div>
            </div>
        </x-modal>
    </x-slot>

</x-layouts.admin>
