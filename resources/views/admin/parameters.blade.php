<x-layouts.admin title="Statutory Parameters & Compliance Rules">

    <div class="space-y-8">
        
        <!-- Header Banner & Action -->
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <div class="flex items-center gap-2">
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">Statutory Parameters &amp; Tax Tables</h1>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-950 text-emerald-800 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                        Effective 2026
                    </span>
                </div>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">
                    Centralized effective-dated Malaysian statutory schedules (KWSP, PERKESO, SKBBK 2026, EIS, LHDN PCB &amp; HRD Corp).
                </p>
            </div>

            <div class="flex items-center gap-2">
                <x-button variant="secondary" size="sm" icon="bx-history">
                    Audit Version Log
                </x-button>
                <x-button variant="primary" size="sm" icon="bx-plus" onclick="alert('Statutory parameters can be modified by authorized Super-Admins.')">
                    Add New Policy Gazette
                </x-button>
            </div>
        </div>

        <!-- 1. KWSP / EPF Parameter Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-bold text-lg">
                        <i class="bx bxs-bank"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">KWSP / EPF Statutory Parameters (Act 1991)</h2>
                        <span class="text-[11px] text-slate-400 font-mono">P.U. (A) EPF Act 1991 Third Schedule</span>
                    </div>
                </div>
                <x-badge variant="indigo" dot="true">Active Standard</x-badge>
            </div>

            <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Standard Employee</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">11.0%</span>
                    <span class="text-[10px] text-slate-400">Voluntary option 9.0%</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Employer (Wage &le; RM5k)</span>
                    <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400 font-mono mt-1 block">13.0%</span>
                    <span class="text-[10px] text-slate-400">Mandatory statutory low wage</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Employer (Wage &gt; RM5k)</span>
                    <span class="text-xl font-bold text-slate-900 dark:text-white font-mono mt-1 block">12.0%</span>
                    <span class="text-[10px] text-slate-400">Standard statutory bracket</span>
                </div>
                <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                    <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Senior Citizen (Age &ge; 60)</span>
                    <span class="text-xl font-bold text-emerald-600 dark:text-emerald-400 font-mono mt-1 block">4.0% (ER) / 0% (EE)</span>
                    <span class="text-[10px] text-slate-400">Malaysian Citizens</span>
                </div>
            </div>
        </div>

        <!-- 2. PERKESO / SOCSO (Act 4) & June 2026 SKBBK Card -->
        <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 flex items-center justify-center font-bold text-lg">
                        <i class="bx bxs-shield"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900 dark:text-white">PERKESO SOCSO &amp; SKBBK (Lindung 24 Jam) Schedule</h2>
                        <span class="text-[11px] text-slate-400 font-mono">Effective 1 June 2026 • Wage Ceiling RM6,000</span>
                    </div>
                </div>
                <x-badge variant="purple" dot="true">Includes SKBBK 2026</x-badge>
            </div>

            <div class="p-6 space-y-4">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">Act 4 SOCSO Category 1 (Base)</span>
                        <span class="text-lg font-bold text-slate-900 dark:text-white font-mono mt-1 block">1.75% ER / 0.5% EE</span>
                        <span class="text-[10px] text-slate-400">Employment Injury &amp; Invalidity</span>
                    </div>
                    <div class="p-4 rounded-xl bg-purple-50/60 dark:bg-purple-950/40 border border-purple-200 dark:border-purple-800">
                        <span class="text-xs text-purple-700 dark:text-purple-300 font-bold block">SKBBK (Lindung 24 Jam)</span>
                        <span class="text-lg font-bold text-purple-950 dark:text-purple-100 font-mono mt-1 block">Tiered (e.g. RM14.50 @ RM2k)</span>
                        <span class="text-[10px] text-purple-600 dark:text-purple-400 font-medium">100% Employee Borne</span>
                    </div>
                    <div class="p-4 rounded-xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200 dark:border-slate-700">
                        <span class="text-xs text-slate-500 dark:text-slate-400 font-medium block">SOCSO Category 2 (Age &ge; 60)</span>
                        <span class="text-lg font-bold text-slate-900 dark:text-white font-mono mt-1 block">1.25% ER / RM7.00 SKBBK</span>
                        <span class="text-[10px] text-slate-400">Employment Injury Only</span>
                    </div>
                </div>

                <!-- Sample Bracket Preview Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-200 dark:border-slate-700">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 font-bold uppercase tracking-wider text-[10px]">
                            <tr>
                                <th class="p-3">Monthly Wage Bracket</th>
                                <th class="p-3">Employer Share</th>
                                <th class="p-3">Act 4 Employee</th>
                                <th class="p-3">SKBBK Employee</th>
                                <th class="p-3">Total Employee Cut</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700 text-slate-700 dark:text-slate-300 font-mono">
                            <tr>
                                <td class="p-3 font-sans font-semibold">RM 1,900.01 – RM 2,000.00</td>
                                <td class="p-3 text-slate-900 dark:text-white font-bold">RM 34.15</td>
                                <td class="p-3">RM 9.90</td>
                                <td class="p-3 text-purple-600 dark:text-purple-400 font-bold">RM 14.50</td>
                                <td class="p-3 text-rose-600 dark:text-rose-400 font-bold">RM 24.40</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-sans font-semibold">RM 2,000.01 – RM 2,100.00</td>
                                <td class="p-3 text-slate-900 dark:text-white font-bold">RM 35.85</td>
                                <td class="p-3">RM 10.40</td>
                                <td class="p-3 text-purple-600 dark:text-purple-400 font-bold">RM 15.20</td>
                                <td class="p-3 text-rose-600 dark:text-rose-400 font-bold">RM 25.60</td>
                            </tr>
                            <tr>
                                <td class="p-3 font-sans font-semibold">RM 5,900.01 – RM 6,000.00 (Ceiling)</td>
                                <td class="p-3 text-slate-900 dark:text-white font-bold">RM 104.15</td>
                                <td class="p-3">RM 29.90</td>
                                <td class="p-3 text-purple-600 dark:text-purple-400 font-bold">RM 43.50</td>
                                <td class="p-3 text-rose-600 dark:text-rose-400 font-bold">RM 73.40</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 3. SIP / EIS & LHDN PCB Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- EIS Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 dark:bg-teal-950/80 text-teal-600 dark:text-teal-400 flex items-center justify-center font-bold text-base">
                            <i class="bx bx-briefcase"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">SIP / EIS (Act 800)</h3>
                            <span class="text-[10px] text-slate-400">Employment Insurance System</span>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-teal-600 dark:text-teal-400">0.2% + 0.2%</span>
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs">
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                        <span class="text-[10px] text-slate-400 block">Wage Ceiling Limit</span>
                        <span class="font-bold text-slate-800 dark:text-white font-mono">RM 6,000.00</span>
                    </div>
                    <div class="p-3 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                        <span class="text-[10px] text-slate-400 block">Max Deduction (Capped)</span>
                        <span class="font-bold text-slate-800 dark:text-white font-mono">RM 11.90 each</span>
                    </div>
                </div>
            </div>

            <!-- LHDN Reliefs Card -->
            <div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xs p-5 space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-800 pb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 flex items-center justify-center font-bold text-base">
                            <i class="bx bxs-file-pdf"></i>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-slate-900 dark:text-white">LHDN PCB Standard Reliefs</h3>
                            <span class="text-[10px] text-slate-400">Income Tax Act 1967 (Computerised MTD)</span>
                        </div>
                    </div>
                    <span class="text-xs font-mono font-bold text-rose-600 dark:text-rose-400">Auto MTD Engine</span>
                </div>
                <div class="grid grid-cols-3 gap-2 text-xs">
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                        <span class="text-[9px] text-slate-400 block">Individual (D)</span>
                        <span class="font-bold text-slate-800 dark:text-white font-mono">RM 9,000</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                        <span class="text-[9px] text-slate-400 block">Spouse (S)</span>
                        <span class="font-bold text-slate-800 dark:text-white font-mono">RM 4,000</span>
                    </div>
                    <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800/60">
                        <span class="text-[9px] text-slate-400 block">Per Child (QC)</span>
                        <span class="font-bold text-slate-800 dark:text-white font-mono">RM 2,000</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

</x-layouts.admin>
