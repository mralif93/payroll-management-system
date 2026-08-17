<x-layouts.admin :title="'Payroll Run Details — ' . $payrollRun->batch_no">

    <div class="space-y-6">

        <!-- Top Navigation / Breadcrumb & Actions -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="space-y-1">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.payroll.index') }}" class="w-7 h-7 rounded-lg bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-indigo-600 flex items-center justify-center transition">
                        <i class="bx bx-arrow-back text-sm"></i>
                    </a>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight font-mono">
                        {{ $payrollRun->batch_no }}
                    </h1>
                    @if($payrollRun->status === 'approved')
                        <x-badge variant="emerald" dot="true">Approved</x-badge>
                    @elseif($payrollRun->status === 'paid')
                        <x-badge variant="blue" dot="true">Disbursed</x-badge>
                    @else
                        <x-badge variant="amber" dot="true">Draft Review</x-badge>
                    @endif
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400">
                    Cycle Period: <strong class="text-slate-700 dark:text-slate-200">{{ date("F Y", mktime(0, 0, 0, (int)$payrollRun->period_month, 1, (int)$payrollRun->period_year)) }}</strong>
                    • Payment Due: <span class="font-mono">{{ \Carbon\Carbon::parse($payrollRun->payment_date)->format('d M Y') }}</span>
                    • {{ $payrollRun->company?->name ?? 'Enterprise Inc' }}
                </p>
            </div>

            <!-- Contextual Action Buttons -->
            <div class="flex items-center gap-2.5 flex-wrap">
                <a href="{{ route('admin.payroll.index') }}" class="px-3.5 py-2 text-xs font-semibold rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition flex items-center gap-1.5">
                    <i class="bx bx-arrow-back"></i>
                    <span>Back to Batches</span>
                </a>

                @if($payrollRun->status === 'draft')
                    <form method="POST" action="{{ route('admin.payroll.approve', $payrollRun) }}">
                        @csrf
                        <x-button variant="success" size="md" type="submit" icon="bx-check-double">
                            Approve &amp; Lock Batch
                        </x-button>
                    </form>
                @else
                    <a href="{{ route('admin.banking.index') }}" class="px-3.5 py-2 text-xs font-bold rounded-xl bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm flex items-center gap-1.5 transition">
                        <i class="bx bxs-bank"></i>
                        <span>Generate Bank Autopay File</span>
                    </a>
                @endif
            </div>
        </div>

        <!-- Summary Metric Cards (UI Kit) -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Gross Earnings Pool"
                value="RM {{ number_format($payrollRun->total_gross_amount, 2) }}"
                change="{{ $payrollRun->total_headcount }} Employees computed"
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
                change="Company statutory cost"
                changeType="neutral"
                icon="bx-buildings"
                color="purple"
            />
            <x-stat-card 
                title="Net Take-Home Disbursed"
                value="RM {{ number_format($payrollRun->total_net_disbursement, 2) }}"
                change="To Bank Accounts"
                changeType="positive"
                icon="bx-wallet"
                color="emerald"
            />
        </div>

        <!-- Line Item Breakdown Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base">
                        <i class="bx bx-list-ul"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Individual Employee Payslip Calculations</h2>
                        <p class="text-[11px] text-slate-400">Detailed line item breakdown per statutory regulation Act 4 / Act 800</p>
                    </div>
                </div>
                <div>
                    <span class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400">{{ $payrollRun->items->count() }} Staff Records</span>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Employee Name &amp; Staff ID</th>
                            <th class="p-3.5">Basic Salary</th>
                            <th class="p-3.5">Gross Pay</th>
                            <th class="p-3.5">EPF (EE / ER)</th>
                            <th class="p-3.5">SOCSO &amp; SKBBK</th>
                            <th class="p-3.5">EIS</th>
                            <th class="p-3.5">PCB Tax</th>
                            <th class="p-3.5">Net Pay</th>
                            <th class="p-3.5 text-right">Payslip</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($payrollRun->items as $item)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="p-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-lg bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white flex items-center justify-center text-xs font-extrabold shadow-xs shrink-0">
                                            {{ substr($item->employee?->full_name ?? 'EM', 0, 2) }}
                                        </div>
                                        <div class="min-w-0">
                                            <span class="font-bold text-slate-900 dark:text-white block truncate">{{ $item->employee?->full_name ?? 'Unknown Staff' }}</span>
                                            <span class="text-[11px] font-mono text-slate-400">{{ $item->employee?->employee_no ?? '—' }} • {{ $item->employee?->designation ?? 'Staff' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3.5 font-mono text-slate-600 dark:text-slate-400">
                                    RM {{ number_format($item->basic_salary, 2) }}
                                </td>
                                <td class="p-3.5 font-mono font-semibold text-slate-900 dark:text-white">
                                    RM {{ number_format($item->gross_salary, 2) }}
                                </td>
                                <td class="p-3.5 font-mono text-[11px]">
                                    <span class="text-rose-600 dark:text-rose-400 font-bold">RM {{ number_format($item->epf_employee, 2) }}</span>
                                    <span class="text-slate-400"> / </span>
                                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">RM {{ number_format($item->epf_employer, 2) }}</span>
                                </td>
                                <td class="p-3.5 font-mono text-[11px]">
                                    <span class="text-rose-600 dark:text-rose-400 font-bold">RM {{ number_format($item->socso_employee + $item->skbbk_employee, 2) }}</span>
                                    <span class="text-slate-400"> / </span>
                                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">RM {{ number_format($item->socso_employer, 2) }}</span>
                                </td>
                                <td class="p-3.5 font-mono text-[11px]">
                                    <span class="text-rose-600 dark:text-rose-400 font-bold">RM {{ number_format($item->eis_employee, 2) }}</span>
                                </td>
                                <td class="p-3.5 font-mono text-[11px]">
                                    <span class="text-rose-600 dark:text-rose-400 font-bold">RM {{ number_format($item->pcb_amount, 2) }}</span>
                                </td>
                                <td class="p-3.5 font-mono font-extrabold text-emerald-600 dark:text-emerald-400 text-sm">
                                    RM {{ number_format($item->net_salary, 2) }}
                                </td>
                                <td class="p-3.5 text-right">
                                    <button type="button" onclick="openPayslipModal({{ json_encode($item) }}, {{ json_encode($item->employee) }}, '{{ $payrollRun->batch_no }}', '{{ date("F Y", mktime(0, 0, 0, (int)$payrollRun->period_month, 1, (int)$payrollRun->period_year)) }}')" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 flex items-center justify-center transition cursor-pointer" title="View Detailed Payslip">
                                        <i class="bx bx-receipt text-base"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="p-8 text-center text-slate-400">
                                    No employee items found for this payroll batch.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- INDIVIDUAL PAYSLIP MODAL (Label | Value Right-Aligned Structure) -->
    <x-modal id="payslip-modal" title="Digital Payslip Statement" subtitle="Monthly Malaysian Statutory &amp; Net Disbursement Breakdown" icon="bx-receipt" size="lg">
        <div class="space-y-4 text-left text-xs">
            
            <!-- Employee Header Banner -->
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800 flex items-center justify-between gap-3.5">
                <div class="min-w-0">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white truncate" id="ps-emp-name">Employee Name</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-mono truncate" id="ps-emp-no">EMP-00101 • Designation</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-[10px] font-bold text-slate-400 block uppercase tracking-wider">Payroll Period</span>
                    <span class="text-xs font-bold text-indigo-600 dark:text-indigo-400 font-mono" id="ps-period">August 2026</span>
                </div>
            </div>

            <!-- Structured Label | Value Rows Table -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800">
                
                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Monthly Basic Salary</div>
                    <div class="text-right font-mono font-bold text-slate-900 dark:text-white" id="ps-basic">RM 0.00</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Allowances &amp; Claims</div>
                    <div class="text-right font-mono text-slate-800 dark:text-slate-200" id="ps-allowances">RM 0.00</div>
                </div>

                <div class="grid grid-cols-2 p-3 bg-slate-50/40 dark:bg-slate-800/20 hover:bg-slate-50/60 transition items-center">
                    <div class="font-bold text-slate-900 dark:text-white">Gross Salary Wages</div>
                    <div class="text-right font-mono font-extrabold text-slate-900 dark:text-white" id="ps-gross">RM 0.00</div>
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

                <div class="grid grid-cols-2 p-3.5 bg-emerald-50/50 dark:bg-emerald-950/30 hover:bg-emerald-50/80 transition items-center border-t border-emerald-200 dark:border-emerald-800">
                    <div class="font-extrabold text-emerald-900 dark:text-emerald-300 text-sm">Net Take-Home Pay</div>
                    <div class="text-right font-mono font-extrabold text-emerald-600 dark:text-emerald-400 text-base" id="ps-net">RM 0.00</div>
                </div>

                <!-- Employer Statutory (Informational) -->
                <div class="grid grid-cols-2 p-3 bg-slate-50/20 text-slate-400 dark:text-slate-500 text-[11px] items-center">
                    <div>Employer EPF / SOCSO / EIS</div>
                    <div class="text-right font-mono" id="ps-employer-total">RM 0.00</div>
                </div>

            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('payslip-modal')">
                    Close Payslip
                </x-button>
            </div>
        </div>
    </x-modal>

    <x-slot name="scripts">
        <script>
            function openPayslipModal(item, employee, batchNo, periodText) {
                document.getElementById('ps-emp-name').textContent = employee.full_name || 'Staff';
                document.getElementById('ps-emp-no').textContent = (employee.employee_no || '—') + ' • ' + (employee.designation || 'Staff');
                document.getElementById('ps-period').textContent = periodText;

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
