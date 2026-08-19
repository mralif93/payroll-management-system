<x-layouts.admin title="System Parameters & Governance" :hideHeader="true">

    <div class="space-y-6 sm:space-y-8">

        <!-- Flash Messages -->
        @if(session('success'))
            <x-alert type="success" dismissible="true">
                {{ session('success') }}
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
                            <i class="bx bx-slider-alt"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">System Parameters &amp; Governance</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Statutory Active 2026
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        Centralized statutory schedules, Malaysian government contribution brackets (KWSP, SOCSO, EIS, PCB), company profile, and departmental hierarchies.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap shrink-0">
                    <button 
                        type="button" 
                        onclick="openModal('edit-company-modal')"
                        class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 backdrop-blur-md transition flex items-center gap-2 cursor-pointer shadow-xs hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bx bx-edit text-sm text-indigo-200"></i>
                        <span>Company Profile</span>
                    </button>
                    <button 
                        type="button" 
                        onclick="openModal('add-department-modal')"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition flex items-center gap-2 cursor-pointer hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bx bx-plus-circle text-base"></i>
                        <span>Add Department</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Metric Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Active Departments"
                value="{{ $departments->count() }}"
                change="Organizational Structure"
                changeType="positive"
                icon="bx-buildings"
                color="indigo"
            />
            <x-stat-card 
                title="EPF Statutory Rule"
                value="Act 1991 (11%/13%)"
                change="Third Schedule"
                changeType="positive"
                icon="bx-shield-quarter"
                color="emerald"
            />
            <x-stat-card 
                title="PERKESO Scheme"
                value="SKBBK 2026"
                change="Lindung 24 Jam Active"
                changeType="positive"
                icon="bx-check-shield"
                color="purple"
            />
            <x-stat-card 
                title="EIS Ceiling"
                value="RM 6,000.00"
                change="Act 800 (0.2% + 0.2%)"
                changeType="neutral"
                icon="bx-briefcase"
                color="blue"
            />
        </div>

        <!-- EXCLUSIVE ACCORDION CONTAINER (Only 1 item open at a time; Item 1 open by default) -->
        <div class="space-y-4" id="parameters-accordion">

            <!-- ACCORDION ITEM 1: Corporate Departments Roster (Open by default) -->
            <div class="accordion-item bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-all duration-300">
                <div class="accordion-header p-4.5 flex items-center justify-between cursor-pointer select-none hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition" onclick="toggleAccordion('dept-accordion')">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                            <i class="bx bx-sitemap"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Corporate Departments Roster</h2>
                            <span class="text-[11px] text-slate-400">Manage company organizational units, codes, and employee department allocations</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800 font-mono">
                            {{ $departments->count() }} Units
                        </span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-lg transition-transform duration-300 accordion-icon" id="dept-accordion-icon">
                            <i class="bx bx-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="accordion-content border-t border-slate-100 dark:border-slate-800" id="dept-accordion-content">
                    <div class="p-4 bg-slate-50/40 dark:bg-slate-900/40 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Assigned employee roster per departmental division</span>
                        <x-button variant="secondary" size="xs" icon="bx-plus" onclick="openModal('add-department-modal')">
                            New Department
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs min-w-[700px]">
                            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="p-3.5 whitespace-nowrap">Department Name</th>
                                    <th class="p-3.5 whitespace-nowrap">Code / Acronym</th>
                                    <th class="p-3.5 whitespace-nowrap">Assigned Staff</th>
                                    <th class="p-3.5 whitespace-nowrap">Created Date</th>
                                    <th class="p-3.5 text-right whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                                @forelse($departments as $dept)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                        <td class="p-3.5 font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                            {{ $dept->name }}
                                        </td>
                                        <td class="p-3.5 font-mono text-indigo-600 dark:text-indigo-400 font-bold whitespace-nowrap">
                                            {{ $dept->code ?? '—' }}
                                        </td>
                                        <td class="p-3.5 font-mono whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                {{ $dept->employees_count }} staff
                                            </span>
                                        </td>
                                        <td class="p-3.5 text-slate-400 whitespace-nowrap">
                                            {{ $dept->created_at ? $dept->created_at->format('d M Y') : '—' }}
                                        </td>
                                        <td class="p-3.5 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center justify-end gap-1.5">
                                                <x-action-button variant="purple" icon="bx-pencil" title="Edit Department" onclick="openEditDepartmentModal({{ json_encode($dept) }})">
                                                    Edit
                                                </x-action-button>

                                                @if($dept->employees_count === 0)
                                                    <x-action-button variant="rose" icon="bx-trash" title="Delete Department" onclick="confirmDeleteDepartment({{ $dept->id }}, '{{ addslashes($dept->name) }}')">
                                                        Delete
                                                    </x-action-button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-slate-400">
                                            No organizational departments found. Click "Add Department" to create units.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ACCORDION ITEM 2: Allowance & Benefit Components Registry (Collapsed by default) -->
            <div class="accordion-item bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-all duration-300">
                <div class="accordion-header p-4.5 flex items-center justify-between cursor-pointer select-none hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition" onclick="toggleAccordion('allowance-accordion')">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-950/80 text-teal-600 dark:text-teal-400 flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                            <i class="bx bx-gift"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Allowance &amp; Benefit Components Registry</h2>
                            <span class="text-[11px] text-slate-400">Recurring allowance types, deductions, and Malaysian statutory taxability flags</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-teal-50 dark:bg-teal-950 text-teal-700 dark:text-teal-300 border border-teal-200 dark:border-teal-800 font-mono">
                            {{ $salaryComponents->count() }} Components
                        </span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-lg transition-transform duration-300 accordion-icon rotate-[-90deg]" id="allowance-accordion-icon">
                            <i class="bx bx-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="accordion-content border-t border-slate-100 dark:border-slate-800 hidden" id="allowance-accordion-content">
                    <div class="p-4 bg-slate-50/40 dark:bg-slate-900/40 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Statutory taxability matrix per compensation component</span>
                        <x-button variant="secondary" size="xs" icon="bx-plus" onclick="openModal('add-allowance-modal')">
                            New Allowance Type
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs min-w-[760px]">
                            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="p-3.5 whitespace-nowrap">Allowance Name</th>
                                    <th class="p-3.5 whitespace-nowrap">Component Code</th>
                                    <th class="p-3.5 whitespace-nowrap">Statutory Rules (EPF / SOCSO / EIS / PCB)</th>
                                    <th class="p-3.5 whitespace-nowrap">Assigned Staff</th>
                                    <th class="p-3.5 text-right whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                                @forelse($salaryComponents as $comp)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                        <td class="p-3.5 font-bold text-slate-900 dark:text-white whitespace-nowrap">
                                            {{ $comp->name }}
                                        </td>
                                        <td class="p-3.5 font-mono text-teal-600 dark:text-teal-400 font-bold whitespace-nowrap">
                                            {{ $comp->code }}
                                        </td>
                                        <td class="p-3.5 whitespace-nowrap">
                                            <div class="flex items-center gap-1.5 flex-wrap text-[10px]">
                                                @if($comp->is_epf_subject)
                                                    <span class="px-1.5 py-0.5 rounded font-bold bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">EPF</span>
                                                @endif
                                                @if($comp->is_socso_subject)
                                                    <span class="px-1.5 py-0.5 rounded font-bold bg-purple-50 dark:bg-purple-950 text-purple-700 dark:text-purple-300 border border-purple-200 dark:border-purple-800">SOCSO</span>
                                                @endif
                                                @if($comp->is_eis_subject)
                                                    <span class="px-1.5 py-0.5 rounded font-bold bg-blue-50 dark:bg-blue-950 text-blue-700 dark:text-blue-300 border border-blue-200 dark:border-blue-800">EIS</span>
                                                @endif
                                                @if($comp->is_pcb_subject)
                                                    <span class="px-1.5 py-0.5 rounded font-bold bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">PCB</span>
                                                @endif
                                                @if(!$comp->is_epf_subject && !$comp->is_socso_subject && !$comp->is_eis_subject && !$comp->is_pcb_subject)
                                                    <span class="px-1.5 py-0.5 rounded font-medium bg-slate-100 dark:bg-slate-800 text-slate-500">Exempt (Tax Free)</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="p-3.5 font-mono whitespace-nowrap">
                                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                                {{ $comp->employee_salary_components_count }} staff
                                            </span>
                                        </td>
                                        <td class="p-3.5 text-right whitespace-nowrap">
                                            <div class="inline-flex items-center justify-end gap-1.5">
                                                <x-action-button variant="purple" icon="bx-pencil" title="Edit Allowance Rules" onclick="openEditAllowanceModal({{ json_encode($comp) }})">
                                                    Edit
                                                </x-action-button>

                                                @if($comp->employee_salary_components_count === 0)
                                                    <x-action-button variant="rose" icon="bx-trash" title="Delete Allowance Component" onclick="confirmDeleteAllowance({{ $comp->id }}, '{{ addslashes($comp->name) }}')">
                                                        Delete
                                                    </x-action-button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-8 text-center text-slate-400">
                                            No allowance components configured. Click "New Allowance Type" to define benefits.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- ACCORDION ITEM 3: KWSP / EPF Statutory Parameters (Collapsed by default) -->
            <div class="accordion-item bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-all duration-300">
                <div class="accordion-header p-4.5 flex items-center justify-between cursor-pointer select-none hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition" onclick="toggleAccordion('epf-accordion')">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                            <i class="bx bxs-bank"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">KWSP / EPF Statutory Parameters (Act 1991)</h2>
                            <span class="text-[11px] text-slate-400 font-mono">{{ $parameters['epf']->first()?->reference_gazette ?? 'P.U. (A) EPF Act 1991 Third Schedule' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge variant="indigo" dot="true">Active Standard (11% / 13%)</x-badge>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-lg transition-transform duration-300 accordion-icon rotate-[-90deg]" id="epf-accordion-icon">
                            <i class="bx bx-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="accordion-content border-t border-slate-100 dark:border-slate-800 hidden" id="epf-accordion-content">
                    <div class="p-4 bg-slate-50/40 dark:bg-slate-900/40 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Statutory employee and employer rate configuration</span>
                        <x-action-button variant="purple" icon="bx-pencil" title="Edit EPF Rates" onclick="openModal('edit-epf-modal')">
                            Edit EPF Rates
                        </x-action-button>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Standard Employee</span>
                            <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ ($epfParams['standard_employee_rate'] ?? 0.11) * 100 }}%</span>
                            <span class="text-[10px] text-slate-400">Voluntary option {{ ($epfParams['voluntary_reduced_employee_rate'] ?? 0.09) * 100 }}%</span>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Employer (Wage &le; RM{{ number_format($epfParams['salary_threshold'] ?? 5000, 0) }})</span>
                            <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400 font-mono mt-1 block">{{ ($epfParams['employer_rate_low_wage'] ?? 0.13) * 100 }}%</span>
                            <span class="text-[10px] text-slate-400">Mandatory statutory low wage</span>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Employer (Wage &gt; RM{{ number_format($epfParams['salary_threshold'] ?? 5000, 0) }})</span>
                            <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ ($epfParams['employer_rate_high_wage'] ?? 0.12) * 100 }}%</span>
                            <span class="text-[10px] text-slate-400">Standard statutory bracket</span>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Senior Citizen (Age 60+)</span>
                            <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400 font-mono mt-1 block">{{ ($epfParams['senior_citizen_employer_rate'] ?? 0.04) * 100 }}% (ER) / {{ ($epfParams['senior_citizen_employee_rate'] ?? 0) * 100 }}% (EE)</span>
                            <span class="text-[10px] text-slate-400">Malaysian Citizens</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACCORDION ITEM 4: PERKESO SOCSO & SKBBK Schedule (Collapsed by default) -->
            <div class="accordion-item bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-all duration-300">
                <div class="accordion-header p-4.5 flex items-center justify-between cursor-pointer select-none hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition" onclick="toggleAccordion('socso-accordion')">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                            <i class="bx bxs-shield"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">PERKESO SOCSO &amp; SKBBK (Lindung 24 Jam) Schedule</h2>
                            <span class="text-[11px] text-slate-400 font-mono">Effective 1 June 2026 • Wage Ceiling RM{{ number_format($socsoParams['wage_ceiling'] ?? 6000, 2) }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <x-badge variant="purple" dot="true">Includes SKBBK 2026</x-badge>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-lg transition-transform duration-300 accordion-icon rotate-[-90deg]" id="socso-accordion-icon">
                            <i class="bx bx-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="accordion-content border-t border-slate-100 dark:border-slate-800 hidden" id="socso-accordion-content">
                    <div class="p-4 bg-slate-50/40 dark:bg-slate-900/40 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Act 4 base contribution brackets & 24-Hour Non-Employment Injury Scheme</span>
                        <x-action-button variant="purple" icon="bx-pencil" title="Edit SOCSO Rates" onclick="openModal('edit-socso-modal')">
                            Edit SOCSO Schedule
                        </x-action-button>
                    </div>

                    <div class="p-6 space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Act 4 SOCSO Category 1 (Base)</span>
                                <span class="text-lg font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ ($socsoParams['category_1']['employer_rate_percentage'] ?? 0.0175) * 100 }}% ER / {{ ($socsoParams['category_1']['employee_base_percentage'] ?? 0.005) * 100 }}% EE</span>
                                <span class="text-[10px] text-slate-400">Employment Injury &amp; Invalidity</span>
                            </div>
                            <div class="p-4 rounded-xl bg-purple-50/60 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800">
                                <span class="text-xs text-purple-700 dark:text-purple-300 font-bold block">SKBBK (Lindung 24 Jam)</span>
                                <span class="text-lg font-bold text-purple-950 dark:text-purple-100 font-mono mt-1 block">Tiered (e.g. RM14.50 @ RM2k)</span>
                                <span class="text-[10px] text-purple-600 dark:text-purple-400 font-medium">100% Employee Borne</span>
                            </div>
                            <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">SOCSO Category 2 (Age 60+)</span>
                                <span class="text-lg font-bold text-slate-900 dark:text-white font-mono mt-1 block">{{ ($socsoParams['category_2']['employer_rate_percentage'] ?? 0.0125) * 100 }}% ER / RM7.00 SKBBK</span>
                                <span class="text-[10px] text-slate-400">Employment Injury Only</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACCORDION ITEM 5: SIP / EIS (Act 800) (Collapsed by default) -->
            <div class="accordion-item bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-all duration-300">
                <div class="accordion-header p-4.5 flex items-center justify-between cursor-pointer select-none hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition" onclick="toggleAccordion('eis-accordion')">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-950/80 text-teal-600 dark:text-teal-400 flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                            <i class="bx bx-briefcase"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">SIP / EIS (Act 800)</h2>
                            <span class="text-[11px] text-slate-400">Employment Insurance System (0.2% Employee + 0.2% Employer)</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-teal-600 dark:text-teal-400">{{ ($eisParams['employee_rate'] ?? 0.002) * 100 }}% + {{ ($eisParams['employer_rate'] ?? 0.002) * 100 }}%</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-lg transition-transform duration-300 accordion-icon rotate-[-90deg]" id="eis-accordion-icon">
                            <i class="bx bx-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="accordion-content border-t border-slate-100 dark:border-slate-800 hidden" id="eis-accordion-content">
                    <div class="p-4 bg-slate-50/40 dark:bg-slate-900/40 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">EIS statutory percentage rates & wage ceiling limit</span>
                        <x-action-button variant="purple" icon="bx-pencil" title="Edit EIS Rules" onclick="openModal('edit-eis-modal')">
                            Edit EIS Rules
                        </x-action-button>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Wage Ceiling Limit</span>
                            <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">RM {{ number_format($eisParams['wage_ceiling'] ?? 6000, 2) }}</span>
                            <span class="text-[10px] text-slate-400">Max statutory wage threshold</span>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Max Monthly Deduction (Capped)</span>
                            <span class="text-xl font-bold text-teal-600 dark:text-teal-400 font-mono mt-1 block">RM {{ number_format(($eisParams['wage_ceiling'] ?? 6000) * ($eisParams['employee_rate'] ?? 0.002) - 0.10, 2) }} each</span>
                            <span class="text-[10px] text-slate-400">0.2% Employee / 0.2% Employer</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- ACCORDION ITEM 6: LHDN PCB Standard Reliefs (Collapsed by default) -->
            <div class="accordion-item bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-all duration-300">
                <div class="accordion-header p-4.5 flex items-center justify-between cursor-pointer select-none hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition" onclick="toggleAccordion('pcb-accordion')">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                            <i class="bx bxs-file-pdf"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">LHDN PCB Standard Reliefs</h2>
                            <span class="text-[11px] text-slate-400">Income Tax Act 1967 (Computerised MTD calculation parameters)</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="text-xs font-mono font-bold text-rose-600 dark:text-rose-400">Auto MTD Engine</span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-lg transition-transform duration-300 accordion-icon rotate-[-90deg]" id="pcb-accordion-icon">
                            <i class="bx bx-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="accordion-content border-t border-slate-100 dark:border-slate-800 hidden" id="pcb-accordion-content">
                    <div class="p-4 bg-slate-50/40 dark:bg-slate-900/40 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Statutory individual and dependent relief amounts for monthly tax calculation</span>
                        <x-action-button variant="purple" icon="bx-pencil" title="Edit PCB Reliefs" onclick="openModal('edit-pcb-modal')">
                            Edit PCB Reliefs
                        </x-action-button>
                    </div>

                    <div class="p-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Individual (D)</span>
                            <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">RM {{ number_format($pcbParams['individual_relief'] ?? 9000, 0) }}</span>
                            <span class="text-[10px] text-slate-400">Standard Individual Relief</span>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Non-Working Spouse (S)</span>
                            <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">RM {{ number_format($pcbParams['spouse_non_working_relief'] ?? 4000, 0) }}</span>
                            <span class="text-[10px] text-slate-400">Spouse without income</span>
                        </div>
                        <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                            <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Per Child (QC)</span>
                            <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">RM {{ number_format($pcbParams['child_relief_per_child'] ?? 2000, 0) }}</span>
                            <span class="text-[10px] text-slate-400">Under 18 / studying</span>
                        </div>
            <!-- ACCORDION ITEM 7: Statutory Leave Categories & Baseline Entitlements (Collapsed by default) -->
            <div class="accordion-item bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden transition-all duration-300">
                <div class="accordion-header p-4.5 flex items-center justify-between cursor-pointer select-none hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition" onclick="toggleAccordion('leave-accordion')">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-amber-50 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 flex items-center justify-center font-bold text-base shadow-xs shrink-0">
                            <i class="bx bx-calendar-event"></i>
                        </div>
                        <div>
                            <h2 class="text-sm font-bold text-slate-900 dark:text-white">Statutory Leave Entitlements (EA 1955 / 2022)</h2>
                            <span class="text-[11px] text-slate-400">Baseline company leave types, annual quotas, and unpaid salary deduction parameters</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-50 dark:bg-amber-950 text-amber-700 dark:text-amber-300 border border-amber-200 dark:border-amber-800 font-mono">
                            {{ $leaveTypes->count() }} Categories
                        </span>
                        <div class="w-8 h-8 rounded-xl bg-slate-100 dark:bg-slate-800 text-slate-500 flex items-center justify-center text-lg transition-transform duration-300 accordion-icon rotate-[-90deg]" id="leave-accordion-icon">
                            <i class="bx bx-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <div class="accordion-content border-t border-slate-100 dark:border-slate-800 hidden" id="leave-accordion-content">
                    <div class="p-4 bg-slate-50/40 dark:bg-slate-900/40 flex items-center justify-between border-b border-slate-100 dark:border-slate-800">
                        <span class="text-xs font-semibold text-slate-600 dark:text-slate-400">Company-wide baseline annual entitlements</span>
                        <x-button variant="secondary" size="xs" icon="bx-plus" onclick="openModal('add-leave-type-modal')">
                            New Leave Category
                        </x-button>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs">
                            <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                                <tr>
                                    <th class="p-3.5">Leave Name</th>
                                    <th class="p-3.5">Code</th>
                                    <th class="p-3.5">Type &amp; Payroll Rule</th>
                                    <th class="p-3.5">Default Days / Year</th>
                                    <th class="p-3.5">Recorded Leaves</th>
                                    <th class="p-3.5 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                                @forelse($leaveTypes as $lType)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                        <td class="p-3.5 font-bold text-slate-900 dark:text-white">
                                            <div class="flex items-center gap-2">
                                                <span class="w-2 h-2 rounded-full bg-{{ $lType->color }}-500"></span>
                                                <span>{{ $lType->name }}</span>
                                            </div>
                                        </td>
                                        <td class="p-3.5 font-mono text-indigo-600 dark:text-indigo-400 font-bold">
                                            {{ $lType->code }}
                                        </td>
                                        <td class="p-3.5">
                                            @if($lType->is_paid)
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                                                    Paid Leave (100% Wage)
                                                </span>
                                            @else
                                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border border-rose-200 dark:border-rose-800">
                                                    Unpaid (ORP Salary Deducted)
                                                </span>
                                            @endif
                                        </td>
                                        <td class="p-3.5 font-mono font-bold text-slate-900 dark:text-white">
                                            {{ $lType->default_days_per_year }} days
                                        </td>
                                        <td class="p-3.5 font-mono text-slate-500">
                                            {{ $lType->applications_count }} applications
                                        </td>
                                        <td class="p-3.5 text-right">
                                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                                <x-action-button variant="purple" icon="bx-pencil" title="Edit Leave Type" onclick="openEditLeaveTypeModal({{ json_encode($lType) }})">
                                                    Edit
                                                </x-action-button>
                                                @if($lType->applications_count === 0 && !in_array($lType->code, ['AL', 'MC', 'UL']))
                                                    <x-action-button variant="rose" icon="bx-trash" title="Delete Leave Type" onclick="confirmDeleteLeaveType({{ $lType->id }}, '{{ addslashes($lType->name) }}')">
                                                        Delete
                                                    </x-action-button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="p-8 text-center text-slate-400">
                                            No leave categories configured. Click "New Leave Category" to add.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- 0. EDIT COMPANY PROFILE MODAL -->
    <x-modal id="edit-company-modal" title="Edit Company Profile & Statutory IDs" subtitle="Configure employer registration IDs, bank autopay accounts, and contact details" icon="bx-buildings" size="xl">
        <form method="POST" action="{{ route('admin.parameters.company.update') }}" class="space-y-5 text-left">
            @csrf
            @method('PUT')

            <!-- Section 1: Company Identity -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-1.5">
                    <i class="bx bx-buildings text-indigo-600 dark:text-indigo-400 text-sm"></i>
                    <span class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">Company Identity</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <x-input label="Company Legal Name" name="name" value="{{ $company->name ?? '' }}" required placeholder="e.g. PayFlow Technologies Sdn Bhd" />
                    <x-input label="SSM Registration No." name="registration_no" value="{{ $company->registration_no ?? '' }}" required placeholder="e.g. 202601009999" />
                </div>
            </div>

            <!-- Section 2: Statutory Organization Numbers (Clean 2-Column Grid) -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-1.5">
                    <i class="bx bx-shield-quarter text-indigo-600 dark:text-indigo-400 text-sm"></i>
                    <span class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">Statutory Organization Numbers</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <x-input label="KWSP / EPF Employer No." name="epf_no" value="{{ $company->epf_no ?? '' }}" placeholder="e.g. 123456789" />
                    <x-input label="PERKESO / SOCSO Employer No." name="socso_no" value="{{ $company->socso_no ?? '' }}" placeholder="e.g. A123456789" />
                    <x-input label="LHDN Employer Tax No. (E)" name="tax_no" value="{{ $company->tax_no ?? '' }}" placeholder="e.g. E 9876543200" />
                    <x-input label="HRD Corp Registration No." name="hrd_no" value="{{ $company->hrd_no ?? '' }}" placeholder="e.g. HRD-2026-999" />
                </div>
            </div>

            <!-- Section 3: Corporate Banking & AutoPay -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-1.5">
                    <i class="bx bxs-bank text-indigo-600 dark:text-indigo-400 text-sm"></i>
                    <span class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">Corporate Banking &amp; AutoPay</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <x-input label="AutoPay Bank Name" name="bank_name" value="{{ $company->bank_name ?? '' }}" placeholder="e.g. Malayan Banking Berhad (Maybank)" />
                    <x-input label="Corporate Bank Account No." name="bank_account_no" value="{{ $company->bank_account_no ?? '' }}" placeholder="e.g. 514012345678" />
                </div>
            </div>

            <!-- Section 4: Contact & Office Location -->
            <div class="space-y-3">
                <div class="flex items-center gap-2 border-b border-slate-100 dark:border-slate-800 pb-1.5">
                    <i class="bx bx-map-pin text-indigo-600 dark:text-indigo-400 text-sm"></i>
                    <span class="text-xs font-bold text-slate-800 dark:text-white uppercase tracking-wider">Contact &amp; Registered Address</span>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                    <x-input label="Contact Person" name="contact_person" value="{{ $company->contact_person ?? '' }}" placeholder="e.g. Ahmad Tajudin" />
                    <x-input label="Contact Email" name="contact_email" type="email" value="{{ $company->contact_email ?? '' }}" placeholder="e.g. admin@payroll.my" />
                    <x-input label="Contact Phone" name="contact_phone" value="{{ $company->contact_phone ?? '' }}" placeholder="e.g. +603-88889999" />
                </div>
                <x-input label="Corporate Registered Address" name="address" value="{{ $company->address ?? '' }}" placeholder="Registered office address" />
            </div>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-company-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save Company Profile
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 1. ADD DEPARTMENT MODAL -->
    <x-modal id="add-department-modal" title="Add Organizational Department" subtitle="Create a new corporate department for employee assignments" icon="bx-buildings" size="md">
        <form method="POST" action="{{ route('admin.parameters.departments.store') }}" class="space-y-4 text-left">
            @csrf
            <input type="hidden" name="company_id" value="{{ $company->id ?? 1 }}">

            <x-input label="Department Name" name="name" required placeholder="e.g. Quality Assurance & Testing" />
            <x-input label="Department Code / Acronym" name="code" placeholder="e.g. QAT" />

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('add-department-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Create Department
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 2. EDIT DEPARTMENT MODAL -->
    <x-modal id="edit-department-modal" title="Edit Department" subtitle="Update department name or organizational code" icon="bx-pencil" size="md">
        <form id="edit-department-form" method="POST" action="" class="space-y-4 text-left">
            @csrf
            @method('PUT')

            <x-input label="Department Name" name="name" id="edit-dept-name" required />
            <x-input label="Department Code / Acronym" name="code" id="edit-dept-code" />

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-department-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save Changes
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 3. ADD ALLOWANCE COMPONENT MODAL -->
    <x-modal id="add-allowance-modal" title="Create Custom Allowance Type" subtitle="Define a custom employee allowance and set Malaysian statutory taxability rules" icon="bx-gift" size="lg">
        <form method="POST" action="{{ route('admin.parameters.allowances.store') }}" class="space-y-4 text-left">
            @csrf
            <input type="hidden" name="type" value="allowance">

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-input label="Allowance Name" name="name" required placeholder="e.g. Technical Certification Allowance" />
                <x-input label="Component Code" name="code" required placeholder="e.g. TECH_CERT_ALLOW" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Malaysian Statutory Deductibility &amp; Taxability Rules</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="is_epf_subject" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span>Subject to KWSP / EPF (11% EE / 12-13% ER)</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="is_socso_subject" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span>Subject to PERKESO SOCSO &amp; SKBBK</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="is_eis_subject" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span>Subject to SIP / EIS (Act 800)</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="is_pcb_subject" value="1" checked class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span>Subject to LHDN Monthly Tax (PCB)</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('add-allowance-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Create Allowance Type
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 4. EDIT ALLOWANCE COMPONENT MODAL -->
    <x-modal id="edit-allowance-modal" title="Edit Allowance Component" subtitle="Modify component title and statutory contribution rules" icon="bx-pencil" size="lg">
        <form id="edit-allowance-form" method="POST" action="" class="space-y-4 text-left">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <x-input label="Allowance Name" name="name" id="edit-allowance-name" required />
                <x-input label="Component Code" name="code" id="edit-allowance-code" disabled helper="Unique identifier cannot be modified" />
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2">Malaysian Statutory Deductibility &amp; Taxability Rules</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 p-3 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-800/40">
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="is_epf_subject" id="edit-allowance-epf" value="1" class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span>Subject to KWSP / EPF (11% EE / 12-13% ER)</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="is_socso_subject" id="edit-allowance-socso" value="1" class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span>Subject to PERKESO SOCSO &amp; SKBBK</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="is_eis_subject" id="edit-allowance-eis" value="1" class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span>Subject to SIP / EIS (Act 800)</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs text-slate-700 dark:text-slate-300 cursor-pointer">
                        <input type="checkbox" name="is_pcb_subject" id="edit-allowance-pcb" value="1" class="rounded text-indigo-600 focus:ring-indigo-500">
                        <span>Subject to LHDN Monthly Tax (PCB)</span>
                    </label>
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-allowance-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save Changes
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 5. EDIT EPF STATUTORY PARAMETERS MODAL -->
    <x-modal id="edit-epf-modal" title="Edit KWSP / EPF Policy Rates" subtitle="Update Malaysian EPF contribution percentages & wage threshold rules" icon="bx-bank" size="md">
        <form method="POST" action="{{ route('admin.parameters.statutory.update', 'epf') }}" class="space-y-4 text-left">
            @csrf
            @method('PUT')

            <x-input label="Statutory Gazette Reference" name="reference_gazette" value="{{ $parameters['epf']->first()?->reference_gazette ?? 'P.U. (A) EPF Act 1991 Third Schedule' }}" />
            <x-input label="Standard Employee Rate (%)" name="value_payload[standard_employee_rate]" type="number" step="0.5" value="{{ ($epfParams['standard_employee_rate'] ?? 0.11) * 100 }}" required />
            <x-input label="Voluntary Reduced Rate (%)" name="value_payload[voluntary_reduced_employee_rate]" type="number" step="0.5" value="{{ ($epfParams['voluntary_reduced_employee_rate'] ?? 0.09) * 100 }}" required />
            <x-input label="Low Wage Threshold (RM)" name="value_payload[salary_threshold]" type="number" step="100" value="{{ $epfParams['salary_threshold'] ?? 5000 }}" required />
            <x-input label="Employer Low Wage Rate (%) (<= RM5,000)" name="value_payload[employer_rate_low_wage]" type="number" step="0.5" value="{{ ($epfParams['employer_rate_low_wage'] ?? 0.13) * 100 }}" required />
            <x-input label="Employer Standard Rate (%) (> RM5,000)" name="value_payload[employer_rate_high_wage]" type="number" step="0.5" value="{{ ($epfParams['employer_rate_high_wage'] ?? 0.12) * 100 }}" required />
            <x-input label="Senior Citizen ER Rate (%) (Age 60+)" name="value_payload[senior_citizen_employer_rate]" type="number" step="0.5" value="{{ ($epfParams['senior_citizen_employer_rate'] ?? 0.04) * 100 }}" required />
            <x-input label="Senior Citizen EE Rate (%) (Age 60+)" name="value_payload[senior_citizen_employee_rate]" type="number" step="0.5" value="{{ ($epfParams['senior_citizen_employee_rate'] ?? 0.00) * 100 }}" required />

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-epf-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save EPF Rates
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 6. EDIT SOCSO & SKBBK STATUTORY PARAMETERS MODAL -->
    <x-modal id="edit-socso-modal" title="Edit PERKESO SOCSO & SKBBK Schedule" subtitle="Configure Act 4 base percentages and June 2026 Lindung 24 Jam rules" icon="bx-shield" size="md">
        <form method="POST" action="{{ route('admin.parameters.statutory.update', 'socso') }}" class="space-y-4 text-left">
            @csrf
            @method('PUT')

            <x-input label="Statutory Gazette Reference" name="reference_gazette" value="{{ $parameters['socso']->first()?->reference_gazette ?? 'Warta Kerajaan PERKESO SKBBK 2026' }}" />
            <x-input label="Monthly Wage Ceiling (RM)" name="value_payload[wage_ceiling]" type="number" step="100" value="{{ $socsoParams['wage_ceiling'] ?? 6000 }}" required />
            <x-input label="Category 1 Employer Rate (%)" name="value_payload[category_1][employer_rate_percentage]" type="number" step="0.05" value="{{ ($socsoParams['category_1']['employer_rate_percentage'] ?? 0.0175) * 100 }}" required />
            <x-input label="Category 1 Employee Rate (%)" name="value_payload[category_1][employee_base_percentage]" type="number" step="0.05" value="{{ ($socsoParams['category_1']['employee_base_percentage'] ?? 0.005) * 100 }}" required />
            <x-input label="Category 2 (Senior) ER Rate (%)" name="value_payload[category_2][employer_rate_percentage]" type="number" step="0.05" value="{{ ($socsoParams['category_2']['employer_rate_percentage'] ?? 0.0125) * 100 }}" required />
            <x-input label="Category 2 (Senior) EE Rate (%)" name="value_payload[category_2][employee_base_percentage]" type="number" step="0.05" value="{{ ($socsoParams['category_2']['employee_base_percentage'] ?? 0.00) * 100 }}" required />

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-socso-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save SOCSO Schedule
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 7. EDIT EIS STATUTORY PARAMETERS MODAL -->
    <x-modal id="edit-eis-modal" title="Edit SIP / EIS (Act 800) Rates" subtitle="Update Employment Insurance System percentage rates & wage ceiling" icon="bx-briefcase" size="md">
        <form method="POST" action="{{ route('admin.parameters.statutory.update', 'eis') }}" class="space-y-4 text-left">
            @csrf
            @method('PUT')

            <x-input label="Employee EIS Rate (%)" name="value_payload[employee_rate]" type="number" step="0.05" value="{{ ($eisParams['employee_rate'] ?? 0.002) * 100 }}" required />
            <x-input label="Employer EIS Rate (%)" name="value_payload[employer_rate]" type="number" step="0.05" value="{{ ($eisParams['employer_rate'] ?? 0.002) * 100 }}" required />
            <x-input label="Wage Ceiling Limit (RM)" name="value_payload[wage_ceiling]" type="number" step="100" value="{{ $eisParams['wage_ceiling'] ?? 6000 }}" required />

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-eis-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save EIS Parameters
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 8. EDIT PCB TAX RELIEFS MODAL -->
    <x-modal id="edit-pcb-modal" title="Edit LHDN PCB Standard Reliefs" subtitle="Update statutory individual and dependent relief amounts for monthly tax calculation" icon="bxs-file-pdf" size="md">
        <form method="POST" action="{{ route('admin.parameters.statutory.update', 'pcb') }}" class="space-y-4 text-left">
            @csrf
            @method('PUT')

            <x-input label="Individual Tax Relief (D) (RM)" name="value_payload[individual_relief]" type="number" step="500" value="{{ $pcbParams['individual_relief'] ?? 9000 }}" required />
            <x-input label="Non-Working Spouse Relief (S) (RM)" name="value_payload[spouse_non_working_relief]" type="number" step="500" value="{{ $pcbParams['spouse_non_working_relief'] ?? 4000 }}" required />
            <x-input label="Child Relief Per Dependent (QC) (RM)" name="value_payload[child_relief_per_child]" type="number" step="500" value="{{ $pcbParams['child_relief_per_child'] ?? 2000 }}" required />

            <div class="flex justify-end gap-2 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-pcb-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit">
                    Save PCB Reliefs
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 7. ADD LEAVE TYPE MODAL -->
    <x-modal id="add-leave-type-modal" title="Add Statutory Leave Category" subtitle="Define a company leave entitlement rule and annual default quota" icon="bx-calendar-plus" size="lg">
        <form method="POST" action="{{ route('admin.parameters.leave-types.store') }}" class="space-y-4 text-left">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <x-input label="Leave Category Name" name="name" required placeholder="e.g. Marriage Leave, Study Leave" />
                <x-input label="Leave Code / Acronym" name="code" required placeholder="e.g. ML, SL" />
                <x-input label="Annual Default Days" name="default_days_per_year" type="number" min="0" max="365" required placeholder="e.g. 14" />
                
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Payroll Compensation Rule</label>
                    <div class="relative">
                        <select name="is_paid" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                            <option value="1">Paid Leave (100% Full Wages)</option>
                            <option value="0">Unpaid Leave (ORP Basic / 26 days Deduction)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                            <i class="bx bx-chevron-down text-base"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Description & Guidelines</label>
                    <textarea name="description" rows="2" placeholder="Policy terms, eligibility criteria, documentation needed..." class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('add-leave-type-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit" icon="bx-check">
                    Create Leave Category
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 8. EDIT LEAVE TYPE MODAL -->
    <x-modal id="edit-leave-type-modal" title="Edit Leave Entitlement Rule" subtitle="Update leave code, annual quota, and statutory payment rules" icon="bx-pencil" size="lg">
        <form id="edit-leave-type-form" method="POST" action="" class="space-y-4 text-left">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <x-input label="Leave Category Name" name="name" id="edit-leave-name" required />
                <x-input label="Leave Code / Acronym" name="code" id="edit-leave-code" required />
                <x-input label="Annual Default Days" name="default_days_per_year" id="edit-leave-days" type="number" min="0" max="365" required />
                
                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Payroll Compensation Rule</label>
                    <div class="relative">
                        <select name="is_paid" id="edit-leave-paid" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                            <option value="1">Paid Leave (100% Full Wages)</option>
                            <option value="0">Unpaid Leave (ORP Basic / 26 days Deduction)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                            <i class="bx bx-chevron-down text-base"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Description & Guidelines</label>
                    <textarea name="description" id="edit-leave-desc" rows="2" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"></textarea>
                </div>
            </div>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="sm" type="button" onclick="closeModal('edit-leave-type-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="sm" type="submit" icon="bx-check">
                    Save Changes
                </x-button>
            </div>
        </form>
    </x-modal>

    <!-- 9. CONFIRM DELETE MODALS -->
    <x-confirm-modal 
        id="delete-department-confirm-modal"
        title="Delete Department"
        message="Are you sure you want to delete this organizational department? This action cannot be undone."
        confirmText="Yes, Delete Department"
        confirmVariant="danger"
    />

    <x-confirm-modal 
        id="delete-allowance-confirm-modal"
        title="Delete Allowance Component"
        message="Are you sure you want to delete this custom allowance type? This action cannot be undone."
        confirmText="Yes, Delete Component"
        confirmVariant="danger"
    />

    <x-confirm-modal 
        id="delete-leave-type-confirm-modal"
        title="Delete Leave Category"
        message="Are you sure you want to delete this leave category? This action cannot be undone."
        confirmText="Yes, Delete Leave Type"
        confirmVariant="danger"
    />

    <x-slot name="scripts">
        <script>
            function toggleAccordion(targetId) {
                const accordionKeys = [
                    'dept-accordion',
                    'allowance-accordion',
                    'epf-accordion',
                    'socso-accordion',
                    'eis-accordion',
                    'pcb-accordion',
                    'leave-accordion'
                ];

                accordionKeys.forEach(key => {
                    const content = document.getElementById(`${key}-content`);
                    const icon = document.getElementById(`${key}-icon`);

                    if (key === targetId) {
                        const isCurrentlyHidden = content.classList.contains('hidden');
                        if (isCurrentlyHidden) {
                            content.classList.remove('hidden');
                            if (icon) icon.classList.remove('rotate-[-90deg]');
                        }
                    } else {
                        if (content) content.classList.add('hidden');
                        if (icon) icon.classList.add('rotate-[-90deg]');
                    }
                });
            }

            function openEditLeaveTypeModal(lType) {
                const form = document.getElementById('edit-leave-type-form');
                form.action = `/admin/parameters/leave-types/${lType.id}`;

                document.getElementById('edit-leave-name').value = lType.name || '';
                document.getElementById('edit-leave-code').value = lType.code || '';
                document.getElementById('edit-leave-days').value = lType.default_days_per_year || 0;
                document.getElementById('edit-leave-paid').value = lType.is_paid ? '1' : '0';
                document.getElementById('edit-leave-desc').value = lType.description || '';

                openModal('edit-leave-type-modal');
            }

            function confirmDeleteLeaveType(id, name) {
                const form = document.getElementById('delete-leave-type-confirm-modal-form');
                form.action = `/admin/parameters/leave-types/${id}`;
                document.getElementById('delete-leave-type-confirm-modal-method').value = 'DELETE';
                document.getElementById('delete-leave-type-confirm-modal-message').textContent = `Are you sure you want to delete leave category "${name}"?`;
                openModal('delete-leave-type-confirm-modal');
            }

            function openEditDepartmentModal(dept) {
                const form = document.getElementById('edit-department-form');
                form.action = `/admin/parameters/departments/${dept.id}`;

                document.getElementById('edit-dept-name').value = dept.name || '';
                document.getElementById('edit-dept-code').value = dept.code || '';

                openModal('edit-department-modal');
            }

            function confirmDeleteDepartment(deptId, deptName) {
                const form = document.getElementById('delete-department-confirm-modal-form');
                form.action = `/admin/parameters/departments/${deptId}`;
                document.getElementById('delete-department-confirm-modal-method').value = 'DELETE';
                document.getElementById('delete-department-confirm-modal-message').textContent = `Are you sure you want to delete department "${deptName}"?`;
                openModal('delete-department-confirm-modal');
            }

            function openEditAllowanceModal(comp) {
                const form = document.getElementById('edit-allowance-form');
                form.action = `/admin/parameters/allowances/${comp.id}`;

                document.getElementById('edit-allowance-name').value = comp.name || '';
                document.getElementById('edit-allowance-code').value = comp.code || '';
                document.getElementById('edit-allowance-epf').checked = comp.is_epf_subject == 1;
                document.getElementById('edit-allowance-socso').checked = comp.is_socso_subject == 1;
                document.getElementById('edit-allowance-eis').checked = comp.is_eis_subject == 1;
                document.getElementById('edit-allowance-pcb').checked = comp.is_pcb_subject == 1;

                openModal('edit-allowance-modal');
            }

            function confirmDeleteAllowance(compId, compName) {
                const form = document.getElementById('delete-allowance-confirm-modal-form');
                form.action = `/admin/parameters/allowances/${compId}`;
                document.getElementById('delete-allowance-confirm-modal-method').value = 'DELETE';
                document.getElementById('delete-allowance-confirm-modal-message').textContent = `Are you sure you want to delete allowance component "${compName}"?`;
                openModal('delete-allowance-confirm-modal');
            }
        </script>
    </x-slot>

</x-layouts.admin>

