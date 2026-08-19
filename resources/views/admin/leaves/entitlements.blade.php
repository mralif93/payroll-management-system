<x-layouts.admin title="Employee Leave Entitlements &amp; Quotas">
    <div class="space-y-8">

        <!-- Executive Page Hero Banner & Action Suite -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900 via-slate-900 to-indigo-950 text-white p-6 sm:p-7 shadow-lg shadow-indigo-950/20 border border-indigo-800/40">
            <!-- Background Decorative Glow -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-20 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <div class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 font-bold text-base shadow-xs">
                            <i class="bx bx-pie-chart-alt-2"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Leave Entitlements &amp; Quotas</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            EA 1955 Statutory Rules
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        Manage annual statutory quotas (AL, MC, Hospitalization) per Malaysian Employment Act Section 60E/60F, carry-forwards, and leave adjustments.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap shrink-0">
                    <!-- Assessment Year Switcher -->
                    <div class="flex items-center bg-white/10 backdrop-blur-md p-1 rounded-xl border border-white/10 text-xs font-bold">
                        <a href="{{ route('admin.leave-entitlements.index', ['year' => '2024']) }}" class="px-3 py-1.5 rounded-lg transition {{ $currentYear == 2024 ? 'bg-indigo-600 text-white shadow-xs' : 'text-indigo-200 hover:text-white' }}">
                            2024
                        </a>
                        <a href="{{ route('admin.leave-entitlements.index', ['year' => '2025']) }}" class="px-3 py-1.5 rounded-lg transition {{ $currentYear == 2025 ? 'bg-indigo-600 text-white shadow-xs' : 'text-indigo-200 hover:text-white' }}">
                            2025
                        </a>
                        <a href="{{ route('admin.leave-entitlements.index', ['year' => '2026']) }}" class="px-3 py-1.5 rounded-lg transition {{ $currentYear == 2026 ? 'bg-indigo-600 text-white shadow-xs' : 'text-indigo-200 hover:text-white' }}">
                            2026 (Current)
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Metric Highlights via UI Kit -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <x-stat-card 
                title="Total Entitled Days"
                value="{{ number_format($totalEntitledDays, 0) }} Days"
                change="{{ $totalStaff }} Staff Registered"
                changeType="neutral"
                icon="bx-calendar"
                color="indigo"
            />
            <x-stat-card 
                title="Days Taken (YTD)"
                value="{{ number_format($totalTakenDays, 0) }} Days"
                change="Approved leaves recorded"
                changeType="positive"
                icon="bx-calendar-check"
                color="purple"
            />
            <x-stat-card 
                title="Available Balance Pool"
                value="{{ number_format($totalRemainingDays, 0) }} Days"
                change="Remaining leave pool"
                changeType="positive"
                icon="bx-check-double"
                color="emerald"
            />
            <x-stat-card 
                title="Statutory Categories"
                value="{{ $leaveTypes->count() }} Categories"
                change="AL, MC, Hosp, Mat, Pat"
                changeType="neutral"
                icon="bx-shield-quarter"
                color="rose"
            />
        </div>

        <!-- Executive Search & Filter Command Suite for Entitlements -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-3.5 sm:p-4 bg-slate-50/50 dark:bg-slate-850/40 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                        <i class="bx bx-slider-alt"></i>
                    </span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Search &amp; Filter Staff Quotas (Year {{ $currentYear }})</span>
                </div>
                @if(request()->hasAny(['search', 'department_id']))
                    <a href="{{ route('admin.leave-entitlements.index', ['year' => $currentYear]) }}" class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 hover:underline flex items-center gap-1">
                        <i class="bx bx-reset"></i>
                        <span>Clear All Filters</span>
                    </a>
                @endif
            </div>

            <div class="p-3.5 sm:p-4">
                <form method="GET" action="{{ route('admin.leave-entitlements.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                    <input type="hidden" name="year" value="{{ $currentYear }}">

                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="bx bx-search text-base"></i>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search by staff name or ID (e.g. MY-EMP-001)..." 
                            class="w-full pl-10 pr-10 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 dark:focus:border-indigo-400 transition"
                        >
                        @if(request('search'))
                            <a href="{{ route('admin.leave-entitlements.index', ['year' => $currentYear]) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <i class="bx bx-x-circle text-base"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Dropdowns & Actions Group -->
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Department Filter -->
                        <div class="relative">
                            <select 
                                name="department_id" 
                                onchange="this.form.submit()" 
                                class="py-2 pl-3 pr-8 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                            >
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                                        {{ $dept->name }}
                                    </option>
                                @endforeach
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

        <!-- Staff Leave Balances & Entitlements Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl overflow-hidden shadow-xs">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bx-pie-chart-alt text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Annual Statutory Leave Balances Roster</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-mono">{{ $employeeBalances->total() }} Employees Listed</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300 min-w-[880px]">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4 whitespace-nowrap">Employee</th>
                            <th class="py-3.5 px-4 whitespace-nowrap">Type &amp; Department</th>
                            @foreach($leaveTypes as $lt)
                                <th class="py-3.5 px-3 text-center whitespace-nowrap">
                                    <span class="block">{{ $lt->name }}</span>
                                    <span class="text-[9px] text-slate-400 font-mono">Entitled &bull; Taken &bull; Rem</span>
                                </th>
                            @endforeach
                            <th class="py-3.5 px-4 text-right whitespace-nowrap">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                        @forelse($employeeBalances as $emp)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4 whitespace-nowrap">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                            {{ strtoupper(substr($emp->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 dark:text-white block">{{ $emp->full_name }}</span>
                                            <span class="text-[10px] text-slate-400 font-mono">{{ $emp->employee_no }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ ucfirst($emp->employment_type ?? 'permanent') }}
                                    </span>
                                    <span class="block text-[10px] text-slate-400 mt-0.5">{{ $emp->department?->name ?? 'General' }}</span>
                                </td>

                                @foreach($leaveTypes as $lt)
                                    @php
                                        $bal = $emp->leaveBalances->where('leave_type_id', $lt->id)->first();
                                        $entitled = $bal ? (float)$bal->total_entitled : (float)$lt->default_days_per_year;
                                        $taken = $bal ? (float)$bal->taken_days : 0;
                                        $rem = $bal ? (float)$bal->remaining_days : $entitled;
                                    @endphp
                                    <td class="py-3.5 px-3 text-center">
                                        <div class="inline-flex items-center gap-1 font-mono text-[11px] bg-slate-50 dark:bg-slate-800/60 px-2 py-1 rounded-lg border border-slate-100 dark:border-slate-700/50">
                                            <span class="text-slate-500 font-bold" title="Annual Entitlement">{{ number_format($entitled, 0) }}</span>
                                            <span class="text-slate-300">/</span>
                                            <span class="text-rose-500 font-bold" title="Days Taken">{{ number_format($taken, 0) }}</span>
                                            <span class="text-slate-300">/</span>
                                            <span class="text-emerald-600 dark:text-emerald-400 font-extrabold" title="Remaining Days">{{ number_format($rem, 0) }}d</span>
                                        </div>
                                    </td>
                                @endforeach

                                <td class="py-3.5 px-4 text-right">
                                    <x-action-button variant="purple" icon="bx-slider-alt" title="Adjust Quotas" onclick="openAdjustEmployeeBalancesModal({{ json_encode($emp) }})">
                                        Adjust
                                    </x-action-button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-12 text-center text-slate-400">
                                    No active employees found for balance display.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($employeeBalances->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $employeeBalances->links() }}
                </div>
            @endif
        </div>

    </div>

    <!-- ADJUST INDIVIDUAL EMPLOYEE LEAVE BALANCES MODAL -->
    <x-modal id="adjust-employee-balance-modal" title="Adjust Employee Annual Leave Entitlements" subtitle="Customize annual leave quota and recorded days taken for the employee" icon="bx-slider" size="lg">
        <div class="space-y-4 text-left">
            <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700 flex items-center justify-between">
                <div>
                    <span class="text-sm font-bold text-slate-900 dark:text-white block" id="adjust-emp-name">Employee Name</span>
                    <span class="text-xs text-slate-400 font-mono" id="adjust-emp-no">EMP-00101 &bull; Permanent</span>
                </div>
                <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                    Year {{ $currentYear }}
                </span>
            </div>

            <div id="adjust-balances-container" class="space-y-3">
                <!-- Dynamically injected via JavaScript -->
            </div>

            <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="md" type="button" onclick="closeModal('adjust-employee-balance-modal')">
                    Close
                </x-button>
            </div>
        </div>
    </x-modal>

    <x-slot name="scripts">
        <script>
            const allLeaveTypes = @json($leaveTypes);
            const updateBalanceBaseUrl = "{{ url('admin/leaves/balances') }}";

            function openAdjustEmployeeBalancesModal(emp) {
                document.getElementById('adjust-emp-name').textContent = emp.full_name;
                document.getElementById('adjust-emp-no').textContent = emp.employee_no + ' • ' + (emp.department?.name || 'General');

                const container = document.getElementById('adjust-balances-container');
                container.innerHTML = '';

                allLeaveTypes.forEach(lt => {
                    const bal = emp.leave_balances ? emp.leave_balances.find(b => b.leave_type_id === lt.id) : null;
                    const entitled = bal ? bal.total_entitled : lt.default_days_per_year;
                    const taken = bal ? bal.taken_days : 0;
                    const balanceId = bal ? bal.id : 0;

                    const row = document.createElement('form');
                    row.method = 'POST';
                    row.action = `${updateBalanceBaseUrl}/${balanceId}`;
                    row.className = 'p-3.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3';

                    row.innerHTML = `
                        @csrf
                        @method('PUT')
                        <div class="min-w-[140px]">
                            <span class="font-bold text-xs text-slate-900 dark:text-white block">${lt.name}</span>
                            <span class="text-[10px] text-slate-400 font-mono">Code: ${lt.code}</span>
                        </div>
                        <div class="flex items-center gap-3 flex-1">
                            <div class="flex-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Entitled Days</label>
                                <input type="number" step="0.5" name="total_entitled" value="${entitled}" class="w-full text-xs font-mono font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-2 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                            <div class="flex-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-1">Days Taken</label>
                                <input type="number" step="0.5" name="taken_days" value="${taken}" class="w-full text-xs font-mono font-bold rounded-lg border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 p-2 text-slate-900 dark:text-white focus:outline-none focus:ring-1 focus:ring-indigo-500">
                            </div>
                        </div>
                        <div class="shrink-0 flex items-end">
                            <button type="submit" ${balanceId === 0 ? 'disabled' : ''} class="w-full sm:w-auto px-3.5 py-2 text-xs font-bold rounded-lg bg-indigo-600 text-white hover:bg-indigo-500 transition shadow-xs flex items-center justify-center gap-1 cursor-pointer disabled:opacity-50">
                                <i class="bx bx-save"></i> Save
                            </button>
                        </div>
                    `;
                    container.appendChild(row);
                });

                openModal('adjust-employee-balance-modal');
            }
        </script>
    </x-slot>
</x-layouts.admin>
