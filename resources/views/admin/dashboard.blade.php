<x-layouts.admin title="Payroll Dashboard - PayFlow MY" :hideHeader="true">

    <div class="space-y-6">

        <!-- Executive Page Hero Banner & Action Suite -->
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-indigo-900 via-slate-900 to-indigo-950 text-white p-6 sm:p-7 shadow-lg shadow-indigo-950/20 border border-indigo-800/40">
            <!-- Background Decorative Glow -->
            <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-1/3 -bottom-20 w-48 h-48 bg-purple-500/15 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-5">
                <div class="space-y-2 max-w-2xl">
                    <div class="flex items-center gap-2.5 flex-wrap">
                        <div class="w-8 h-8 rounded-xl bg-white/10 backdrop-blur-md flex items-center justify-center text-indigo-300 font-bold text-base shadow-xs">
                            <i class="bx bxs-dashboard"></i>
                        </div>
                        <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">Monthly Payroll Operations</h1>
                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 inline-flex items-center gap-1.5 backdrop-blur-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                            Live Payroll Cycle
                        </span>
                    </div>
                    <p class="text-xs sm:text-sm text-indigo-100/80 leading-relaxed">
                        Real-time Malaysian payroll processing, statutory deductions summary (KWSP, SOCSO, EIS, PCB), and batch disbursement.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap shrink-0">
                    <button 
                        type="button" 
                        class="px-3.5 py-2 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold border border-white/20 backdrop-blur-md transition flex items-center gap-2 cursor-pointer shadow-xs hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bx bx-filter text-sm text-indigo-200"></i>
                        <span>Filter Period</span>
                    </button>
                    <a 
                        href="{{ route('admin.payroll.index') }}"
                        class="px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md shadow-indigo-600/30 transition flex items-center gap-2 cursor-pointer hover:scale-[1.02] active:scale-[0.98]"
                    >
                        <i class="bx bx-plus text-base"></i>
                        <span>New Payroll Run</span>
                    </a>
                </div>
            </div>
        </div>

    <!-- Top KPI Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <x-stat-card 
            title="Total Monthly Net Pay" 
            value="RM 148,250.00" 
            icon="bx-wallet" 
            iconColor="emerald" 
            trend="4.2% vs last month"
            trendUp="true"
        />
        <x-stat-card 
            title="KWSP / EPF Pool" 
            value="RM 38,420.00" 
            icon="bx-shield-quarter" 
            iconColor="indigo" 
            subtext="Employee + Employer"
        />
        <x-stat-card 
            title="PERKESO (SOCSO/EIS)" 
            value="RM 4,115.60" 
            icon="bx-check-shield" 
            iconColor="sky" 
            subtext="Capped at RM6k limit"
        />
        <x-stat-card 
            title="LHDN PCB / MTD" 
            value="RM 16,890.45" 
            icon="bx-calculator" 
            iconColor="rose" 
            subtext="CP39 Ready to export"
        />
    </div>

    <!-- Active Payroll Batch Run Card -->
    <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
        
        <!-- Batch Header -->
        <div class="p-6 border-b border-slate-100 dark:border-slate-800 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg">
                    <i class="bx bx-calendar"></i>
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white">Batch Run: August 2026</h3>
                        <x-badge variant="warning" dot="true">Draft Review</x-badge>
                    </div>
                    <p class="text-xs text-slate-400 dark:text-slate-500 mt-0.5">Cut-off Date: 25 Aug 2026 • Disbursement Date: 28 Aug 2026</p>
                </div>
            </div>

            <!-- Export Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <x-button variant="secondary" size="sm" icon="bx-download">
                    Download CP39
                </x-button>
                <x-button variant="secondary" size="sm" icon="bx-file">
                    Maybank2e File
                </x-button>
                <x-button variant="success" size="sm" icon="bx-check-double">
                    Approve & Lock Batch
                </x-button>
            </div>
        </div>

        <!-- Employee Table -->
        <div class="overflow-x-auto">
            <table class="w-full min-w-[780px] divide-y divide-slate-200 dark:divide-slate-800 text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3.5 px-6 whitespace-nowrap">Employee</th>
                        <th class="py-3.5 px-6 whitespace-nowrap">Basic Pay</th>
                        <th class="py-3.5 px-6 whitespace-nowrap">KWSP EPF (EE / ER)</th>
                        <th class="py-3.5 px-6 whitespace-nowrap">SOCSO &amp; EIS</th>
                        <th class="py-3.5 px-6 whitespace-nowrap">PCB (MTD)</th>
                        <th class="py-3.5 px-6 whitespace-nowrap">Net Take-Home</th>
                        <th class="py-3.5 px-6 text-right whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-800 text-slate-700 dark:text-slate-300">
                    
                    <!-- Row 1 -->
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition">
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-xs">
                                    AM
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white">Ahmad bin Mustaffa</div>
                                    <div class="text-[11px] text-slate-400 font-mono">EMP-00104 • 880415-14-5531</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-mono font-bold text-slate-900 dark:text-white whitespace-nowrap">RM 6,500.00</td>
                        <td class="py-4 px-6 font-mono whitespace-nowrap">
                            <div>EE: <span class="font-semibold text-slate-900 dark:text-white">RM 715.00</span></div>
                            <div class="text-[10px] text-slate-400">ER: RM 780.00 (12%)</div>
                        </td>
                        <td class="py-4 px-6 font-mono whitespace-nowrap">
                            <div>SOCSO: RM 30.00</div>
                            <div class="text-[10px] text-slate-400">EIS: RM 12.00</div>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-semibold bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border border-rose-100 dark:border-rose-900">
                                RM 262.50
                            </span>
                        </td>
                        <td class="py-4 px-6 font-mono text-sm font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                            RM 5,480.50
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap space-x-2">
                            <a href="#" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Payslip</a>
                            <a href="#" class="font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Edit</a>
                        </td>
                    </tr>

                    <!-- Row 2 -->
                    <tr class="hover:bg-slate-50/70 dark:hover:bg-slate-800/50 transition">
                        <td class="py-4 px-6 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-sky-600 text-white flex items-center justify-center font-bold text-xs">
                                    CL
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900 dark:text-white">Chong Wei Ling</div>
                                    <div class="text-[11px] text-slate-400 font-mono">EMP-00105 • 920702-10-5892</div>
                                </div>
                            </div>
                        </td>
                        <td class="py-4 px-6 font-mono font-bold text-slate-900 dark:text-white whitespace-nowrap">RM 4,200.00</td>
                        <td class="py-4 px-6 font-mono whitespace-nowrap">
                            <div>EE: <span class="font-semibold text-slate-900 dark:text-white">RM 462.00</span></div>
                            <div class="text-[10px] text-slate-400">ER: RM 546.00 (13%)</div>
                        </td>
                        <td class="py-4 px-6 font-mono whitespace-nowrap">
                            <div>SOCSO: RM 21.00</div>
                            <div class="text-[10px] text-slate-400">EIS: RM 8.40</div>
                        </td>
                        <td class="py-4 px-6 whitespace-nowrap">
                            <span class="px-2 py-0.5 rounded text-xs font-mono font-semibold bg-rose-50 dark:bg-rose-950 text-rose-700 dark:text-rose-300 border border-rose-100 dark:border-rose-900">
                                RM 42.00
                            </span>
                        </td>
                        <td class="py-4 px-6 font-mono text-sm font-bold text-emerald-600 dark:text-emerald-400 whitespace-nowrap">
                            RM 3,666.60
                        </td>
                        <td class="py-4 px-6 text-right whitespace-nowrap space-x-2">
                            <a href="#" class="font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">Payslip</a>
                            <a href="#" class="font-semibold text-slate-400 hover:text-slate-600 dark:hover:text-slate-200">Edit</a>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>

        <!-- Table Pagination Footer -->
        <div class="px-6 py-3.5 bg-slate-50/75 dark:bg-slate-950/60 border-t border-slate-100 dark:border-slate-800 flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
            <span>Showing 2 of 48 active employees in batch</span>
            <div class="flex items-center gap-1.5">
                <button class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer">Previous</button>
                <button class="px-2.5 py-1 rounded-lg border border-indigo-600 bg-indigo-600 text-white font-bold cursor-pointer">1</button>
                <button class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer">2</button>
                <button class="px-2.5 py-1 rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer">Next</button>
            </div>
        </div>
    </div>
</x-layouts.admin>
