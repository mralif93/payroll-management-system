<x-layouts.admin title="Salary & Statutory Profiles">

    <div class="space-y-8">

        <!-- Flash Messages -->
        @if(session('success') || session('status'))
            <x-alert type="success" dismissible="true">
                {{ session('success') ?? session('status') }}
            </x-alert>
        @endif

        @if(session('error'))
            <x-alert type="danger" dismissible="true">
                {{ session('error') }}
            </x-alert>
        @endif

        <!-- Executive Page Hero Banner & Navigation Tabs -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 text-white p-6 sm:p-7 shadow-lg shadow-indigo-950/20 border border-indigo-800/40">
            <!-- Background Decorative Glow -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-20 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <div class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 font-bold text-base shadow-xs">
                            <i class="bx bx-wallet-alt"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Salary &amp; Statutory Profiles</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-indigo-400 animate-pulse"></span>
                            Payroll &amp; Compliance Hub
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        Configure contractual wage packages, itemized fixed allowances, bank autopay routing, and Malaysian statutory schemes (KWSP, SOCSO, SKBBK, EIS, LHDN PCB).
                    </p>
                </div>

                <!-- Navigation Switcher -->
                <div class="flex items-center gap-2 bg-white/10 dark:bg-slate-900/60 p-1.5 rounded-2xl backdrop-blur-md border border-white/10 shrink-0">
                    <a 
                        href="{{ route('admin.employees.index') }}"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold text-indigo-200 hover:text-white hover:bg-white/10 transition flex items-center gap-2"
                    >
                        <i class="bx bx-group text-sm"></i>
                        <span>Staff Directory</span>
                    </a>
                    <a 
                        href="{{ route('admin.employees.statutory') }}"
                        class="px-3.5 py-2 rounded-xl text-xs font-bold text-white bg-indigo-600 shadow-md shadow-indigo-600/30 transition flex items-center gap-2"
                    >
                        <i class="bx bx-wallet-alt text-sm"></i>
                        <span>Salary &amp; Statutory</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Total Monthly Basic"
                value="RM {{ number_format($employees->sum('basic_salary'), 2) }}"
                change="Base Wage Commitment"
                changeType="positive"
                icon="bx-money"
                color="indigo"
            />
            <x-stat-card 
                title="Fixed Allowances"
                value="{{ $availableAllowances->count() }} Types"
                change="Active Allowance Schemes"
                changeType="neutral"
                icon="bx-gift"
                color="teal"
            />
            <x-stat-card 
                title="EPF Statutory 11%"
                value="{{ $employees->filter(fn($e) => ($e->statutoryProfile?->epf_rate_type ?? 'standard_11') === 'standard_11')->count() }} Staff"
                change="Third Schedule Standard"
                changeType="positive"
                icon="bx-shield-quarter"
                color="emerald"
            />
            <x-stat-card 
                title="SKBBK Opt-Ins"
                value="{{ $employees->filter(fn($e) => (bool) ($e->statutoryProfile?->is_skbbk_contributed ?? false))->count() }} Staff"
                change="Lindung 24 Jam Enrolled"
                changeType="positive"
                icon="bx-check-shield"
                color="purple"
            />
        </div>

        <!-- Filter Suite -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-3.5 sm:p-4 bg-slate-50/50 dark:bg-slate-850/40 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                        <i class="bx bx-slider-alt"></i>
                    </span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Search &amp; Filter Compensation Profiles</span>
                </div>
                @if(request()->hasAny(['search', 'department_id', 'status']))
                    <a href="{{ route('admin.employees.statutory') }}" class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 hover:underline flex items-center gap-1">
                        <i class="bx bx-reset"></i>
                        <span>Clear All Filters</span>
                    </a>
                @endif
            </div>

            <div class="p-3.5 sm:p-4">
                <form method="GET" action="{{ route('admin.employees.statutory') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="bx bx-search text-base"></i>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search by employee name, staff ID, bank, or tax file no..." 
                            class="w-full pl-10 pr-10 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 dark:focus:border-indigo-400 transition"
                        >
                    </div>

                    <div class="flex flex-wrap items-center gap-2">
                        <div class="relative">
                            <select 
                                name="department_id" 
                                onchange="this.form.submit()" 
                                class="py-2 pl-3 pr-8 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                            >
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="relative">
                            <select 
                                name="status" 
                                onchange="this.form.submit()" 
                                class="py-2 pl-3 pr-8 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                            >
                                <option value="">All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active Staff</option>
                                <option value="resigned" {{ request('status') == 'resigned' ? 'selected' : '' }}>Resigned / Inactive</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Compensation & Statutory Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bx-wallet-alt text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Employee Compensation &amp; Statutory Profiles</h2>
                </div>
                <span class="text-xs text-slate-400 font-mono">Showing {{ $employees->count() }} of {{ $employees->total() }} profiles</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs min-w-[840px]">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5 whitespace-nowrap">Employee</th>
                            <th class="p-3.5 whitespace-nowrap">Monthly Basic</th>
                            <th class="p-3.5 whitespace-nowrap">Fixed Allowances</th>
                            <th class="p-3.5 whitespace-nowrap">KWSP / EPF</th>
                            <th class="p-3.5 whitespace-nowrap">SOCSO &amp; SKBBK</th>
                            <th class="p-3.5 whitespace-nowrap">LHDN PCB Tax</th>
                            <th class="p-3.5 whitespace-nowrap">Disbursement Bank</th>
                            <th class="p-3.5 text-right whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($employees as $emp)
                            @php
                                $allowancesTotal = $emp->salaryComponents->where('salaryComponent.type', 'allowance')->sum('amount');
                                $statProfile = $emp->statutoryProfile;
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <!-- Employee Name & ID -->
                                <td class="p-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-xs shadow-xs shrink-0">
                                            {{ substr($emp->full_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 dark:text-white block">{{ $emp->full_name }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $emp->employee_no }} • {{ $emp->designation ?? 'Staff' }}</span>
                                        </div>
                                    </div>
                                </td>

                                <!-- Monthly Basic Salary -->
                                <td class="p-3.5 font-mono font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                    RM {{ number_format($emp->basic_salary, 2) }}
                                </td>

                                <!-- Fixed Allowances -->
                                <td class="p-3.5 whitespace-nowrap font-mono">
                                    @if($allowancesTotal > 0)
                                        <span class="font-bold text-teal-600 dark:text-teal-400">+ RM {{ number_format($allowancesTotal, 2) }}</span>
                                        <span class="text-[10px] text-slate-400 block">{{ $emp->salaryComponents->where('salaryComponent.type', 'allowance')->count() }} items</span>
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>

                                <!-- EPF Profile -->
                                <td class="p-3.5 whitespace-nowrap">
                                    <div class="space-y-1">
                                        @if($statProfile?->epf_rate_type === 'standard_11')
                                            <x-badge variant="indigo" size="sm">Standard 11%</x-badge>
                                        @elseif($statProfile?->epf_rate_type === 'reduced_9')
                                            <x-badge variant="amber" size="sm">Reduced 9%</x-badge>
                                        @else
                                            <x-badge variant="purple" size="sm">Custom {{ $statProfile?->epf_employee_custom_rate ?? 0 }}%</x-badge>
                                        @endif
                                        <span class="text-[10px] text-slate-400 font-mono block">{{ $statProfile?->epf_member_no ?? 'No EPF No' }}</span>
                                    </div>
                                </td>

                                <!-- SOCSO & SKBBK -->
                                <td class="p-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        @if($statProfile?->socso_category === 'category_2_injury_only')
                                            <x-badge variant="purple" size="sm">Cat 2 Injury</x-badge>
                                        @else
                                            <x-badge variant="indigo" size="sm">Act 4 Full</x-badge>
                                        @endif

                                        @if($statProfile?->is_skbbk_contributed)
                                            <x-badge variant="emerald" size="sm">SKBBK 24H</x-badge>
                                        @else
                                            <x-badge variant="slate" size="sm">No SKBBK</x-badge>
                                        @endif
                                    </div>
                                </td>

                                <!-- LHDN PCB Tax Profile -->
                                <td class="p-3.5 whitespace-nowrap">
                                    <span class="font-bold text-slate-800 dark:text-slate-200 text-xs block uppercase font-mono">
                                        {{ $statProfile?->tax_category ?? 'SINGLE' }} ({{ $statProfile?->number_of_children ?? 0 }}k)
                                    </span>
                                    <span class="text-[10px] text-slate-400 font-mono">{{ $statProfile?->income_tax_no ?? 'No Tax No' }}</span>
                                </td>

                                <!-- Disbursement Bank -->
                                <td class="p-3.5 whitespace-nowrap font-mono text-xs">
                                    @if($emp->bank_name && $emp->bank_account_no)
                                        <span class="font-bold text-slate-800 dark:text-slate-200 block">{{ $emp->bank_name }}</span>
                                        <span class="text-[10px] text-slate-400">{{ $emp->bank_account_no }}</span>
                                    @else
                                        <span class="text-slate-400 italic">Not Assigned</span>
                                    @endif
                                </td>

                                <!-- Action -->
                                <td class="p-3.5 text-right whitespace-nowrap">
                                    <x-action-button variant="purple" icon="bx-edit-alt" title="Edit Salary & Statutory Settings" onclick="openStatutoryModal({{ json_encode($emp) }})">
                                        Configure
                                    </x-action-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="p-8 text-center text-slate-400">
                                    No employee records found matching filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employees->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $employees->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- MODAL: Edit Salary & Statutory Settings -->
    <x-modal id="edit-statutory-modal" title="Configure Salary & Statutory Profile" subtitle="Update basic wages, fixed allowances, EPF/SOCSO schemes, and tax tags" icon="bx-wallet-alt" iconBg="bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400" size="2xl">
        <form id="edit-statutory-form" method="POST" action="" class="space-y-6 text-left">
            @csrf
            @method('PUT')

            <!-- Section 1: Monthly Basic Salary & Bank AutoPay -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold">1</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Basic Salary &amp; Bank AutoPay Details</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <x-input label="Monthly Basic Salary (RM)" name="basic_salary" id="stat-basic-salary" type="number" step="0.01" required placeholder="6000.00" icon="bx-money" />
                    <x-input label="Bank Name" name="bank_name" id="stat-bank-name" optional placeholder="Maybank, CIMB, RHB..." icon="bx-buildings" />
                    <x-input label="Bank Account Number" name="bank_account_no" id="stat-bank-account-no" optional placeholder="e.g. 514012345678" icon="bx-credit-card" />
                </div>
            </div>

            <!-- Section 2: Fixed Monthly Allowances (RM) -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xs font-bold">2</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Fixed Monthly Allowances (RM) <span class="text-[10px] font-normal text-slate-400 dark:text-slate-500 lowercase">(optional)</span></h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    @foreach($availableAllowances as $allowance)
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 truncate" title="{{ $allowance->name }}">
                                    {{ $allowance->name }} <span class="text-[10px] font-normal text-slate-400 dark:text-slate-500">(optional)</span>
                                </label>
                                <span class="text-[10px] font-medium {{ $allowance->is_epf_subject ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400' }}">
                                    {{ $allowance->is_epf_subject ? 'EPF/SOCSO' : 'Tax Exempt' }}
                                </span>
                            </div>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-xs font-bold text-slate-400">RM</span>
                                <input 
                                    type="number" 
                                    step="0.01" 
                                    min="0" 
                                    id="stat-allowance-{{ $allowance->id }}" 
                                    name="allowances[{{ $allowance->id }}]" 
                                    placeholder="0.00" 
                                    class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 pl-10 pr-3.5 py-2.5 text-slate-900 dark:text-white font-mono placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"
                                >
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 3: Malaysian Statutory Schemes (KWSP, SOCSO, SKBBK, EIS, PCB) -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">3</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Malaysian Statutory Coverage &amp; Tax Profile</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <!-- EPF Config -->
                    <x-input label="EPF Member No." name="epf_member_no" id="stat-epf-no" optional placeholder="e.g. 12345678" icon="bx-shield-quarter" />
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            EPF Employee Rate <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <div class="relative">
                            <select name="epf_rate_type" id="stat-epf-rate-type" onchange="toggleCustomEpfFields(this.value)" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="standard_11">Standard Statutory (11.0% EE)</option>
                                <option value="reduced_9">Voluntary Reduced Rate (9.0% EE)</option>
                                <option value="custom">Custom Specified Rate (%)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Custom EPF Rates Row (Conditional) -->
                    <div id="stat-custom-epf-container" class="hidden sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3.5 p-3 rounded-xl bg-indigo-50/50 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-800/50">
                        <x-input label="Custom Employee EPF Rate (%)" name="epf_employee_custom_rate" id="stat-custom-epf-ee" type="number" step="0.01" placeholder="e.g. 2.00" />
                        <x-input label="Custom Employer EPF Rate (%)" name="epf_employer_custom_rate" id="stat-custom-epf-er" type="number" step="0.01" placeholder="e.g. 2.00" />
                    </div>

                    <!-- SOCSO Config -->
                    <x-input label="SOCSO Member No." name="socso_member_no" id="stat-socso-no" optional placeholder="e.g. A12345678" icon="bx-plus-medical" />
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            SOCSO Category <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <div class="relative">
                            <select name="socso_category" id="stat-socso-category" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="category_1_full">Category 1 (Employment Injury &amp; Invalidity Scheme)</option>
                                <option value="category_2_injury_only">Category 2 (Employment Injury Only - Age 60+ / Foreigners)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Statutory Checkbox Toggles -->
                    <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3.5 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="is_eis_contributed" value="1" id="stat-eis-checkbox" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-700">
                            <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">SIP / EIS (Act 800) Coverage</span>
                        </label>
                        <label class="flex items-center gap-2.5 cursor-pointer">
                            <input type="checkbox" name="is_skbbk_contributed" value="1" id="stat-skbbk-checkbox" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-700">
                            <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">SKBBK 2026 (Lindung 24 Jam - 0.75%)</span>
                        </label>
                    </div>

                    <!-- LHDN PCB Tax Fields -->
                    <x-input label="LHDN Income Tax File No. (SG/OG)" name="income_tax_no" id="stat-tax-no" optional placeholder="e.g. SG 12345678000" icon="bx-calculator" />
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">
                            Tax Marital Status <span class="text-rose-500 font-bold">*</span>
                        </label>
                        <div class="relative">
                            <select name="tax_category" id="stat-tax-category" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="single">Single / Individual</option>
                                <option value="married_non_working">Married (Spouse Not Working)</option>
                                <option value="married_working">Married (Spouse Working / Separate)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <x-input label="Number of Eligible Children" name="number_of_children" id="stat-children" type="number" min="0" required placeholder="0" />
                    <x-input label="Monthly Zakat / Fitrah Rebate (RM)" name="monthly_zakat_amount" id="stat-zakat" type="number" step="0.01" optional placeholder="0.00" />

                    <!-- Tax Residency and Special Deductions -->
                    <div class="sm:col-span-2 grid grid-cols-1 sm:grid-cols-3 gap-3 p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_tax_resident" value="1" id="stat-tax-resident" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-700">
                            <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Tax Resident (Residen)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_disabled" value="1" id="stat-disabled" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-700">
                            <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Disabled Person (OKU)</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="spouse_is_disabled" value="1" id="stat-spouse-disabled" class="w-4 h-4 rounded text-indigo-600 focus:ring-indigo-500 border-slate-300 dark:border-slate-700">
                            <span class="text-xs font-semibold text-slate-800 dark:text-slate-200">Disabled Spouse (OKU)</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-statutory-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit" icon="bx-save">
                    Save Compensation Settings
                </x-button>
            </div>
        </form>
    </x-modal>

    @push('scripts')
    <script>
        function toggleCustomEpfFields(rateType) {
            const container = document.getElementById('stat-custom-epf-container');
            if (rateType === 'custom') {
                container.classList.remove('hidden');
            } else {
                container.classList.add('hidden');
            }
        }

        function openStatutoryModal(emp) {
            const form = document.getElementById('edit-statutory-form');
            form.action = `/admin/employees/${emp.id}/statutory`;

            document.getElementById('stat-basic-salary').value = parseFloat(emp.basic_salary || 0).toFixed(2);
            document.getElementById('stat-bank-name').value = emp.bank_name || '';
            document.getElementById('stat-bank-account-no').value = emp.bank_account_no || '';

            // Reset allowance inputs
            document.querySelectorAll('[id^="stat-allowance-"]').forEach(input => input.value = '');

            // Populate assigned allowances
            if (emp.salary_components && emp.salary_components.length > 0) {
                emp.salary_components.forEach(comp => {
                    const inputEl = document.getElementById(`stat-allowance-${comp.salary_component_id}`);
                    if (inputEl) {
                        inputEl.value = parseFloat(comp.amount || 0).toFixed(2);
                    }
                });
            }

            // Populate Statutory Profile
            const stat = emp.statutory_profile || {};
            document.getElementById('stat-epf-no').value = stat.epf_member_no || '';
            document.getElementById('stat-epf-rate-type').value = stat.epf_rate_type || 'standard_11';
            document.getElementById('stat-custom-epf-ee').value = stat.epf_employee_custom_rate || '';
            document.getElementById('stat-custom-epf-er').value = stat.epf_employer_custom_rate || '';
            toggleCustomEpfFields(stat.epf_rate_type || 'standard_11');

            document.getElementById('stat-socso-no').value = stat.socso_member_no || '';
            document.getElementById('stat-socso-category').value = stat.socso_category || 'category_1_full';

            document.getElementById('stat-eis-checkbox').checked = stat.is_eis_contributed !== undefined ? Boolean(stat.is_eis_contributed) : true;
            document.getElementById('stat-skbbk-checkbox').checked = Boolean(stat.is_skbbk_contributed);

            document.getElementById('stat-tax-no').value = stat.income_tax_no || '';
            document.getElementById('stat-tax-category').value = stat.tax_category || 'single';
            document.getElementById('stat-children').value = stat.number_of_children || 0;
            document.getElementById('stat-zakat').value = parseFloat(stat.monthly_zakat_amount || 0).toFixed(2);

            document.getElementById('stat-tax-resident').checked = stat.is_tax_resident !== undefined ? Boolean(stat.is_tax_resident) : true;
            document.getElementById('stat-disabled').checked = Boolean(stat.is_disabled);
            document.getElementById('stat-spouse-disabled').checked = Boolean(stat.spouse_is_disabled);

            openModal('edit-statutory-modal');
        }
    </script>
    @endpush

</x-layouts.admin>
