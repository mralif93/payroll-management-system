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

                @if($payrollRun->status === 'draft')
                    <form method="POST" action="{{ route('admin.payroll.approve', $payrollRun) }}">
                        @csrf
                        <x-button variant="success" size="md" type="submit" icon="bx-check-double">
                            Approve &amp; Lock Batch
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

    <!-- INDIVIDUAL PAYSLIP MODAL (Structured 2-Column Right-Aligned Label | Value) -->
    <x-modal id="payslip-modal" title="Digital Payslip Statement" subtitle="Monthly Malaysian Statutory &amp; Net Disbursement Breakdown" icon="bx-receipt" size="lg">
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

            <!-- Structured Label | Value Rows Table (Values strictly Right-Aligned) -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800">
                
                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Monthly Basic Salary</div>
                    <div class="text-right font-mono font-bold text-slate-900 dark:text-white" id="ps-basic">RM 0.00</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Allowances &amp; Claims</div>
                    <div class="text-right font-mono text-slate-800 dark:text-slate-200" id="ps-allowances">RM 0.00</div>
                </div>

                <div class="grid grid-cols-2 p-3 bg-slate-50/50 dark:bg-slate-800/30 hover:bg-slate-50/70 transition items-center">
                    <div class="font-bold text-slate-900 dark:text-white">Gross Wages Computed</div>
                    <div class="text-right font-mono font-extrabold text-slate-900 dark:text-white text-sm" id="ps-gross">RM 0.00</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-rose-600 dark:text-rose-400">EPF Employee (11%)</div>
                    <div class="text-right font-mono font-bold text-rose-600 dark:text-rose-400" id="ps-epf-ee">- RM 0.00</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-rose-600 dark:text-rose-400">SOCSO Act 4 + 2026 SKBBK</div>
                    <div class="text-right font-mono font-bold text-rose-600 dark:text-rose-400" id="ps-socso-ee">- RM 0.00</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-rose-600 dark:text-rose-400">EIS Employee (0.2%)</div>
                    <div class="text-right font-mono font-bold text-rose-600 dark:text-rose-400" id="ps-eis-ee">- RM 0.00</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-rose-600 dark:text-rose-400">LHDN Monthly Tax (PCB)</div>
                    <div class="text-right font-mono font-bold text-rose-600 dark:text-rose-400" id="ps-pcb">- RM 0.00</div>
                </div>

                <!-- Net Take-Home Highlight -->
                <div class="grid grid-cols-2 p-3.5 bg-emerald-50/60 dark:bg-emerald-950/40 hover:bg-emerald-50/90 transition items-center border-t border-emerald-200 dark:border-emerald-800">
                    <div class="font-extrabold text-emerald-900 dark:text-emerald-300 text-sm">Net Take-Home Pay</div>
                    <div class="text-right font-mono font-extrabold text-emerald-600 dark:text-emerald-400 text-base" id="ps-net">RM 0.00</div>
                </div>

                <!-- Employer Statutory (Company Cost) -->
                <div class="grid grid-cols-2 p-3 bg-slate-50/30 dark:bg-slate-800/20 text-slate-400 dark:text-slate-500 text-[11px] items-center">
                    <div>Employer EPF / SOCSO / EIS</div>
                    <div class="text-right font-mono font-medium" id="ps-employer-total">RM 0.00</div>
                </div>

            </div>

            <div class="flex items-center justify-between gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <a id="ps-pdf-link" href="#" target="_blank" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-sm shadow-indigo-600/20 transition cursor-pointer">
                    <i class="bx bx-printer text-sm"></i>
                    <span>Print / Save PDF</span>
                </a>
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('payslip-modal')">
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

                document.getElementById('ps-basic').textContent = 'RM ' + parseFloat(item.basic_salary || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('ps-allowances').textContent = 'RM ' + parseFloat(item.allowances_total || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('ps-gross').textContent = 'RM ' + parseFloat(item.gross_salary || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                
                document.getElementById('ps-epf-ee').textContent = '- RM ' + parseFloat(item.epf_employee || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('ps-socso-ee').textContent = '- RM ' + (parseFloat(item.socso_employee || 0) + parseFloat(item.skbbk_employee || 0)).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('ps-eis-ee').textContent = '- RM ' + parseFloat(item.eis_employee || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('ps-pcb').textContent = '- RM ' + parseFloat(item.pcb_amount || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                document.getElementById('ps-net').textContent = 'RM ' + parseFloat(item.net_salary || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('ps-employer-total').textContent = 'RM ' + parseFloat(item.total_employer_contributions || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 }) + ' (Company Cost)';

                openModal('payslip-modal');
            }
        </script>
    </x-slot>

</x-layouts.admin>
