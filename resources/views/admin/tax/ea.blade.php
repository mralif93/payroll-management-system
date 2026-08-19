<x-layouts.admin title="Year-End Tax Form EA (C.P.8A) Compiler">

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
                            <i class="bx bx-file"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Year-End Form EA Compiler</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Borang EA (C.P.8A)
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        Annual statement of remuneration from employment under Section 83(1A) of the Malaysian Income Tax Act 1967.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap shrink-0">
                    <!-- Tax Year Selector Switcher -->
                    <div class="flex items-center bg-white/10 backdrop-blur-md p-1 rounded-xl border border-white/10 text-xs font-bold">
                        <a href="{{ route('admin.tax-ea.index', ['tax_year' => '2024']) }}" class="px-3 py-1.5 rounded-lg transition {{ ($taxYear ?? date('Y')) == '2024' ? 'bg-indigo-600 text-white shadow-xs' : 'text-indigo-200 hover:text-white' }}">
                            2024
                        </a>
                        <a href="{{ route('admin.tax-ea.index', ['tax_year' => '2025']) }}" class="px-3 py-1.5 rounded-lg transition {{ ($taxYear ?? date('Y')) == '2025' ? 'bg-indigo-600 text-white shadow-xs' : 'text-indigo-200 hover:text-white' }}">
                            2025
                        </a>
                        <a href="{{ route('admin.tax-ea.index', ['tax_year' => '2026']) }}" class="px-3 py-1.5 rounded-lg transition {{ ($taxYear ?? date('Y')) == '2026' ? 'bg-indigo-600 text-white shadow-xs' : 'text-indigo-200 hover:text-white' }}">
                            2026 (YTD)
                        </a>
                    </div>

                    <form method="POST" action="{{ route('admin.tax-ea.compile') }}">
                        @csrf
                        <input type="hidden" name="tax_year" value="{{ $taxYear ?? date('Y') }}">
                        <button 
                            type="submit" 
                            class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition flex items-center gap-2 cursor-pointer hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <i class="bx bx-refresh text-base"></i>
                            <span>Compile Annual EA ({{ $taxYear ?? date('Y') }})</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Metric Highlights via UI Kit -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <x-stat-card 
                title="Total Compiled Forms"
                value="{{ $eaRecords->total() ?? 0 }}"
                change="Tax Assessment Year {{ $taxYear ?? date('Y') }}"
                changeType="neutral"
                icon="bx-file"
                color="rose"
            />
            <x-stat-card 
                title="Accumulated PCB (MTD)"
                value="RM {{ number_format($totalAccumulatedPcb ?? 0, 2) }}"
                change="Direct LHDN Form EA Sec D1"
                changeType="positive"
                icon="bx-receipt"
                color="indigo"
            />
            <x-stat-card 
                title="Total KWSP Employee"
                value="RM {{ number_format($totalKwspEe ?? 0, 2) }}"
                change="Form EA Section E1 (Max RM4,000)"
                changeType="positive"
                icon="bx-shield-quarter"
                color="emerald"
            />
        </div>
 
        <!-- Modern Search Command Bar for Form EA Records -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-3 sm:p-4">
            <form method="GET" action="{{ route('admin.tax-ea.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-3">
                <input type="hidden" name="tax_year" value="{{ $taxYear ?? date('Y') }}">
                
                <!-- Main Search Input -->
                <div class="relative flex-1">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 dark:text-slate-500">
                        <i class="bx bx-search text-lg"></i>
                    </div>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search compiled EA forms by employee name, staff ID (e.g. MY-EMP-001), or serial no..." 
                        class="w-full pl-10 pr-10 py-2.5 rounded-xl text-xs bg-slate-50/80 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700/80 text-slate-900 dark:text-white placeholder-slate-400 dark:placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-600 dark:focus:border-indigo-400 transition-all font-sans"
                    >
                    @if(request('search'))
                        <a href="{{ route('admin.tax-ea.index', ['tax_year' => $taxYear ?? date('Y')]) }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">
                            <i class="bx bx-x-circle text-base"></i>
                        </a>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition flex items-center gap-1.5 shadow-xs cursor-pointer">
                        <i class="bx bx-filter-alt text-sm"></i>
                        <span>Search</span>
                    </button>

                    @if(request('search'))
                        <a href="{{ route('admin.tax-ea.index', ['tax_year' => $taxYear ?? date('Y')]) }}" class="px-3 py-2.5 rounded-xl bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 text-slate-600 dark:text-slate-300 text-xs font-semibold transition flex items-center gap-1">
                            <i class="bx bx-reset text-sm"></i>
                            <span>Reset</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Compiled Form EA Records Table -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-4 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="bx bxs-file-pdf text-rose-600 dark:text-rose-400 text-lg"></i>
                    <h2 class="text-sm font-bold text-slate-900 dark:text-white">Annual Employee Remuneration Records</h2>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                    <thead class="bg-slate-50 dark:bg-slate-800/60 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider text-[10px]">
                        <tr>
                            <th class="p-3.5">Serial No</th>
                            <th class="p-3.5">Employee Name</th>
                            <th class="p-3.5">Gross Wages (Sec B1)</th>
                            <th class="p-3.5">PCB MTD (Sec D1)</th>
                            <th class="p-3.5">KWSP EE (Sec E1)</th>
                            <th class="p-3.5">PERKESO EE (Sec E2)</th>
                            <th class="p-3.5 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300 font-sans">
                        @forelse($eaRecords as $record)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-800/40 transition">
                                <td class="p-3.5 font-mono font-bold text-indigo-600 dark:text-indigo-400">
                                    {{ $record->serial_no }}
                                </td>
                                <td class="p-3.5 font-semibold text-slate-900 dark:text-white">
                                    {{ $record->employee?->full_name }}
                                </td>
                                <td class="p-3.5 font-mono font-bold">
                                    RM {{ number_format($record->gross_salary_wages, 2) }}
                                </td>
                                <td class="p-3.5 font-mono text-rose-600 dark:text-rose-400 font-bold">
                                    RM {{ number_format($record->total_pcb_mtd, 2) }}
                                </td>
                                <td class="p-3.5 font-mono">
                                    RM {{ number_format($record->total_epf_employee, 2) }}
                                </td>
                                <td class="p-3.5 font-mono">
                                    RM {{ number_format($record->total_socso_employee, 2) }}
                                </td>
                                <td class="p-3.5 text-right">
                                    <a 
                                        href="{{ route('admin.tax-ea.print', $record) }}" 
                                        target="_blank"
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-rose-50 dark:bg-rose-950/50 text-rose-600 dark:text-rose-400 hover:bg-rose-600 hover:text-white dark:hover:bg-rose-600 dark:hover:text-white transition text-xs font-bold shadow-xs cursor-pointer"
                                        title="Print Official Borang EA Statement (PDF)"
                                    >
                                        <i class="bx bx-printer text-base"></i>
                                        <span>Print EA (PDF)</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="p-8 text-center text-slate-400">
                                    No compiled Form EA records found for Tax Year {{ $taxYear ?? date('Y') }}. Click "Compile Annual EA" above.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

</x-layouts.admin>
