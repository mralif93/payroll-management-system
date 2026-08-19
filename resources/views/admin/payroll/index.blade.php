<x-layouts.admin title="Monthly Payroll Runs &amp; Batches">

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
                            <i class="bx bx-calendar-check"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Monthly Payroll Batches</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            2026 Statutory Engine
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        Execute automated Malaysian payroll calculation batches, statutory deductions (KWSP, SOCSO, EIS, PCB), and multi-tier approval workflows.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap shrink-0">
                    <button 
                        type="button" 
                        onclick="openModal('payroll-run-modal')"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition flex items-center gap-2 cursor-pointer hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bx bx-plus text-base"></i>
                        <span>New Payroll Run</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Metric Highlights via UI Kit -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Total Net Disbursed"
                value="RM {{ number_format($totalNetPool, 2) }}"
                change="{{ $payrollRuns->total() }} Total Batches"
                changeType="positive"
                icon="bx-wallet-alt"
                color="indigo"
            />
            <x-stat-card 
                title="Active Roster Included"
                value="{{ $activeEmployeesCount }} Staff"
                change="Ready for payroll calculation"
                changeType="neutral"
                icon="bx-group"
                color="blue"
            />
            <x-stat-card 
                title="Statutory EE Deductions"
                value="RM {{ number_format($totalEmployeeStatutory, 2) }}"
                change="KWSP + PERKESO + EIS + PCB"
                changeType="neutral"
                icon="bx-shield-quarter"
                color="purple"
            />
            <x-stat-card 
                title="Employer Contributions"
                value="RM {{ number_format($totalEmployerStatutory, 2) }}"
                change="Company statutory cost"
                changeType="positive"
                icon="bx-buildings"
                color="emerald"
            />
        </div>
 
        <!-- Executive Search & Filter Command Suite for Payroll Batches -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-3.5 sm:p-4 bg-slate-50/50 dark:bg-slate-850/40 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                        <i class="bx bx-slider-alt"></i>
                    </span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Search &amp; Filter Batches</span>
                </div>
                @if(request()->hasAny(['search', 'year', 'month', 'status']))
                    <a href="{{ route('admin.payroll.index') }}" class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 hover:underline flex items-center gap-1">
                        <i class="bx bx-reset"></i>
                        <span>Clear All Filters</span>
                    </a>
                @endif
            </div>

            <div class="p-3.5 sm:p-4">
                <form method="GET" action="{{ route('admin.payroll.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="bx bx-search text-base"></i>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search by batch number (e.g. RUN-2026-08)..." 
                            class="w-full pl-10 pr-10 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 dark:focus:border-indigo-400 transition"
                        >
                        @if(request('search'))
                            <a href="{{ route('admin.payroll.index', request()->except('search')) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <i class="bx bx-x-circle text-base"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Dropdowns & Actions Group -->
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Year Dropdown -->
                        <div class="relative">
                            <select 
                                name="year" 
                                onchange="this.form.submit()" 
                                class="py-2 pl-3 pr-8 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                            >
                                <option value="">All Years</option>
                                @foreach($availableYears ?? [2026, 2025, 2024] as $yr)
                                    <option value="{{ $yr }}" {{ request('year') == $yr ? 'selected' : '' }}>Year {{ $yr }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Month Dropdown -->
                        <div class="relative">
                            <select 
                                name="month" 
                                onchange="this.form.submit()" 
                                class="py-2 pl-3 pr-8 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                            >
                                <option value="">All Months</option>
                                @for($m = 1; $m <= 12; $m++)
                                    <option value="{{ sprintf('%02d', $m) }}" {{ request('month') == sprintf('%02d', $m) ? 'selected' : '' }}>
                                        {{ date('M', mktime(0, 0, 0, $m, 1)) }} ({{ sprintf('%02d', $m) }})
                                    </option>
                                @endfor
                            </select>
                        </div>

                        <!-- Status Dropdown -->
                        <div class="relative">
                            <select 
                                name="status" 
                                onchange="this.form.submit()" 
                                class="py-2 pl-3 pr-8 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                            >
                                <option value="">All Statuses</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="locked" {{ request('status') === 'locked' ? 'selected' : '' }}>Locked</option>
                            </select>
                        </div>

                        <!-- Filter Button -->
                        <button type="submit" class="px-3.5 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                            <i class="bx bx-filter-alt"></i>
                            <span>Filter</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>

        <!-- Payroll Runs Batch History Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-base">
                        <i class="bx bx-calendar-check"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">Historical Payroll Batches</h2>
                        <p class="text-[11px] text-slate-400">All calculated and approved monthly salary cycles</p>
                    </div>
                </div>
                <div>
                    <span class="text-xs font-mono font-bold text-slate-500 dark:text-slate-400">{{ $payrollRuns->total() }} recorded</span>
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
                                    <div class="flex items-center gap-2">
                                        <i class="bx bx-receipt text-base"></i>
                                        <span>{{ $run->batch_no }}</span>
                                    </div>
                                </td>
                                <td class="p-3.5 font-semibold text-slate-900 dark:text-white">
                                    {{ date("F Y", mktime(0, 0, 0, (int)$run->period_month, 1, (int)$run->period_year)) }}
                                    <span class="block text-[11px] font-normal text-slate-400 font-mono">Disburse: {{ \Carbon\Carbon::parse($run->payment_date)->format('d M Y') }}</span>
                                </td>
                                <td class="p-3.5 font-mono">
                                    <span class="inline-flex items-center gap-1 font-semibold text-slate-800 dark:text-slate-200">
                                        <i class="bx bx-user text-xs text-slate-400"></i>
                                        {{ $run->total_headcount }} Staff
                                    </span>
                                </td>
                                <td class="p-3.5 font-mono font-medium text-slate-800 dark:text-slate-200">
                                    RM {{ number_format($run->total_gross_amount, 2) }}
                                </td>
                                <td class="p-3.5 font-mono text-[11px]">
                                    <span class="text-rose-600 dark:text-rose-400 font-bold">RM {{ number_format($run->total_statutory_employee, 2) }}</span>
                                    <span class="text-slate-400"> / </span>
                                    <span class="text-indigo-600 dark:text-indigo-400 font-bold">RM {{ number_format($run->total_statutory_employer, 2) }}</span>
                                </td>
                                <td class="p-3.5 font-mono font-extrabold text-slate-900 dark:text-white text-sm">
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
                                    <div class="flex items-center justify-end gap-1.5">
                                        <a href="{{ route('admin.payroll.show', $run) }}" class="w-8 h-8 rounded-full bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-indigo-50 hover:text-indigo-600 dark:hover:bg-indigo-950 dark:hover:text-indigo-300 flex items-center justify-center transition cursor-pointer" title="View Batch Calculation Details">
                                            <i class="bx bx-show text-base"></i>
                                        </a>

                                        @if($run->status === 'draft')
                                            <form method="POST" action="{{ route('admin.payroll.destroy', $run) }}" onsubmit="return confirm('Are you sure you want to delete draft batch {{ $run->batch_no }}? All calculated items will be removed.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-8 h-8 rounded-full bg-rose-50 dark:bg-rose-950/40 text-rose-600 dark:text-rose-400 hover:bg-rose-100 dark:hover:bg-rose-900/60 flex items-center justify-center transition cursor-pointer" title="Delete Draft Batch">
                                                    <i class="bx bx-trash text-base"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-12 text-center text-slate-400">
                                    <div class="w-12 h-12 rounded-2xl bg-slate-100 dark:bg-slate-800 text-slate-400 flex items-center justify-center mx-auto mb-3 text-2xl">
                                        <i class="bx bx-calendar-x"></i>
                                    </div>
                                    <p class="text-sm font-semibold text-slate-700 dark:text-slate-300">No payroll batches executed yet</p>
                                    <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Click "New Payroll Run" above to calculate monthly salaries for all active employees.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($payrollRuns->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $payrollRuns->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- 1. INITIATE NEW PAYROLL RUN MODAL (2xl Spacious 2-Column Numbered Sections) -->
    <x-modal id="payroll-run-modal" title="Initiate New Payroll Processing Batch" subtitle="Calculate Gross wages, EPF, SOCSO Act 4, SKBBK, EIS, and PCB for active employees" icon="bx-calculator" size="2xl">
        <form method="POST" action="{{ route('admin.payroll.store') }}" class="space-y-6 text-left">
            @csrf

            <!-- Section 1: Target Entity & Payroll Period -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold">1</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Target Company &amp; Payroll Period</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Employer / Company</label>
                        <div class="relative">
                            <select name="company_id" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                @foreach($companies as $company)
                                    <option value="{{ $company->id }}">{{ $company->name }} (Reg: {{ $company->registration_no ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Payroll Year</label>
                        <div class="relative">
                            <select name="period_year" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8 font-mono">
                                @for($y = (int)date('Y') + 1; $y >= 2024; $y--)
                                    <option value="{{ $y }}" {{ $y == (int)date('Y') ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Payroll Month</label>
                        <div class="relative">
                            <select name="period_month" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8 font-mono">
                                @foreach(range(1, 12) as $m)
                                    @php $paddedMonth = str_pad($m, 2, '0', STR_PAD_LEFT); @endphp
                                    <option value="{{ $paddedMonth }}" {{ $paddedMonth == date('m') ? 'selected' : '' }}>
                                        {{ $paddedMonth }} - {{ date('F', mktime(0, 0, 0, $m, 1)) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Key Operational Dates -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">2</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Execution &amp; Disbursement Dates</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <x-input label="Attendance &amp; Claim Cut-Off Date" name="cutoff_date" type="date" value="{{ date('Y-m-25') }}" required icon="bx-calendar" helper="Last day for claims and overtime calculation" />
                    <x-input label="Salary Disbursement / Payment Date" name="payment_date" type="date" value="{{ date('Y-m-28') }}" required icon="bx-calendar-check" helper="Date banks will disburse funds to staff" />
                </div>
            </div>

            <!-- Pre-Execution Notice -->
            <div class="p-3.5 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/30 border border-indigo-200/60 dark:border-indigo-900/60 text-xs text-indigo-900 dark:text-indigo-200 flex items-start gap-3">
                <i class="bx bx-info-circle text-lg text-indigo-600 shrink-0 mt-0.5"></i>
                <div class="space-y-0.5">
                    <span class="font-bold block">Automated Statutory Calculation:</span>
                    <span>This run will automatically process all <strong>{{ $activeEmployeesCount }} active employees</strong> under the statutory rates for EPF (11%/12%/13%), SOCSO Act 4, June 2026 SKBBK, EIS, and PCB.</span>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="md" type="button" onclick="closeModal('payroll-run-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="md" type="submit" icon="bx-calculator">
                    Calculate &amp; Run Batch
                </x-button>
            </div>
        </form>
    </x-modal>

</x-layouts.admin>

