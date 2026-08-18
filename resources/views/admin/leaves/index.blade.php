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

        <!-- Filter & Search Toolbar -->
        <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200/80 dark:border-slate-800 shadow-sm">
            <form method="GET" action="{{ route('admin.leaves.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
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

    </div>

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

</x-layouts.admin>
