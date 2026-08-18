<x-layouts.admin title="Leave & Attendance Management">
    <div class="space-y-6">

        <!-- Executive Hero Banner with Integrated Quick Actions -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 border border-slate-800/80 p-6 md:p-8 shadow-xl shadow-indigo-950/20 text-white">
            <div class="absolute -right-12 -bottom-12 w-64 h-64 rounded-full bg-indigo-500/10 blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/4 top-0 w-48 h-48 rounded-full bg-blue-500/10 blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/10 border border-indigo-500/30 text-indigo-300">
                        <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                        EA 1955 Statutory Leave Compliance
                    </div>
                    <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white">
                        Leave &amp; Attendance Management
                    </h1>
                    <p class="text-xs md:text-sm text-slate-300 max-w-2xl leading-relaxed">
                        Track annual leave quotas, statutory sick leave, and calculate automated unpaid leave deductions (ORP) across permanent, contract, intern, and part-time staff.
                    </p>
                </div>

                <div class="flex items-center gap-3 flex-wrap">
                    <x-button variant="primary" size="md" icon="bx-plus" onclick="openModal('record-leave-modal')">
                        Record Leave
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Metric KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Pending Approvals -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Pending Approval</span>
                    <span class="text-2xl font-black text-slate-900 dark:text-white mt-1 block">{{ $totalPending }}</span>
                    <span class="text-[11px] text-amber-500 font-medium flex items-center gap-1 mt-0.5">
                        <i class="bx bx-time-five"></i> Awaiting manager review
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 flex items-center justify-center text-2xl">
                    <i class="bx bx-time"></i>
                </div>
            </div>

            <!-- Approved This Month -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Approved This Month</span>
                    <span class="text-2xl font-black text-slate-900 dark:text-white mt-1 block">{{ $totalApprovedMonth }}</span>
                    <span class="text-[11px] text-emerald-500 font-medium flex items-center gap-1 mt-0.5">
                        <i class="bx bx-check-circle"></i> Active in {{ date('F') }}
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center text-2xl">
                    <i class="bx bx-calendar-check"></i>
                </div>
            </div>

            <!-- On Leave Today -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">On Leave Today</span>
                    <span class="text-2xl font-black text-slate-900 dark:text-white mt-1 block">{{ $activeOnLeaveToday }}</span>
                    <span class="text-[11px] text-indigo-500 font-medium flex items-center gap-1 mt-0.5">
                        <i class="bx bx-user-voice"></i> Out of office
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 flex items-center justify-center text-2xl">
                    <i class="bx bx-user-x"></i>
                </div>
            </div>

            <!-- Unpaid Leave Days (Salary Deducted) -->
            <div class="p-5 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-bold text-slate-400 uppercase tracking-wider block">Unpaid Days (ORP)</span>
                    <span class="text-2xl font-black text-slate-900 dark:text-white mt-1 block">{{ number_format($totalUnpaidDaysMonth, 1) }} d</span>
                    <span class="text-[11px] text-rose-500 font-medium flex items-center gap-1 mt-0.5">
                        <i class="bx bx-cut"></i> Auto deducted on payroll
                    </span>
                </div>
                <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-950/60 text-rose-600 flex items-center justify-center text-2xl">
                    <i class="bx bx-cut"></i>
                </div>
            </div>
        </div>

        <!-- Navigation Tabs: Leave Applications vs Employee Quotas & Balances -->
        @php
            $activeTab = request('tab', 'applications');
        @endphp
        <div class="flex items-center gap-2 border-b border-slate-200 dark:border-slate-800 pb-2">
            <a href="{{ route('admin.leaves.index', ['tab' => 'applications']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab === 'applications' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="bx bx-list-check text-base"></i>
                <span>Leave Applications</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $activeTab === 'applications' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }} font-mono">
                    {{ $leaves->total() }}
                </span>
            </a>
            <a href="{{ route('admin.leaves.index', ['tab' => 'balances']) }}" class="px-4 py-2 rounded-xl text-xs font-bold transition flex items-center gap-2 {{ $activeTab === 'balances' ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800' }}">
                <i class="bx bx-pie-chart-alt-2 text-base"></i>
                <span>Employee Leave Entitlements &amp; Quotas</span>
                <span class="px-1.5 py-0.2 rounded-full text-[10px] {{ $activeTab === 'balances' ? 'bg-white/20 text-white' : 'bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300' }} font-mono">
                    {{ $employeeBalances->total() }} Staff
                </span>
            </a>
        </div>

        @if($activeTab === 'applications')
        <!-- Filter & Search Toolbar -->
        <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <form method="GET" action="{{ route('admin.leaves.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <input type="hidden" name="tab" value="applications">
                <div>
                    <div class="relative">
                        <i class="bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base"></i>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search employee name / staff ID..." class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 pl-10 pr-3.5 py-2.5 text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                </div>

                <div>
                    <div class="relative">
                        <select name="leave_type_id" onchange="this.form.submit()" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                            <option value="">All Leave Categories</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                    {{ $type->name }} ({{ $type->code }})
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                            <i class="bx bx-chevron-down text-base"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="relative">
                        <select name="status" onchange="this.form.submit()" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                            <option value="">All Application Statuses</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                            <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                            <i class="bx bx-chevron-down text-base"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-button variant="secondary" size="md" type="submit" class="w-full">
                        Filter
                    </x-button>
                    @if(request()->hasAny(['search', 'leave_type_id', 'status', 'department_id']))
                        <a href="{{ route('admin.leaves.index') }}" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white text-xs font-semibold flex items-center justify-center">
                            <i class="bx bx-reset text-base"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Leave Records Table -->
        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50/75 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Employee</th>
                            <th class="py-3.5 px-4">Leave Type</th>
                            <th class="py-3.5 px-4">Duration &amp; Dates</th>
                            <th class="py-3.5 px-4">Days</th>
                            <th class="py-3.5 px-4">Reason / Notes</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse($leaves as $leave)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-blue-600 flex items-center justify-center text-white text-xs font-bold shadow-sm">
                                            {{ strtoupper(substr($leave->employee?->full_name ?? 'EM', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="font-bold text-slate-900 dark:text-white block">{{ $leave->employee?->full_name }}</span>
                                            <span class="text-[10px] text-slate-400 block font-mono">{{ $leave->employee?->employee_no }} &bull; {{ ucfirst($leave->employee?->employment_type ?? 'permanent') }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <div class="flex items-center gap-1.5">
                                        @if($leave->leaveType?->is_paid)
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-800">
                                                {{ $leave->leaveType?->name }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400 border border-rose-200 dark:border-rose-800">
                                                {{ $leave->leaveType?->name }} (Unpaid)
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-3.5 px-4 font-mono text-[11px]">
                                    {{ $leave->start_date ? date('d M Y', strtotime($leave->start_date)) : '—' }} 
                                    @if($leave->start_date != $leave->end_date)
                                        &rarr; {{ $leave->end_date ? date('d M Y', strtotime($leave->end_date)) : '' }}
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="font-bold text-slate-900 dark:text-white font-mono">{{ number_format($leave->total_days, 1) }}</span>
                                    <span class="text-[10px] text-slate-400">day(s)</span>
                                </td>
                                <td class="py-3.5 px-4 max-w-xs truncate" title="{{ $leave->reason }}">
                                    {{ $leave->reason ?? '—' }}
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($leave->status === 'approved')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-600 border border-emerald-200 dark:border-emerald-800">
                                            <span class="w-1 h-1 rounded-full bg-emerald-500"></span> Approved
                                        </span>
                                    @elseif($leave->status === 'pending')
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 dark:bg-amber-950 text-amber-600 border border-amber-200 dark:border-amber-800">
                                            <span class="w-1 h-1 rounded-full bg-amber-500"></span> Pending
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 dark:bg-rose-950 text-rose-600 border border-rose-200 dark:border-rose-800">
                                            <span class="w-1 h-1 rounded-full bg-rose-500"></span> {{ ucfirst($leave->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4 text-right">
                                    <div class="flex items-center justify-end gap-1.5">
                                        @if($leave->status === 'pending')
                                            <form method="POST" action="{{ route('admin.leaves.update-status', $leave) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" title="Approve Leave" class="p-1.5 rounded-lg bg-emerald-50 dark:bg-emerald-950 text-emerald-600 hover:bg-emerald-100 transition cursor-pointer">
                                                    <i class="bx bx-check text-base"></i>
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('admin.leaves.update-status', $leave) }}" class="inline">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="rejected">
                                                <button type="submit" title="Reject Leave" class="p-1.5 rounded-lg bg-rose-50 dark:bg-rose-950 text-rose-600 hover:bg-rose-100 transition cursor-pointer">
                                                    <i class="bx bx-x text-base"></i>
                                                </button>
                                            </form>
                                        @endif
                                        <form method="POST" action="{{ route('admin.leaves.destroy', $leave) }}" onsubmit="return confirm('Remove this leave record?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Delete Record" class="p-1.5 rounded-lg text-slate-400 hover:text-rose-600 transition cursor-pointer">
                                                <i class="bx bx-trash text-base"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-12 text-center text-slate-400">
                                    <i class="bx bx-calendar-x text-4xl mb-2 block"></i>
                                    <span class="text-sm font-semibold block">No leave records found</span>
                                    <span class="text-xs">Click "Record Leave" above to log staff leave or absence.</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($leaves->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $leaves->links() }}
                </div>
            @endif
        </div>
        @else
        <!-- TAB 2: Employee Leave Balances & Entitlement Quotas -->
        <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <form method="GET" action="{{ route('admin.leaves.index') }}" class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <input type="hidden" name="tab" value="balances">
                <div>
                    <div class="relative">
                        <i class="bx bx-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base"></i>
                        <input type="text" name="balance_search" value="{{ request('balance_search') }}" placeholder="Search employee name or staff ID..." class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 pl-10 pr-3.5 py-2.5 text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition">
                    </div>
                </div>

                <div>
                    <div class="relative">
                        <select name="balance_dept" onchange="this.form.submit()" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                            <option value="">All Departments</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ request('balance_dept') == $dept->id ? 'selected' : '' }}>
                                    {{ $dept->name }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                            <i class="bx bx-chevron-down text-base"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <x-button variant="secondary" size="md" type="submit" class="w-full">
                        Filter Staff
                    </x-button>
                    @if(request()->hasAny(['balance_search', 'balance_dept']))
                        <a href="{{ route('admin.leaves.index', ['tab' => 'balances']) }}" class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-500 hover:text-slate-900 dark:hover:text-white text-xs font-semibold flex items-center justify-center">
                            <i class="bx bx-reset text-base"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <div class="bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 rounded-2xl overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50/75 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Employee</th>
                            <th class="py-3.5 px-4">Type &amp; Department</th>
                            @foreach($leaveTypes->where('is_paid', true) as $lt)
                                <th class="py-3.5 px-3 text-center">
                                    <span class="block">{{ $lt->name }}</span>
                                    <span class="text-[9px] text-slate-400 font-mono">Quota &bull; Taken &bull; Rem</span>
                                </th>
                            @endforeach
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800/80">
                        @forelse($employeeBalances as $emp)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="py-3.5 px-4">
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

                                @foreach($leaveTypes->where('is_paid', true) as $lt)
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
        @endif

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
                <!-- Dynamically populated per leave type -->
            </div>

            <div class="flex justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="md" type="button" onclick="closeModal('adjust-employee-balance-modal')">
                    Close
                </x-button>
            </div>
        </div>
    </x-modal>

    <!-- RECORD LEAVE MODAL (Clean 2-Column Standard Inputs without bulky cards) -->
    <x-modal id="record-leave-modal" title="Record Employee Leave Application" subtitle="Log annual leave, medical MC, or unpaid absence for payroll deduction" icon="bx-calendar-plus" size="xl">
        <form method="POST" action="{{ route('admin.leaves.store') }}" class="space-y-5 text-left">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Select Employee</label>
                    <div class="relative">
                        <select name="employee_id" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                            <option value="">Select Employee...</option>
                            @foreach($employees as $emp)
                                <option value="{{ $emp->id }}">
                                    {{ $emp->full_name }} ({{ $emp->employee_no }}) &bull; {{ ucfirst($emp->employment_type ?? 'permanent') }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                            <i class="bx bx-chevron-down text-base"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Leave Category</label>
                    <div class="relative">
                        <select name="leave_type_id" required class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                            <option value="">Select Leave Type...</option>
                            @foreach($leaveTypes as $type)
                                <option value="{{ $type->id }}">
                                    {{ $type->name }} ({{ $type->code }}) — {{ $type->is_paid ? 'Paid' : 'Unpaid (Salary Deducted)' }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                            <i class="bx bx-chevron-down text-base"></i>
                        </div>
                    </div>
                </div>

                <x-input label="Start Date" name="start_date" type="date" required icon="bx-calendar" />
                <x-input label="End Date" name="end_date" type="date" required icon="bx-calendar" />

                <x-input label="Total Days Taken" name="total_days" type="number" step="0.5" min="0.5" max="90" required placeholder="e.g. 1.0 or 0.5" icon="bx-time" />

                <div>
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Approval Status</label>
                    <div class="relative">
                        <select name="status" class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white appearance-none pr-8">
                            <option value="approved">Approved (Immediately deducts quota/salary)</option>
                            <option value="pending">Pending Approval</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                            <i class="bx bx-chevron-down text-base"></i>
                        </div>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Reason / Justification</label>
                    <textarea name="reason" rows="2" placeholder="e.g. Family emergency, Clinic Medical Certificate, Personal matters..." class="w-full text-xs rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2.5 text-slate-900 dark:text-white placeholder:text-slate-400 focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition"></textarea>
                </div>
            </div>

            <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-100 dark:border-slate-800">
                <x-button variant="secondary" size="md" type="button" onclick="closeModal('record-leave-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary" size="md" type="submit" icon="bx-check">
                    Save Leave Application
                </x-button>
            </div>
        </form>
    </x-modal>

    <x-slot name="scripts">
        <script>
            const allPaidLeaveTypes = @json($leaveTypes->where('is_paid', true)->values());

            function openAdjustEmployeeBalancesModal(emp) {
                document.getElementById('adjust-emp-name').textContent = emp.full_name || 'Employee';
                document.getElementById('adjust-emp-no').textContent = `${emp.employee_no || 'EMP'} • ${emp.employment_type ? (emp.employment_type.charAt(0).toUpperCase() + emp.employment_type.slice(1)) : 'Permanent'}`;

                const container = document.getElementById('adjust-balances-container');
                container.innerHTML = '';

                allPaidLeaveTypes.forEach(lt => {
                    const bal = (emp.leave_balances || []).find(b => b.leave_type_id === lt.id);
                    const balId = bal ? bal.id : null;
                    const totalEntitled = bal ? parseFloat(bal.total_entitled || 0) : parseFloat(lt.default_days_per_year || 0);
                    const takenDays = bal ? parseFloat(bal.taken_days || 0) : 0;
                    const remainingDays = Math.max(0, totalEntitled - takenDays);

                    const formHtml = `
                        <form method="POST" action="/admin/leaves/balances/${balId || ''}" class="p-3.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 shadow-xs space-y-2.5">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="_method" value="PUT">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <span class="w-2.5 h-2.5 rounded-full bg-${lt.color || 'indigo'}-500"></span>
                                    <span class="text-xs font-bold text-slate-900 dark:text-white">${lt.name} (${lt.code})</span>
                                </div>
                                <span class="text-[11px] font-mono font-bold text-emerald-600 dark:text-emerald-400">
                                    Remaining: ${remainingDays} days
                                </span>
                            </div>

                            <div class="grid grid-cols-2 gap-3 items-end">
                                <div>
                                    <label class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Annual Entitlement (Days)</label>
                                    <input type="number" step="0.5" min="0" max="365" name="total_entitled" value="${totalEntitled}" required class="w-full text-xs font-mono rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900">
                                </div>
                                <div>
                                    <label class="block text-[10px] uppercase tracking-wider text-slate-400 font-bold mb-1">Days Taken (Days)</label>
                                    <input type="number" step="0.5" min="0" max="365" name="taken_days" value="${takenDays}" required class="w-full text-xs font-mono rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/60 p-2 text-slate-900 dark:text-white focus:bg-white dark:focus:bg-slate-900">
                                </div>
                            </div>

                            <div class="flex justify-end pt-1">
                                <button type="submit" class="px-3 py-1.5 rounded-lg text-xs font-bold bg-indigo-600 hover:bg-indigo-700 text-white transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                                    <i class="bx bx-check"></i>
                                    <span>Save ${lt.code} Quota</span>
                                </button>
                            </div>
                        </form>
                    `;
                    container.insertAdjacentHTML('beforeend', formHtml);
                });

                openModal('adjust-employee-balance-modal');
            }
        </script>
    </x-slot>

</x-layouts.admin>
