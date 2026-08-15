<x-layouts.admin 
    title="Payroll Dashboard - PayFlow MY"
    headerTitle="Monthly Payroll Operations"
    headerSubtitle="Real-time Malaysian payroll processing, statutory deductions summary, and batch disbursement."
>
    <!-- Header Quick Actions -->
    <x-slot name="headerActions">
        <x-button variant="secondary" size="sm" icon="bx-filter">
            Filter Period
        </x-button>
        <x-button variant="primary" size="sm" icon="bx-plus">
            New Payroll Run (Aug 2026)
        </x-button>
    </x-slot>

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
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-800 text-left text-xs">
                <thead class="bg-slate-50 dark:bg-slate-950/60 text-slate-500 dark:text-slate-400 uppercase font-bold tracking-wider">
                    <tr>
                        <th class="py-3.5 px-6">Employee</th>
                        <th class="py-3.5 px-6">Basic Pay</th>
                        <th class="py-3.5 px-6">KWSP EPF (EE / ER)</th>
                        <th class="py-3.5 px-6">SOCSO & EIS</th>
                        <th class="py-3.5 px-6">PCB (MTD)</th>
                        <th class="py-3.5 px-6">Net Take-Home</th>
                        <th class="py-3.5 px-6 text-right">Actions</th>
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
