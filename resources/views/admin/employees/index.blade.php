<x-layouts.admin title="Employee Master Directory & Registry">

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

        <!-- Executive Page Hero Banner & Action Suite -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900 via-slate-900 to-indigo-950 text-white p-6 sm:p-7 shadow-lg shadow-indigo-950/20 border border-indigo-800/40">
            <!-- Background Decorative Glow -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-20 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <div class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 font-bold text-base shadow-xs">
                            <i class="bx bx-group"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Employee Master Directory</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            {{ $employees->total() }} Active Staff
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        Manage workforce registry, Malaysian statutory identities (KWSP, SOCSO, EIS, PCB), bank accounts, and salary structures.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap shrink-0">
                    <button 
                        type="button" 
                        onclick="openModal('register-employee-modal')"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition flex items-center gap-2 cursor-pointer hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bx bx-user-plus text-base"></i>
                        <span>Register Employee</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Active Headcount"
                value="{{ $employees->where('employment_status', 'active')->count() }}"
                change="On Active Payroll"
                changeType="positive"
                icon="bx-user-check"
                color="indigo"
            />
            <x-stat-card 
                title="Total Registered"
                value="{{ $employees->total() }}"
                change="Master Staff Roster"
                changeType="positive"
                icon="bx-group"
                color="emerald"
            />
            <x-stat-card 
                title="Departments"
                value="{{ $departments->count() }}"
                change="Organizational Units"
                changeType="neutral"
                icon="bx-buildings"
                color="purple"
            />
            <x-stat-card 
                title="Resigned / Inactive"
                value="{{ $employees->where('employment_status', '!=', 'active')->count() }}"
                change="Offboarded Staff"
                changeType="negative"
                icon="bx-user-x"
                color="rose"
            />
        </div>

        <!-- Modern Search & Filter Command Bar -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-3 sm:p-4">
            <form method="GET" action="{{ route('admin.employees.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                
                <!-- Main Search Input with Clear Button -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                        <i class="bx bx-search text-lg"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search employees by name, staff ID (e.g. EMP-00104), designation, or NRIC..." 
                        class="w-full pl-10 pr-10 py-2.5 rounded-xl text-xs bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 dark:focus:border-indigo-400 transition-all font-sans"
                    >
                    @if(request('search'))
                        <a href="{{ route('admin.employees.index', request()->except('search')) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="bx bx-x-circle text-base"></i>
                        </a>
                    @endif
                </div>

                <!-- Filter Dropdowns & Actions -->
                <div class="flex flex-wrap items-center gap-2.5">
                    
                    <!-- Department Filter -->
                    <div class="relative min-w-[160px] flex-1 sm:flex-initial">
                        <select name="department_id" onchange="this.form.submit()" class="w-full text-xs font-semibold rounded-xl border border-slate-200 dark:border-slate-700/80 bg-slate-50/80 dark:bg-slate-800/60 pl-3 pr-8 py-2.5 text-slate-700 dark:text-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 cursor-pointer appearance-none">
                            <option value="">Dept: All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                            <i class="bx bx-chevron-down text-base"></i>
                        </div>
                    </div>

                    <x-button variant="primary" size="md" type="submit" icon="bx-filter-alt">
                        Filter
                    </x-button>

                    @if(request()->hasAny(['search', 'department_id']))
                        <a href="{{ route('admin.employees.index') }}" class="inline-flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-950/50 hover:bg-rose-100 dark:hover:bg-rose-900/60 transition">
                            <i class="bx bx-reset"></i>
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <!-- Active Filter Badges -->
            @if(request()->hasAny(['search', 'department_id']))
                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-slate-100 dark:border-slate-800 text-[11px] flex-wrap">
                    <span class="text-slate-400 font-bold uppercase tracking-wider text-[10px]">Active Filters:</span>
                    @if(request('search'))
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 font-medium">
                            Keyword: "{{ request('search') }}"
                            <a href="{{ route('admin.employees.index', request()->except('search')) }}" class="hover:text-indigo-900 dark:hover:text-white"><i class="bx bx-x"></i></a>
                        </span>
                    @endif
                    @if(request('department_id'))
                        @php $activeDept = $departments->firstWhere('id', request('department_id')); @endphp
                        @if($activeDept)
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 font-medium">
                                Department: {{ $activeDept->name }}
                                <a href="{{ route('admin.employees.index', request()->except('department_id')) }}" class="hover:text-purple-900 dark:hover:text-white"><i class="bx bx-x"></i></a>
                            </span>
                        @endif
                    @endif
                </div>
            @endif
        </div>

        <!-- Employees Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bx-group text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Active Employee Roster</h2>
                </div>
                <span class="text-xs text-slate-400 font-mono">Showing {{ $employees->count() }} of {{ $employees->total() }} staff</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Employee</th>
                            <th class="p-3.5">Designation &amp; Dept</th>
                            <th class="p-3.5">Basic Wage</th>
                            <th class="p-3.5">Statutory Flags</th>
                            <th class="p-3.5">Tax Category</th>
                            <th class="p-3.5">Status</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($employees as $emp)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="p-3.5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-xs shadow-xs">
                                            {{ substr($emp->full_name, 0, 2) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 dark:text-white block">{{ $emp->full_name }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $emp->employee_no }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="p-3.5">
                                    <span class="font-medium text-slate-800 dark:text-slate-200 block">{{ $emp->designation ?? 'General Staff' }}</span>
                                    <span class="text-[10px] text-slate-400">{{ $emp->department?->name ?? 'Headquarters' }}</span>
                                </td>
                                <td class="p-3.5 font-mono font-bold text-slate-900 dark:text-white">
                                    RM {{ number_format($emp->basic_salary, 2) }}
                                </td>
                                <td class="p-3.5">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <x-badge variant="indigo" size="sm">EPF 11%</x-badge>
                                        @if($emp->statutoryProfile?->is_skbbk_contributed)
                                            <x-badge variant="purple" size="sm">SKBBK</x-badge>
                                        @else
                                            <x-badge variant="slate" size="sm">No SKBBK</x-badge>
                                        @endif
                                        @if($emp->statutoryProfile?->is_eis_contributed)
                                            <x-badge variant="blue" size="sm">EIS</x-badge>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-3.5 font-mono text-[11px]">
                                    {{ strtoupper($emp->statutoryProfile?->tax_category ?? 'SINGLE') }}
                                </td>
                                <td class="p-3.5">
                                    @if($emp->employment_status === 'active')
                                        <x-badge variant="emerald" dot="true">Active</x-badge>
                                    @elseif($emp->employment_status === 'probation')
                                        <x-badge variant="amber" dot="true">Probation</x-badge>
                                    @else
                                        <x-badge variant="rose" dot="true">{{ ucfirst($emp->employment_status) }}</x-badge>
                                    @endif
                                </td>
                                <td class="p-3.5 text-right">
                                    <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                        <!-- View / Show Details -->
                                        <x-action-button variant="indigo" icon="bx-show" title="View Profile" onclick="openShowEmployeeModal({{ json_encode($emp) }}, '{{ addslashes($emp->department?->name ?? 'General') }}')">
                                            View
                                        </x-action-button>
                                        
                                        <!-- Edit Employee -->
                                        <x-action-button variant="purple" icon="bx-pencil" title="Edit Employee" onclick="openEditEmployeeModal({{ json_encode($emp) }})">
                                            Edit
                                        </x-action-button>

                                        <!-- Toggle Status (Resign / Reactivate) -->
                                        @if($emp->employment_status === 'active')
                                            <x-action-button variant="warning" icon="bx-user-x" title="Mark as Resigned" onclick="confirmToggleEmployeeStatus({{ $emp->id }}, '{{ addslashes($emp->full_name) }}', 'resign')">
                                                Resign
                                            </x-action-button>
                                        @else
                                            <x-action-button variant="emerald" icon="bx-user-check" title="Reactivate Employee" onclick="confirmToggleEmployeeStatus({{ $emp->id }}, '{{ addslashes($emp->full_name) }}', 'activate')">
                                                Activate
                                            </x-action-button>
                                        @endif

                                        <!-- Delete Employee -->
                                        <x-action-button variant="rose" icon="bx-trash" title="Delete Employee" onclick="confirmDeleteEmployee({{ $emp->id }}, '{{ addslashes($emp->full_name) }}')">
                                            Delete
                                        </x-action-button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    No employee records found. Click "Register Employee" to add staff.
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

    <!-- 1. REGISTER EMPLOYEE MODAL (User Friendly Tabbed / Sectioned Design) -->
    <x-modal id="register-employee-modal" title="Register New Employee" subtitle="Create master staff record with default Malaysian statutory profile" icon="bx-user-plus" size="2xl">
        <form method="POST" action="{{ route('admin.employees.store') }}" class="space-y-6 text-left">
            @csrf
            <input type="hidden" name="company_id" value="1">

            <!-- Section 1: Personal & Identity Information -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold">1</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Personal &amp; Identity Details</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2">
                        <x-input label="Full Legal Name" name="full_name" required placeholder="e.g. Ahmad bin Mustaffa" icon="bx-user" />
                    </div>
                    <x-input label="Email Address" name="email" type="email" placeholder="ahmad@company.com" icon="bx-envelope" />
                    <x-input label="Phone Number" name="phone_number" placeholder="+60123456789" icon="bx-phone" />
                    <x-input label="Staff ID" name="employee_no" required placeholder="e.g. EMP-00105" icon="bx-id-card" />
                    <x-input label="NRIC / Passport No." name="nric_passport" required placeholder="880415-14-5531" icon="bx-card" />
                    <x-input label="Date of Birth" name="birth_date" type="date" required icon="bx-calendar" />
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Gender</label>
                        <div class="relative">
                            <select name="gender" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="male">Male (Lelaki)</option>
                                <option value="female">Female (Perempuan)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Citizenship &amp; Tax Status</label>
                        <div class="relative">
                            <select name="citizenship" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="malaysian">Malaysian Citizen (Warganegara)</option>
                                <option value="permanent_resident">Permanent Resident (PR)</option>
                                <option value="foreign_worker">Foreign Expatriate / Worker</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Employment & Salary Package -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">2</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Employment &amp; Compensation</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Department</label>
                        <div class="relative">
                            <select name="department_id" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="">Select Department...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <x-input label="Designation / Position" name="designation" placeholder="e.g. Senior Software Engineer" icon="bx-briefcase-alt" />
                    
                    <x-input label="Monthly Basic Salary (RM)" name="basic_salary" type="number" step="0.01" required placeholder="6500.00" icon="bx-money" />
                    
                    <x-input label="Joined Date" name="joined_date" type="date" required icon="bx-calendar-check" />

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Employment Type</label>
                        <div class="relative">
                            <select name="employment_type" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="permanent">Permanent Staff</option>
                                <option value="contract">Contract</option>
                                <option value="intern">Intern</option>
                                <option value="part_time">Part Time</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Monthly Fixed Allowances & Stipends -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xs font-bold">3</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Fixed Monthly Allowances (RM)</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach($availableAllowances as $allowance)
                        <div class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1 truncate" title="{{ $allowance->name }}">{{ $allowance->name }}</label>
                            <div class="relative">
                                <input type="number" step="0.01" min="0" name="allowances[{{ $allowance->id }}]" placeholder="0.00" class="w-full text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-2 text-slate-900 dark:text-white font-mono">
                            </div>
                            <span class="text-[9px] text-slate-400 block mt-1">
                                {{ $allowance->is_epf_subject ? 'EPF/SOCSO' : 'Exempt' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 4: Banking & Disbursement -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">4</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Disbursement &amp; Banking Details</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <x-input label="Disbursement Bank" name="bank_name" placeholder="Maybank, CIMB, Public Bank, RHB..." icon="bx-buildings" />
                    <x-input label="Bank Account Number" name="bank_account_no" placeholder="e.g. 514012345678" icon="bx-credit-card" />
                </div>
            </div>

            <!-- Section 5: Malaysian Statutory Coverage & Options -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs font-bold">5</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Statutory Scheme Toggles &amp; EPF Rates</h4>
                </div>

                <!-- EPF Rate Configuration -->
                <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">KWSP / EPF Employee Rate</label>
                            <div class="relative">
                                <select name="epf_rate_type" id="create-emp-epf-rate-type" onchange="toggleCustomEpfFields(this.value, 'create')" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                    <option value="standard_11">Standard Statutory (11.0% EE)</option>
                                    <option value="reduced_9">Voluntary Reduced Rate (9.0% EE)</option>
                                    <option value="custom">Custom Specified Rate (%)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                    <i class="bx bx-chevron-down text-base"></i>
                                </div>
                            </div>
                        </div>

                        <div id="create-custom-epf-container" class="hidden">
                            <x-input label="Custom Employee EPF Rate (%)" name="epf_employee_custom_rate" id="create-emp-epf-custom-rate" type="number" step="0.5" placeholder="e.g. 13.0" icon="bx-percentage" />
                        </div>
                    </div>
                </div>

                <!-- Statutory Account & Member Numbers -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <x-input label="KWSP / EPF Member No." name="epf_member_no" placeholder="e.g. 12345678" icon="bx-shield-quarter" />
                    <x-input label="PERKESO / SOCSO No." name="socso_member_no" placeholder="e.g. A12345678" icon="bx-check-shield" />
                    <x-input label="LHDN Income Tax No." name="income_tax_no" placeholder="e.g. SG 123456780" icon="bx-calculator" />
                </div>

                <!-- SKBBK & EIS Checkboxes -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-indigo-50/20 transition cursor-pointer">
                        <input type="checkbox" name="is_skbbk_contributed" value="1" checked class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-xs font-bold text-slate-900 dark:text-white block">PERKESO SKBBK (Lindung 24 Jam)</span>
                            <span class="text-[11px] text-slate-400 block leading-tight mt-0.5">24-hour non-employment injury scheme (Uncheck to Opt Out)</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-indigo-50/20 transition cursor-pointer">
                        <input type="checkbox" name="is_eis_contributed" value="1" checked class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-xs font-bold text-slate-900 dark:text-white block">SIP / EIS Contribution (Act 800)</span>
                            <span class="text-[11px] text-slate-400 block leading-tight mt-0.5">Employment Insurance System (0.2% EE / 0.2% ER)</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="md" type="button" onclick="closeModal('register-employee-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="md" type="submit" icon="bx-check">
                    Register Employee
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 2. EDIT EMPLOYEE MODAL (Sectioned & User-Friendly) -->
    <x-modal id="edit-employee-modal" title="Edit Employee Profile" subtitle="Update designation, compensation, contact, and employment status" icon="bx-pencil" size="2xl">
        <form id="edit-employee-form" method="POST" action="" class="space-y-6 text-left">
            @csrf
            @method('PUT')

            <!-- Section 1: Identity & Contact -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs font-bold">1</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Personal &amp; Identity Details</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div class="sm:col-span-2">
                        <x-input label="Full Legal Name" name="full_name" id="edit-emp-name" required icon="bx-user" />
                    </div>
                    <x-input label="Email Address" name="email" id="edit-emp-email" type="email" icon="bx-envelope" />
                    <x-input label="Contact Phone" name="phone_number" id="edit-emp-phone" icon="bx-phone" />
                    <x-input label="Staff ID" name="employee_no" id="edit-emp-no" disabled icon="bx-id-card" helper="System Master Identifier" />
                    <x-input label="NRIC / Passport No." name="nric_passport" id="edit-emp-nric" required icon="bx-card" />
                    <x-input label="Date of Birth" name="birth_date" id="edit-emp-birth" type="date" required icon="bx-calendar" />

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Gender</label>
                        <div class="relative">
                            <select name="gender" id="edit-emp-gender" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="male">Male (Lelaki)</option>
                                <option value="female">Female (Perempuan)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Citizenship &amp; Tax Status</label>
                        <div class="relative">
                            <select name="citizenship" id="edit-emp-citizenship" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="malaysian">Malaysian Citizen (Warganegara)</option>
                                <option value="permanent_resident">Permanent Resident (PR)</option>
                                <option value="foreign_worker">Foreign Expatriate / Worker</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 2: Position, Compensation & Status -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-xs font-bold">2</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Employment &amp; Compensation</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Department</label>
                        <div class="relative">
                            <select name="department_id" id="edit-emp-dept" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="">Select Department...</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}">{{ $dept->name }} ({{ $dept->code ?? 'N/A' }})</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <x-input label="Designation / Role" name="designation" id="edit-emp-designation" icon="bx-briefcase-alt" />
                    
                    <x-input label="Monthly Basic Salary (RM)" name="basic_salary" id="edit-emp-salary" type="number" step="0.01" required icon="bx-money" />

                    <x-input label="Joined Date" name="joined_date" id="edit-emp-joined" type="date" required icon="bx-calendar-check" />
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Employment Status</label>
                        <div class="relative">
                            <select name="employment_status" id="edit-emp-status" onchange="toggleResignedDateField(this.value)" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="active">Active (On Payroll)</option>
                                <option value="probation">Probation</option>
                                <option value="confirmed">Confirmed</option>
                                <option value="resigned">Resigned / Inactive</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Employment Type</label>
                        <div class="relative">
                            <select name="employment_type" id="edit-emp-type" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                <option value="permanent">Permanent Staff</option>
                                <option value="contract">Contract</option>
                                <option value="intern">Intern</option>
                                <option value="part_time">Part Time</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                <i class="bx bx-chevron-down text-base"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Resigned Date Field in Edit Modal -->
                    <div id="edit-emp-resigned-container" class="hidden sm:col-span-2 p-3 rounded-xl bg-rose-50/60 dark:bg-rose-950/30 border border-rose-200/60 dark:border-rose-900/60">
                        <x-input label="Official Resignation / Last Day Date" name="resigned_date" id="edit-emp-resigned-date" type="date" icon="bx-calendar-x" helper="Date employee formally offboarded from monthly payroll" />
                    </div>
                </div>
            </div>

            <!-- Section 3: Monthly Fixed Allowances & Stipends -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-teal-50 dark:bg-teal-950 text-teal-600 dark:text-teal-400 flex items-center justify-center text-xs font-bold">3</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Fixed Monthly Allowances (RM)</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    @foreach($availableAllowances as $allowance)
                        <div class="p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                            <label class="block text-[11px] font-bold text-slate-700 dark:text-slate-300 mb-1 truncate" title="{{ $allowance->name }}">{{ $allowance->name }}</label>
                            <div class="relative">
                                <input type="number" step="0.01" min="0" name="allowances[{{ $allowance->id }}]" id="edit-emp-allowance-{{ $allowance->id }}" placeholder="0.00" class="edit-emp-allowance-input w-full text-xs rounded-lg border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-2 text-slate-900 dark:text-white font-mono">
                            </div>
                            <span class="text-[9px] text-slate-400 block mt-1">
                                {{ $allowance->is_epf_subject ? 'EPF/SOCSO' : 'Exempt' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Section 4: Bank Disbursement -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-purple-50 dark:bg-purple-950 text-purple-600 dark:text-purple-400 flex items-center justify-center text-xs font-bold">4</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Disbursement &amp; Banking Details</h4>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <x-input label="Disbursement Bank" name="bank_name" id="edit-emp-bank-name" placeholder="Maybank, CIMB, Public Bank, RHB..." icon="bx-buildings" />
                    <x-input label="Bank Account Number" name="bank_account_no" id="edit-emp-bank-acc" placeholder="e.g. 514012345678" icon="bx-credit-card" />
                </div>
            </div>

            <!-- Section 5: Malaysian Statutory Coverage & Opt-Outs -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 pb-2 border-b border-slate-100 dark:border-slate-800">
                    <span class="w-6 h-6 rounded-lg bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400 flex items-center justify-center text-xs font-bold">5</span>
                    <h4 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Statutory Scheme Toggles &amp; EPF Rates</h4>
                </div>

                <!-- EPF Rate Configuration -->
                <div class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 space-y-3">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">KWSP / EPF Employee Rate</label>
                            <div class="relative">
                                <select name="epf_rate_type" id="edit-emp-epf-rate-type" onchange="toggleCustomEpfFields(this.value, 'edit')" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                                    <option value="standard_11">Standard Statutory (11.0% EE)</option>
                                    <option value="reduced_9">Voluntary Reduced Rate (9.0% EE)</option>
                                    <option value="custom">Custom Specified Rate (%)</option>
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                                    <i class="bx bx-chevron-down text-base"></i>
                                </div>
                            </div>
                        </div>

                        <div id="edit-custom-epf-container" class="hidden">
                            <x-input label="Custom Employee EPF Rate (%)" name="epf_employee_custom_rate" id="edit-emp-epf-custom-rate" type="number" step="0.5" placeholder="e.g. 13.0" icon="bx-percentage" />
                        </div>
                    </div>
                </div>

                <!-- Statutory Account & Member Numbers -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <x-input label="KWSP / EPF Member No." name="epf_member_no" id="edit-emp-epf-no" placeholder="e.g. 12345678" icon="bx-shield-quarter" />
                    <x-input label="PERKESO / SOCSO No." name="socso_member_no" id="edit-emp-socso-no" placeholder="e.g. A12345678" icon="bx-check-shield" />
                    <x-input label="LHDN Income Tax No." name="income_tax_no" id="edit-emp-tax-no" placeholder="e.g. SG 123456780" icon="bx-calculator" />
                </div>

                <!-- SKBBK & EIS Checkboxes -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-indigo-50/20 transition cursor-pointer">
                        <input type="checkbox" name="is_skbbk_contributed" id="edit-emp-skbbk" value="1" class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-xs font-bold text-slate-900 dark:text-white block">PERKESO SKBBK (Lindung 24 Jam)</span>
                            <span class="text-[11px] text-slate-400 block leading-tight mt-0.5">24-hour non-employment injury scheme (Uncheck to Opt Out)</span>
                        </div>
                    </label>

                    <label class="flex items-start gap-3 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40 hover:bg-indigo-50/20 transition cursor-pointer">
                        <input type="checkbox" name="is_eis_contributed" id="edit-emp-eis" value="1" class="mt-0.5 rounded text-indigo-600 focus:ring-indigo-500">
                        <div>
                            <span class="text-xs font-bold text-slate-900 dark:text-white block">SIP / EIS Contribution (Act 800)</span>
                            <span class="text-[11px] text-slate-400 block leading-tight mt-0.5">Employment Insurance System (0.2% EE / 0.2% ER)</span>
                        </div>
                    </label>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="md" type="button" onclick="closeModal('edit-employee-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="md" type="submit" icon="bx-save">
                    Save Changes
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 3. SHOW EMPLOYEE MODAL (Label | Value Design with Full Field Parity) -->
    <x-modal id="show-employee-modal" title="Employee Master Profile" subtitle="Complete staff identity, contact, statutory tags, and compensation" icon="bx-user" size="lg">
        <div class="space-y-4 text-left text-xs">
            
            <!-- Identity Banner -->
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-800 flex items-center gap-3.5">
                <div class="w-11 h-11 rounded-xl bg-gradient-to-tr from-indigo-600 to-indigo-500 text-white flex items-center justify-center text-sm font-extrabold shadow-sm shrink-0" id="show-emp-avatar">
                    EM
                </div>
                <div class="min-w-0 flex-1">
                    <h3 class="text-sm font-extrabold text-slate-900 dark:text-white truncate" id="show-emp-name">Employee Name</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-mono truncate" id="show-emp-designation">Designation</p>
                </div>
                <div id="show-emp-status-badge" class="shrink-0">
                    <x-badge variant="emerald" dot="true">Active</x-badge>
                </div>
            </div>

            <!-- Structured Label | Value Rows Table (Values strictly Right-Aligned) -->
            <div class="rounded-xl border border-slate-200 dark:border-slate-800 overflow-hidden bg-white dark:bg-slate-900 divide-y divide-slate-100 dark:divide-slate-800">
                
                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Employee ID</div>
                    <div class="text-right font-mono font-bold text-slate-900 dark:text-white" id="show-emp-no">EMP-00101</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">NRIC / Passport No.</div>
                    <div class="text-right font-mono font-bold text-slate-900 dark:text-white" id="show-emp-nric">—</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Date of Birth</div>
                    <div class="text-right font-mono text-slate-800 dark:text-slate-200" id="show-emp-birth">—</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Gender</div>
                    <div class="text-right text-slate-800 dark:text-slate-200 uppercase font-semibold text-[11px]" id="show-emp-gender">—</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Email Address</div>
                    <div class="text-right font-mono text-slate-800 dark:text-slate-200" id="show-emp-email">—</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Contact Phone</div>
                    <div class="text-right font-mono text-slate-800 dark:text-slate-200" id="show-emp-phone">—</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Department</div>
                    <div class="text-right font-medium text-slate-800 dark:text-slate-200" id="show-emp-dept">Technology</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Employment Type</div>
                    <div class="text-right font-medium text-slate-800 dark:text-slate-200 uppercase font-mono text-[11px]" id="show-emp-type">Permanent</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Monthly Basic Salary</div>
                    <div class="text-right font-mono font-extrabold text-slate-900 dark:text-white text-sm text-indigo-600 dark:text-indigo-400" id="show-emp-salary">RM 6,500.00</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Monthly Allowances</div>
                    <div class="text-right font-mono font-bold text-teal-600 dark:text-teal-400" id="show-emp-allowances">RM 0.00</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Citizenship</div>
                    <div class="text-right text-slate-800 dark:text-slate-200 uppercase font-semibold text-[11px]" id="show-emp-citizenship">Malaysian</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Disbursement Bank</div>
                    <div class="text-right font-mono text-slate-800 dark:text-slate-200" id="show-emp-bank">Maybank (514012345678)</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Joined Date</div>
                    <div class="text-right font-mono text-slate-800 dark:text-slate-200" id="show-emp-joined">01 Jan 2026</div>
                </div>

                <!-- Resigned Date Row (Dynamic if present) -->
                <div id="show-emp-resigned-row" class="hidden grid grid-cols-2 p-3 bg-rose-50/40 dark:bg-rose-950/20 hover:bg-rose-50/60 dark:hover:bg-rose-950/40 transition items-center">
                    <div class="font-bold text-rose-600 dark:text-rose-400">Resigned Date</div>
                    <div class="text-right font-mono font-bold text-rose-600 dark:text-rose-400" id="show-emp-resigned-val">—</div>
                </div>

                <div class="grid grid-cols-2 p-3 hover:bg-slate-50/50 dark:hover:bg-slate-800/30 transition items-center">
                    <div class="font-bold text-slate-500 dark:text-slate-400">Statutory Deductions</div>
                    <div class="flex items-center justify-end gap-1.5 flex-wrap" id="show-emp-statutory-badges">
                        <x-badge variant="indigo" size="sm">EPF 11%</x-badge>
                        <x-badge variant="purple" size="sm">SKBBK 2026</x-badge>
                        <x-badge variant="blue" size="sm">EIS</x-badge>
                    </div>
                </div>

            </div>

            <div class="flex justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('show-employee-modal')">
                    Close Details
                </x-button>
            </div>
        </div>
    </x-modal>

    <!-- 4. CONFIRM DELETE EMPLOYEE MODAL -->
    <x-confirm-modal 
        id="delete-employee-confirm-modal"
        title="Delete Employee Record"
        message="Are you sure you want to delete this employee record? Their statutory profile will also be removed."
        confirmText="Yes, Delete Record"
        confirmVariant="danger"
    />

    <!-- 5. CONFIRM TOGGLE EMPLOYEE STATUS MODAL -->
    <x-confirm-modal 
        id="toggle-employee-status-confirm-modal"
        title="Change Employment Status"
        message="Are you sure you want to update this employee's employment status?"
        confirmText="Confirm Status Update"
        confirmVariant="warning"
        icon="bx-user-x"
        iconBg="bg-amber-50 dark:bg-amber-950 text-amber-600 dark:text-amber-400"
    />

    <x-slot name="scripts">
        <script>
            function toggleResignedDateField(status) {
                const container = document.getElementById('edit-emp-resigned-container');
                if (status === 'resigned') {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }

            function toggleCustomEpfFields(rateType, modalType) {
                const container = document.getElementById(`${modalType}-custom-epf-container`);
                if (rateType === 'custom') {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                }
            }

            function openShowEmployeeModal(emp, deptName) {
                document.getElementById('show-emp-avatar').textContent = (emp.full_name || 'EM').substring(0, 2).toUpperCase();
                document.getElementById('show-emp-name').textContent = emp.full_name || '—';
                document.getElementById('show-emp-designation').textContent = (emp.designation || 'Staff') + ' • ' + deptName;
                document.getElementById('show-emp-no').textContent = emp.employee_no || '—';
                document.getElementById('show-emp-nric').textContent = emp.nric_passport || '—';
                document.getElementById('show-emp-birth').textContent = emp.birth_date ? new Date(emp.birth_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '—';
                document.getElementById('show-emp-gender').textContent = (emp.gender === 'female' ? 'Female (Perempuan)' : 'Male (Lelaki)');
                document.getElementById('show-emp-email').textContent = emp.email || '—';
                document.getElementById('show-emp-phone').textContent = emp.phone_number || '—';
                document.getElementById('show-emp-dept').textContent = deptName;
                document.getElementById('show-emp-type').textContent = (emp.employment_type || 'permanent').toUpperCase();
                document.getElementById('show-emp-salary').textContent = 'RM ' + parseFloat(emp.basic_salary || 0).toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                // Allowances Sum in Show Modal
                let totalAllowances = 0;
                if (emp.salary_components && emp.salary_components.length > 0) {
                    totalAllowances = emp.salary_components.reduce((sum, item) => sum + parseFloat(item.amount || 0), 0);
                }
                document.getElementById('show-emp-allowances').textContent = 'RM ' + totalAllowances.toLocaleString('en-MY', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

                document.getElementById('show-emp-citizenship').textContent = (emp.citizenship || 'malaysian').replace('_', ' ');
                document.getElementById('show-emp-bank').textContent = (emp.bank_name || 'Bank') + ' (' + (emp.bank_account_no || 'Not set') + ')';
                document.getElementById('show-emp-joined').textContent = emp.joined_date ? new Date(emp.joined_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : '—';

                // Dynamic Statutory Badges
                const statutoryContainer = document.getElementById('show-emp-statutory-badges');
                const epfType = emp.statutory_profile ? emp.statutory_profile.epf_rate_type : 'standard_11';
                let epfLabel = 'EPF 11%';
                if (epfType === 'reduced_9') epfLabel = 'EPF 9% (Voluntary)';
                else if (epfType === 'custom') epfLabel = `EPF ${parseFloat(emp.statutory_profile.epf_employee_custom_rate || 0)}% (Custom)`;

                const isSkbbk = emp.statutory_profile ? emp.statutory_profile.is_skbbk_contributed : true;
                const isEis = emp.statutory_profile ? emp.statutory_profile.is_eis_contributed : true;
                statutoryContainer.innerHTML = `
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">${epfLabel}</span>
                    ${isSkbbk ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">SKBBK</span>' : '<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 dark:bg-slate-800 text-slate-500 border border-slate-200 dark:border-slate-700">Opted Out (SKBBK)</span>'}
                    ${isEis ? '<span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">EIS</span>' : ''}
                `;

                const resignedRow = document.getElementById('show-emp-resigned-row');
                if (emp.employment_status === 'resigned' || emp.resigned_date) {
                    resignedRow.classList.remove('hidden');
                    document.getElementById('show-emp-resigned-val').textContent = emp.resigned_date ? new Date(emp.resigned_date).toLocaleDateString('en-GB', { day: '2-digit', month: 'short', year: 'numeric' }) : 'Recorded';
                } else {
                    resignedRow.classList.add('hidden');
                }

                const statusBadge = document.getElementById('show-emp-status-badge');
                if (emp.employment_status === 'active') {
                    statusBadge.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Active</span>';
                } else if (emp.employment_status === 'probation') {
                    statusBadge.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 dark:bg-amber-950/60 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800"><span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>Probation</span>';
                } else {
                    statusBadge.innerHTML = '<span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-rose-50 dark:bg-rose-950/60 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800"><span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>Resigned / Inactive</span>';
                }

                openModal('show-employee-modal');
            }

            function openEditEmployeeModal(emp) {
                const form = document.getElementById('edit-employee-form');
                form.action = `/admin/employees/${emp.id}`;

                document.getElementById('edit-emp-name').value = emp.full_name || '';
                document.getElementById('edit-emp-no').value = emp.employee_no || '';
                document.getElementById('edit-emp-nric').value = emp.nric_passport || '';
                document.getElementById('edit-emp-birth').value = emp.birth_date ? emp.birth_date.substring(0, 10) : '';
                document.getElementById('edit-emp-gender').value = emp.gender || 'male';
                document.getElementById('edit-emp-citizenship').value = emp.citizenship || 'malaysian';
                document.getElementById('edit-emp-designation').value = emp.designation || '';
                document.getElementById('edit-emp-dept').value = emp.department_id || '';
                document.getElementById('edit-emp-salary').value = emp.basic_salary || '';
                document.getElementById('edit-emp-joined').value = emp.joined_date ? emp.joined_date.substring(0, 10) : '';
                document.getElementById('edit-emp-status').value = emp.employment_status || 'active';
                document.getElementById('edit-emp-type').value = emp.employment_type || 'permanent';
                document.getElementById('edit-emp-bank-name').value = emp.bank_name || '';
                document.getElementById('edit-emp-bank-acc').value = emp.bank_account_no || '';
                document.getElementById('edit-emp-email').value = emp.email || '';
                document.getElementById('edit-emp-phone').value = emp.phone_number || '';
                document.getElementById('edit-emp-resigned-date').value = emp.resigned_date ? emp.resigned_date.substring(0, 10) : '';

                // Reset all allowance inputs in edit modal
                document.querySelectorAll('.edit-emp-allowance-input').forEach(input => input.value = '');

                // Populate existing allowances
                if (emp.salary_components && emp.salary_components.length > 0) {
                    emp.salary_components.forEach(comp => {
                        const allowanceInput = document.getElementById(`edit-emp-allowance-${comp.salary_component_id}`);
                        if (allowanceInput) {
                            allowanceInput.value = parseFloat(comp.amount || 0).toFixed(2);
                        }
                    });
                }

                // Statutory Numbers & Registration
                document.getElementById('edit-emp-epf-no').value = emp.statutory_profile ? (emp.statutory_profile.epf_member_no || '') : '';
                document.getElementById('edit-emp-socso-no').value = emp.statutory_profile ? (emp.statutory_profile.socso_member_no || '') : '';
                document.getElementById('edit-emp-tax-no').value = emp.statutory_profile ? (emp.statutory_profile.income_tax_no || '') : '';

                // Statutory EPF Rate & Checkboxes
                const epfType = emp.statutory_profile ? emp.statutory_profile.epf_rate_type : 'standard_11';
                const epfCustomRate = emp.statutory_profile ? emp.statutory_profile.epf_employee_custom_rate : '';
                document.getElementById('edit-emp-epf-rate-type').value = epfType;
                document.getElementById('edit-emp-epf-custom-rate').value = epfCustomRate || '';
                toggleCustomEpfFields(epfType, 'edit');

                const isSkbbk = emp.statutory_profile ? (emp.statutory_profile.is_skbbk_contributed == 1) : true;
                const isEis = emp.statutory_profile ? (emp.statutory_profile.is_eis_contributed == 1) : true;
                document.getElementById('edit-emp-skbbk').checked = isSkbbk;
                document.getElementById('edit-emp-eis').checked = isEis;

                toggleResignedDateField(emp.employment_status || 'active');

                openModal('edit-employee-modal');
            }

            function confirmDeleteEmployee(empId, empName) {
                const form = document.getElementById('delete-employee-confirm-modal-form');
                form.action = `/admin/employees/${empId}`;
                document.getElementById('delete-employee-confirm-modal-method').value = 'DELETE';
                document.getElementById('delete-employee-confirm-modal-message').textContent = `Are you sure you want to remove employee "${empName}" from the roster?`;
                openModal('delete-employee-confirm-modal');
            }

            function confirmToggleEmployeeStatus(empId, empName, action) {
                const form = document.getElementById('toggle-employee-status-confirm-modal-form');
                form.action = `/admin/employees/${empId}/toggle-status`;
                document.getElementById('toggle-employee-status-confirm-modal-method').value = 'POST';

                if (action === 'resign') {
                    document.getElementById('toggle-employee-status-confirm-modal-title').textContent = 'Mark Employee as Resigned';
                    document.getElementById('toggle-employee-status-confirm-modal-message').textContent = `Are you sure you want to mark "${empName}" as RESIGNED? They will be excluded from new monthly payroll calculations.`;
                    document.getElementById('toggle-employee-status-confirm-modal-btn').textContent = 'Yes, Mark Resigned';
                } else {
                    document.getElementById('toggle-employee-status-confirm-modal-title').textContent = 'Reactivate Employee';
                    document.getElementById('toggle-employee-status-confirm-modal-message').textContent = `Are you sure you want to REACTIVATE "${empName}"?`;
                    document.getElementById('toggle-employee-status-confirm-modal-btn').textContent = 'Yes, Reactivate';
                }

                openModal('toggle-employee-status-confirm-modal');
            }
        </script>
    </x-slot>

</x-layouts.admin>
