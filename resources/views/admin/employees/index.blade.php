<x-layouts.admin title="Employee Master Directory & Registry">

    <div class="space-y-8">

        <!-- Header Banner & Action Modal -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Employee Registry</h1>
                    <x-badge variant="indigo" dot="true">
                        {{ $employees->total() ?? 0 }} Staff Records
                    </x-badge>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Manage active staff, statutory identity tags, tax reliefs, and assigned salary structures.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <x-button variant="secondary" size="sm" icon="bx-export">
                    Export Directory CSV
                </x-button>
                <x-button variant="primary" size="sm" icon="bx-user-plus" onclick="document.getElementById('register-modal').showModal()">
                    Add New Employee
                </x-button>
            </div>
        </div>

        <!-- Metric Cards via UI Kit -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Active Headcount"
                value="{{ $employees->total() ?? 48 }}"
                change="98.2% Active status"
                changeType="positive"
                icon="bx-user-check"
                color="indigo"
            />
            <x-stat-card 
                title="EPF Registered (11%)"
                value="{{ $employees->total() ?? 48 }}"
                change="KWSP compliant"
                changeType="positive"
                icon="bx-shield-quarter"
                color="emerald"
            />
            <x-stat-card 
                title="SOCSO & SKBBK (2026)"
                value="{{ $employees->total() ?? 48 }}"
                change="Lindung 24 Jam active"
                changeType="positive"
                icon="bx-plus-medical"
                color="purple"
            />
            <x-stat-card 
                title="LHDN PCB Tax Active"
                value="34 Staff"
                change="Monthly MTD deduction"
                changeType="neutral"
                icon="bx-receipt"
                color="blue"
            />
        </div>

        <!-- Filter Bar -->
        <div class="p-4 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs flex flex-col md:flex-row gap-4 justify-between items-center">
            <form method="GET" action="{{ route('admin.employees.index') }}" class="w-full md:w-auto flex flex-1 items-center gap-3">
                <div class="w-full md:w-80">
                    <x-input 
                        type="text" 
                        name="search" 
                        placeholder="Search employee name, staff ID, NRIC..." 
                        value="{{ request('search') }}"
                        icon="bx-search" 
                    />
                </div>
                <x-button variant="secondary" size="md" type="submit">
                    Search
                </x-button>
            </form>
        </div>

        <!-- Employees Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bx-group text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Active Employee Roster</h2>
                </div>
                <span class="text-xs text-slate-400 font-mono">Page {{ $employees->currentPage() }} of {{ $employees->lastPage() }}</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Employee</th>
                            <th class="p-3.5">Designation</th>
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
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 font-bold flex items-center justify-center text-xs">
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
                                        <x-badge variant="purple" size="sm">SKBBK 2026</x-badge>
                                        <x-badge variant="blue" size="sm">EIS</x-badge>
                                    </div>
                                </td>
                                <td class="p-3.5 font-mono text-[11px]">
                                    {{ strtoupper($emp->statutoryProfile?->tax_category ?? 'SINGLE') }}
                                </td>
                                <td class="p-3.5">
                                    <x-badge variant="emerald" dot="true">Active</x-badge>
                                </td>
                                <td class="p-3.5 text-right">
                                    <x-action-button variant="indigo" icon="bx-user" href="{{ route('admin.employees.show', $emp) }}">
                                        View Profile
                                    </x-action-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    No employee records found. Click "Add New Employee" to register staff.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Register Employee Modal -->
    <x-modal id="register-modal" title="Register New Employee" size="lg">
        <form method="POST" action="{{ route('admin.employees.store') }}" class="space-y-4 text-left">
            @csrf
            <input type="hidden" name="company_id" value="1">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-input label="Full Legal Name" name="full_name" required placeholder="Ahmad bin Mustaffa" />
                <x-input label="Staff Employee ID" name="employee_no" required placeholder="EMP-00105" />
                <x-input label="NRIC / Passport No." name="nric_passport" required placeholder="880415-14-5531" />
                <x-input label="Designation" name="designation" placeholder="Senior Software Engineer" />
                <x-input label="Monthly Basic Salary (RM)" name="basic_salary" type="number" step="0.01" required placeholder="6500.00" />
                <x-input label="Birth Date" name="birth_date" type="date" required />
                <x-input label="Joined Date" name="joined_date" type="date" required />
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Citizenship</label>
                    <select name="citizenship" class="w-full text-xs rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 p-2.5 text-slate-900 dark:text-white">
                        <option value="malaysian">Malaysian Citizen</option>
                        <option value="permanent_resident">Permanent Resident (PR)</option>
                        <option value="foreign_worker">Foreign Expatriate</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="document.getElementById('register-modal').close()">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save Employee
                </x-button>
            </div>
        </form>
    </x-modal>

</x-layouts.admin>
