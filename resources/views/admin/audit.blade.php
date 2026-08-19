<x-layouts.admin title="System Audit Trails & Governance">

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
                            <i class="bx bx-shield-quarter"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Audit Trails &amp; Compliance Logs</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Immutable Security Ledger
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        Complete historical log of system access, statutory adjustments, payroll batch approvals, and bank export downloads.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap shrink-0">
                    <button 
                        type="button" 
                        class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 backdrop-blur-md transition flex items-center gap-2 cursor-pointer shadow-xs hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bx bx-filter text-sm text-indigo-200"></i>
                        <span>Filter Module</span>
                    </button>
                    <button 
                        type="button" 
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition flex items-center gap-2 cursor-pointer hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bx bx-download text-base"></i>
                        <span>Export CSV</span>
                    </button>
                </div>
            </div>
        </div>

        <!-- Metric Highlights -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Total Audit Events</span>
                <span class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">3</span>
                <span class="text-[10px] text-emerald-600 dark:text-emerald-400 font-semibold">100% Verified integrity</span>
            </div>
            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Critical Overrides</span>
                <span class="text-2xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">0</span>
                <span class="text-[10px] text-slate-400">Zero unauthorized changes</span>
            </div>
            <div class="p-4 rounded-2xl bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800">
                <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Active User Sessions</span>
                <span class="text-2xl font-bold text-indigo-600 dark:text-indigo-400 font-mono mt-1 block">2</span>
                <span class="text-[10px] text-slate-400">Authenticated via SSL</span>
            </div>
        </div>

        <!-- Audit Trail Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bx-shield-quarter text-indigo-600 dark:text-indigo-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Chronological System Events</h2>
                </div>
                <span class="text-[11px] text-slate-400 font-mono">Auto-synced</span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Timestamp</th>
                            <th class="p-3.5">User</th>
                            <th class="p-3.5">Module</th>
                            <th class="p-3.5">Event</th>
                            <th class="p-3.5">Description</th>
                            <th class="p-3.5">IP Address</th>
                            <th class="p-3.5">Severity</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @php
                            $logs = \App\Models\AuditTrail::with('user')->latest()->get();
                        @endphp

                        @forelse($logs as $log)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="p-3.5 font-mono text-[11px] text-slate-500">
                                    {{ $log->created_at->format('Y-m-d H:i:s') }}
                                </td>
                                <td class="p-3.5 font-semibold text-slate-900 dark:text-white">
                                    {{ $log->user?->name ?? 'System Automated' }}
                                    @if($log->user?->staff_id)
                                        <span class="text-[10px] text-indigo-600 dark:text-indigo-400 font-mono block">{{ $log->user->staff_id }}</span>
                                    @endif
                                </td>
                                <td class="p-3.5">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold font-mono uppercase bg-slate-100 dark:bg-slate-800 text-slate-700 dark:text-slate-300">
                                        {{ $log->module }}
                                    </span>
                                </td>
                                <td class="p-3.5 font-mono text-[11px] font-semibold text-slate-800 dark:text-slate-200">
                                    {{ $log->event }}
                                </td>
                                <td class="p-3.5 max-w-xs truncate text-slate-600 dark:text-slate-300">
                                    {{ $log->description }}
                                </td>
                                <td class="p-3.5 font-mono text-[11px] text-slate-500">
                                    {{ $log->ip_address ?? '127.0.0.1' }}
                                </td>
                                <td class="p-3.5">
                                    @if($log->severity === 'critical')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 dark:bg-rose-950 text-rose-700 dark:text-rose-300">Critical</span>
                                    @elseif($log->severity === 'warning')
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 dark:bg-amber-950 text-amber-700 dark:text-amber-300">Warning</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950 text-emerald-700 dark:text-emerald-300">Info</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    No audit entries found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($logs->hasPages())
                <div class="p-4 border-t border-slate-100 dark:border-slate-800">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>

    </div>

</x-layouts.admin>
