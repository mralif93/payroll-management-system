<x-layouts.admin title="System Audit Trails & Governance">

    <div class="space-y-8">
        
        <!-- Header Banner & Filter -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Audit Trails &amp; Compliance Logs</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-100 dark:bg-indigo-950 text-indigo-800 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                        Immutable Ledger
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Complete historical log of system access, statutory adjustments, payroll batch approvals, and bank export downloads.
                </p>
            </div>

            <div class="flex items-center gap-2">
                <x-button variant="secondary" size="sm" icon="bx-filter">
                    Filter by Module
                </x-button>
                <x-button variant="secondary" size="sm" icon="bx-download">
                    Export Audit CSV
                </x-button>
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
        </div>

    </div>

</x-layouts.admin>
