<x-layouts.admin title="Leave Applications &amp; Approvals">
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
                            <i class="bx bx-calendar-event"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Leave Applications &amp; Approvals</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            EA 1955 Compliance
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        Process employee absence requests, medical leave validation, multi-tier manager approvals, and automated unpaid leave deduction (ORP) tracking.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap shrink-0">
                    <x-button variant="primary" size="md" icon="bx-plus" onclick="openModal('record-leave-modal')">
                        Record Leave
                    </x-button>
                </div>
            </div>
        </div>

        <!-- Metric KPI Cards via StatCard UI Kit -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Pending Approvals -->
            <x-stat-card 
                title="Pending Approvals"
                value="{{ $totalPending }}"
                change="Awaiting manager review"
                changeType="neutral"
                icon="bx-time-five"
                color="amber"
            />

            <!-- Approved This Month -->
            <x-stat-card 
                title="Approved This Month"
                value="{{ $totalApprovedMonth }}"
                change="Active in {{ date('F') }}"
                changeType="positive"
                icon="bx-calendar-check"
                color="emerald"
            />

            <!-- Active On Leave Today -->
            <x-stat-card 
                title="On Leave Today"
                value="{{ $activeOnLeaveToday }}"
                change="Absent workforce"
                changeType="neutral"
                icon="bx-user-x"
                color="indigo"
            />

            <!-- Unpaid Days (Payroll ORP) -->
            <x-stat-card 
                title="Unpaid Leave Days"
                value="{{ $totalUnpaidDaysMonth }} Days"
                change="Auto deducted on payroll"
                changeType="negative"
                icon="bx-cut"
                color="rose"
            />
        </div>

        <!-- Executive Search & Filter Command Suite for Leave Applications -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-3.5 sm:p-4 bg-slate-50/50 dark:bg-slate-850/40 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="w-6 h-6 rounded-lg bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-xs">
                        <i class="bx bx-slider-alt"></i>
                    </span>
                    <span class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider">Search &amp; Filter Applications</span>
                </div>
                @if(request()->hasAny(['search', 'leave_type_id', 'status', 'department_id']))
                    <a href="{{ route('admin.leaves.index') }}" class="text-[11px] font-semibold text-rose-600 dark:text-rose-400 hover:underline flex items-center gap-1">
                        <i class="bx bx-reset"></i>
                        <span>Clear All Filters</span>
                    </a>
                @endif
            </div>

            <div class="p-3.5 sm:p-4">
                <form method="GET" action="{{ route('admin.leaves.index') }}" class="flex flex-col lg:flex-row items-stretch lg:items-center justify-between gap-3">
                    
                    <!-- Search Input -->
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                            <i class="bx bx-search text-base"></i>
                        </div>
                        <input 
                            type="text" 
                            name="search" 
                            value="{{ request('search') }}" 
                            placeholder="Search by employee name or staff ID (e.g. MY-EMP-001)..." 
                            class="w-full pl-10 pr-10 py-2 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 dark:focus:border-indigo-400 transition"
                        >
                        @if(request('search'))
                            <a href="{{ route('admin.leaves.index', request()->except('search')) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600">
                                <i class="bx bx-x-circle text-base"></i>
                            </a>
                        @endif
                    </div>

                    <!-- Dropdowns & Actions Group -->
                    <div class="flex flex-wrap items-center gap-2">
                        <!-- Leave Category Filter -->
                        <div class="relative">
                            <select 
                                name="leave_type_id" 
                                onchange="this.form.submit()" 
                                class="py-2 pl-3 pr-8 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                            >
                                <option value="">All Leave Categories</option>
                                @foreach($leaveTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('leave_type_id') == $type->id ? 'selected' : '' }}>
                                        {{ $type->name }} ({{ $type->code }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Status Filter -->
                        <div class="relative">
                            <select 
                                name="status" 
                                onchange="this.form.submit()" 
                                class="py-2 pl-3 pr-8 rounded-xl text-xs bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 text-slate-700 dark:text-slate-300 font-semibold focus:outline-none focus:ring-2 focus:ring-indigo-500/20 cursor-pointer"
                            >
                                <option value="">All Statuses</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
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

        <!-- Leave Records Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bx-calendar-check text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Leave Applications &amp; Absences</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-mono">{{ $leaves->total() }} Applications Logged</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600 dark:text-slate-300">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 border-b border-slate-200/80 dark:border-slate-800 text-[10px] font-bold text-slate-500 uppercase tracking-wider">
                        <tr>
                            <th class="py-3.5 px-4">Employee</th>
                            <th class="py-3.5 px-4">Leave Category</th>
                            <th class="py-3.5 px-4">Duration &amp; Dates</th>
                            <th class="py-3.5 px-4">Days</th>
                            <th class="py-3.5 px-4">Reason / Notes</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
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

    </div>

    <!-- RECORD LEAVE MODAL -->
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

</x-layouts.admin>
