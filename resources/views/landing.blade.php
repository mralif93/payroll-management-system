<x-layouts.app 
    title="PayFlow MY - Enterprise Malaysian Payroll Management System"
    description="Automated, accurate, and 100% compliant Malaysian payroll system. Built-in EPF, SOCSO, EIS, PCB Computerised calculation, and bank autopay exports."
>
    <!-- SECTION 1 (HERO): INDIGO / BLUE THEME -->
    <section class="relative overflow-hidden pt-12 pb-20 sm:pt-16 sm:pb-28 bg-gradient-to-b from-indigo-50/60 via-slate-50 to-white dark:from-slate-900 dark:via-indigo-950/40 dark:to-slate-900 border-b border-indigo-100 dark:border-slate-800 transition-colors">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[650px] h-[450px] bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl pointer-events-none -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-8">
            
            <!-- Badge Pill -->
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-indigo-50 dark:bg-indigo-950/80 border border-indigo-200 dark:border-indigo-800 text-xs font-semibold text-indigo-700 dark:text-indigo-300 animate__animated animate__fadeInDown">
                <span class="w-2 h-2 rounded-full bg-indigo-600 dark:bg-indigo-400 animate-pulse"></span>
                <span>Fully Updated for 2026 Malaysian Statutory Regulations</span>
            </div>

            <!-- Hero Heading -->
            <div class="max-w-4xl mx-auto space-y-4 animate__animated animate__fadeIn">
                <h1 class="text-3xl sm:text-5xl lg:text-6xl font-extrabold text-slate-900 dark:text-white tracking-tight leading-tight">
                    Flawless Malaysian Payroll. <br>
                    <span class="bg-gradient-to-r from-indigo-600 via-indigo-700 to-blue-600 dark:from-indigo-400 dark:via-blue-400 dark:to-teal-300 bg-clip-text text-transparent">
                        Statutory Compliance Built-In.
                    </span>
                </h1>
                <p class="text-sm sm:text-base lg:text-lg text-slate-600 dark:text-slate-300 max-w-2xl mx-auto leading-relaxed">
                    Automate employee compensation, statutory deductions (KWSP, SOCSO, EIS, PCB, HRD Corp), Maybank2e/CIMB autopay files, and annual Form EA generation with zero manual spreadsheets.
                </p>
            </div>

            <!-- Action CTAs -->
            <div class="flex flex-wrap items-center justify-center gap-4 animate__animated animate__fadeInUp">
                <x-button variant="primary" size="lg" href="#calculator-preview" icon="bx-play-circle">
                    Try Statutory Calculator
                </x-button>
                <x-button variant="secondary" size="lg" href="/demo" icon="bx-layer">
                    Explore UI Kit Components
                </x-button>
            </div>

            <!-- Trust Metrics / Stats Row -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 max-w-4xl mx-auto pt-10 text-left">
                <x-stat-card 
                    title="KWSP / EPF Rates" 
                    value="11% / 13%" 
                    icon="bx-shield-quarter" 
                    iconColor="indigo" 
                    subtext="RM5k bracket & age 60+ rules" 
                />
                <x-stat-card 
                    title="PERKESO Ceiling" 
                    value="RM 6,000" 
                    icon="bx-check-shield" 
                    iconColor="sky" 
                    subtext="Statutory monthly cap" 
                />
                <x-stat-card 
                    title="LHDN PCB" 
                    value="100% Exact" 
                    icon="bx-calculator" 
                    iconColor="emerald" 
                    subtext="Official Computerised formula" 
                />
                <x-stat-card 
                    title="Bank Autopay" 
                    value="1-Click" 
                    icon="bx-building-house" 
                    iconColor="amber" 
                    subtext="Maybank2e & CIMB BizChannel" 
                />
            </div>

        </div>
    </section>

    <!-- SECTION 2 (STATUTORY MODULES): SLATE / COOL GRAY THEME -->
    <section id="features" class="py-20 bg-slate-100/70 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-12">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <x-badge variant="indigo">Statutory Engine Suite</x-badge>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Engineered to Malaysian Government Standards</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Strictly mapped to legislative acts with effective date tracking.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                
                <!-- EPF Card -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs hover:border-indigo-300 dark:hover:border-indigo-500 hover:shadow-md transition group space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-indigo-50 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="bx bxs-institution"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">KWSP / EPF Automation</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Automatically handles standard 11% employee contribution, 13% employer contribution for wages ≤ RM5k (12% for > RM5k), senior age 60+ (0% / 4%), and foreign workers (2%).
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-indigo-600 dark:text-indigo-400 font-semibold">
                        <span>i-Akaun txt ready</span>
                        <i class="bx bx-right-arrow-alt text-base"></i>
                    </div>
                </div>

                <!-- SOCSO Card -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs hover:border-sky-300 dark:hover:border-sky-500 hover:shadow-md transition group space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-sky-50 dark:bg-sky-950/80 text-sky-600 dark:text-sky-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="bx bxs-shield-alt-2"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">PERKESO SOCSO & EIS</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Full Category 1 (Employment Injury & Invalidity) and Category 2 (Employment Injury only) support with dynamic statutory ceiling caps (RM6,000 max wage limit).
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-sky-600 dark:text-sky-400 font-semibold">
                        <span>ASSIST CSV ready</span>
                        <i class="bx bx-right-arrow-alt text-base"></i>
                    </div>
                </div>

                <!-- LHDN PCB Card -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs hover:border-emerald-300 dark:hover:border-emerald-500 hover:shadow-md transition group space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-emerald-50 dark:bg-emerald-950/80 text-emerald-600 dark:text-emerald-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="bx bxs-calculator"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">LHDN PCB / MTD Calculator</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Official LHDN computerized formula with projected annual net taxable income, TP1 tax relief deductions, prior month zakat deductions, and additional remuneration deltas.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-emerald-600 dark:text-emerald-400 font-semibold">
                        <span>CP39 text export</span>
                        <i class="bx bx-right-arrow-alt text-base"></i>
                    </div>
                </div>

                <!-- Overtime Card -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs hover:border-amber-300 dark:hover:border-amber-500 hover:shadow-md transition group space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-amber-50 dark:bg-amber-950/80 text-amber-600 dark:text-amber-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="bx bx-time-five"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">EA 1955 Overtime Engine</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Calculates statutory Ordinary Rate of Pay (ORP) & Hourly Rate of Pay (HRP) with standard multipliers (1.5x Normal, 2.0x Rest Day, 3.0x Public Holiday).
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-amber-600 dark:text-amber-400 font-semibold">
                        <span>Section 60I compliant</span>
                        <i class="bx bx-right-arrow-alt text-base"></i>
                    </div>
                </div>

                <!-- Bank Autopay Card -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs hover:border-purple-300 dark:hover:border-purple-500 hover:shadow-md transition group space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-purple-50 dark:bg-purple-950/80 text-purple-600 dark:text-purple-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="bx bxs-credit-card"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">Bank Autopay Drivers</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Generate direct upload payment batch files for Maybank2e (HDR/DTL structured format) and CIMB BizChannel (Bulk Payment CSV) with zero manual formatting.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-purple-600 dark:text-purple-400 font-semibold">
                        <span>Direct corporate upload</span>
                        <i class="bx bx-right-arrow-alt text-base"></i>
                    </div>
                </div>

                <!-- PDPA Security Card -->
                <div class="bg-white dark:bg-slate-800 p-6 rounded-2xl border border-slate-200 dark:border-slate-700/80 shadow-xs hover:border-rose-300 dark:hover:border-rose-500 hover:shadow-md transition group space-y-4">
                    <div class="w-12 h-12 rounded-xl bg-rose-50 dark:bg-rose-950/80 text-rose-600 dark:text-rose-400 flex items-center justify-center text-2xl group-hover:scale-110 transition-transform">
                        <i class="bx bxs-lock-alt"></i>
                    </div>
                    <div class="space-y-1.5">
                        <h3 class="text-base font-bold text-slate-900 dark:text-white">PDPA 2010 Encryption</h3>
                        <p class="text-xs text-slate-600 dark:text-slate-300 leading-relaxed">
                            Field-level encryption for sensitive PII data (NRIC, Passport numbers, Employee bank account numbers) and frozen immutable state locks for approved batch runs.
                        </p>
                    </div>
                    <div class="pt-2 border-t border-slate-100 dark:border-slate-700 flex items-center justify-between text-xs text-rose-600 dark:text-rose-400 font-semibold">
                        <span>7-year audit compliance</span>
                        <i class="bx bx-right-arrow-alt text-base"></i>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- SECTION 3 (COMPLIANCE TABLE): EMERALD / TEAL TINT THEME -->
    <section id="compliance" class="py-20 bg-emerald-50/40 dark:bg-emerald-950/15 border-b border-emerald-100 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            
            <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
                <div>
                    <x-badge variant="success">Legal Framework</x-badge>
                    <h2 class="text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight mt-1">Malaysian Statutory Regulatory Matrix</h2>
                </div>
                <span class="text-xs text-emerald-800 dark:text-emerald-400 font-medium">Verified against Malaysian Acts of Parliament</span>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-emerald-200/80 dark:border-slate-700 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700 text-left text-xs">
                        <thead class="bg-emerald-50/80 dark:bg-slate-900/60 text-slate-600 dark:text-slate-300 font-bold uppercase tracking-wider">
                            <tr>
                                <th class="py-3.5 px-6">Regulatory Body</th>
                                <th class="py-3.5 px-6">Legislative Act</th>
                                <th class="py-3.5 px-6">System Implementation</th>
                                <th class="py-3.5 px-6 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700 text-slate-700 dark:text-slate-300">
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/50 transition">
                                <td class="py-4 px-6 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-indigo-600"></span> KWSP / EPF
                                </td>
                                <td class="py-4 px-6 font-medium">Employees Provident Fund Act 1991</td>
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-300">11% EE, 13%/12% ER split, Age 60+ (0%/4%), Foreign worker baseline 2%.</td>
                                <td class="py-4 px-6 text-right">
                                    <x-badge variant="success" dot="true">Compliant</x-badge>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/50 transition">
                                <td class="py-4 px-6 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-sky-600"></span> PERKESO / SOCSO
                                </td>
                                <td class="py-4 px-6 font-medium">Employees' Social Security Act 1969</td>
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-300">Category 1 (Injury & Invalidity) & Category 2 with RM6,000 wage ceiling limit.</td>
                                <td class="py-4 px-6 text-right">
                                    <x-badge variant="success" dot="true">Compliant</x-badge>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/50 transition">
                                <td class="py-4 px-6 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-sky-500"></span> PERKESO / EIS
                                </td>
                                <td class="py-4 px-6 font-medium">Employment Insurance System Act 2017</td>
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-300">0.2% Employee + 0.2% Employer contribution up to RM6,000 wage ceiling.</td>
                                <td class="py-4 px-6 text-right">
                                    <x-badge variant="success" dot="true">Compliant</x-badge>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/50 transition">
                                <td class="py-4 px-6 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span> LHDN / HASiL
                                </td>
                                <td class="py-4 px-6 font-medium">Income Tax Act 1967</td>
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-300">Computerised calculation method for PCB, Form TP1/TP3 reliefs, and CP39 files.</td>
                                <td class="py-4 px-6 text-right">
                                    <x-badge variant="success" dot="true">Compliant</x-badge>
                                </td>
                            </tr>
                            <tr class="hover:bg-slate-50/60 dark:hover:bg-slate-700/50 transition">
                                <td class="py-4 px-6 font-bold text-slate-900 dark:text-white flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-amber-600"></span> JTK / KSM
                                </td>
                                <td class="py-4 px-6 font-medium">Employment Act 1955 (Amended 2022/2023)</td>
                                <td class="py-4 px-6 text-slate-600 dark:text-slate-300">7-day payment window, 50% max deduction threshold, and 1.5x/2.0x/3.0x OT.</td>
                                <td class="py-4 px-6 text-right">
                                    <x-badge variant="success" dot="true">Compliant</x-badge>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </section>

    <!-- SECTION 4 (INTERACTIVE SIMULATOR): PURPLE / VIOLET TINT THEME -->
    <section id="calculator-preview" class="py-20 bg-purple-50/30 dark:bg-purple-950/15 border-b border-purple-100 dark:border-slate-800 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
            
            <div class="text-center max-w-2xl mx-auto space-y-2">
                <x-badge variant="purple">Interactive Simulator</x-badge>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 dark:text-white tracking-tight">Real-Time Statutory Deduction Simulator</h2>
                <p class="text-xs sm:text-sm text-slate-500 dark:text-slate-400">Enter a salary amount to test real-time Malaysian statutory calculation formulas.</p>
            </div>

            <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-200 dark:border-slate-700 shadow-xl overflow-hidden grid grid-cols-1 lg:grid-cols-12">
                
                <!-- Left Input Form Area -->
                <div class="p-6 sm:p-8 lg:col-span-5 bg-slate-50/80 dark:bg-slate-900/60 border-b lg:border-b-0 lg:border-r border-slate-200 dark:border-slate-700 space-y-6">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2">
                        <i class="bx bx-slider-alt text-indigo-600 dark:text-indigo-400 text-lg"></i>
                        Remuneration Parameters
                    </h3>

                    <div class="space-y-4">
                        <!-- Salary Input -->
                        <x-input 
                            id="calc_basic_salary" 
                            label="Monthly Basic Salary" 
                            type="number" 
                            value="2000" 
                            prefix="MYR"
                            oninput="updateSimulator()"
                        />

                        <!-- Calculation Schedule / Effective Period -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Statutory Schedule / Effective Date</label>
                            <select id="calc_effective_date" onchange="updateSimulator()" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="2026_jun">Effective June 2026 (Includes SKBBK / Lindung 24 Jam)</option>
                                <option value="standard">Standard Scheme (Classic Statutory Rates)</option>
                            </select>
                        </div>

                        <!-- Citizenship Select -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Citizenship &amp; Age Category</label>
                            <select id="calc_citizenship" onchange="updateSimulator()" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="malaysian_under60">Malaysian Citizen (Age &lt; 60 - Category 1)</option>
                                <option value="malaysian_over60">Malaysian Citizen (Age ≥ 60 - Category 2)</option>
                                <option value="foreign_worker">Foreign Worker (Non-Citizen)</option>
                            </select>
                        </div>

                        <!-- Tax Category -->
                        <div class="space-y-1.5">
                            <label class="block text-xs font-semibold text-slate-700 dark:text-slate-300">Tax Category / Marital Status</label>
                            <select id="calc_tax_cat" onchange="updateSimulator()" class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 px-3.5 py-2.5 text-xs text-slate-900 dark:text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500">
                                <option value="single">Single / Divorced (Category 1)</option>
                                <option value="married_working">Married (Working Spouse) (Category 1)</option>
                                <option value="married_non_working">Married (Non-Working Spouse) (Category 2)</option>
                            </select>
                        </div>

                        <!-- Voluntary EPF 9% Toggle Switch -->
                        <div class="p-3.5 rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                            <x-toggle 
                                id="calc_epf_9" 
                                label="Voluntary Reduced EPF Rate" 
                                description="Switch employee EPF contribution to 9% (vs statutory 11%)" 
                                color="indigo"
                                onchange="updateSimulator()"
                                class="w-full"
                            />
                        </div>
                    </div>
                </div>

                <!-- Right Calculation Results Breakdown -->
                <div class="p-6 sm:p-8 lg:col-span-7 space-y-6 flex flex-col justify-between bg-white dark:bg-slate-800/90">
                    <div class="space-y-6">
                        
                        <!-- Take-Home Pay Hero Highlight Banner -->
                        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-500/10 via-emerald-500/5 to-teal-500/10 dark:from-emerald-950/40 dark:via-slate-800 dark:to-teal-950/30 p-6 border border-emerald-200/80 dark:border-emerald-800/60 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div>
                                <div class="flex items-center gap-2">
                                    <span class="text-xs font-bold text-emerald-800 dark:text-emerald-300 uppercase tracking-wider">Estimated Monthly Net Take-Home</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 dark:bg-emerald-900/80 text-emerald-800 dark:text-emerald-200">
                                        Real-Time
                                    </span>
                                </div>
                                <div id="calc_net_salary" class="text-3xl sm:text-4xl font-extrabold text-emerald-600 dark:text-emerald-400 tracking-tight font-mono mt-1">RM 5,480.50</div>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Amount transferred directly into employee bank account</p>
                            </div>

                            <div class="shrink-0 flex sm:flex-col items-end gap-1">
                                <span class="text-[11px] text-slate-400 font-medium">Total Deductions</span>
                                <span id="calc_total_deductions" class="text-sm font-bold text-rose-600 dark:text-rose-400 font-mono">- RM 1,019.50</span>
                            </div>
                        </div>

                        <!-- Section: Employee Statutory Deductions -->
                        <div class="space-y-3">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/80 pb-2">
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="bx bx-user-minus text-rose-500 text-base"></i>
                                    Employee Deductions (Salary Cuts)
                                </span>
                                <span class="text-[11px] text-slate-400">Paid by Employee</span>
                            </div>
                            
                            <div id="calc_employee_deductions_grid" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3.5">
                                <!-- EPF EE -->
                                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-700 hover:border-indigo-300 dark:hover:border-indigo-600 transition">
                                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1.5">
                                        <span class="font-semibold">KWSP / EPF</span>
                                        <span id="calc_epf_ee_rate_badge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400">11%</span>
                                    </div>
                                    <div id="calc_epf_ee" class="font-bold text-slate-900 dark:text-white font-mono text-base">RM 220.00</div>
                                </div>

                                <!-- SOCSO Standard EE -->
                                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-700 hover:border-sky-300 dark:hover:border-sky-600 transition">
                                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1.5">
                                        <span class="font-semibold">SOCSO (Act 4)</span>
                                        <span id="calc_socso_ee_rate_badge" class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-50 dark:bg-sky-950 text-sky-600 dark:text-sky-400">Cat 1</span>
                                    </div>
                                    <div id="calc_socso_ee" class="font-bold text-slate-900 dark:text-white font-mono text-base">RM 9.90</div>
                                </div>

                                <!-- SKBBK / Lindung 24 Jam EE (New 2026) -->
                                <div id="calc_skbbk_card" class="p-3.5 rounded-xl bg-purple-50/60 dark:bg-purple-950/40 border border-purple-200/80 dark:border-purple-800/60 hover:border-purple-300 dark:hover:border-purple-600 transition">
                                    <div class="flex items-center justify-between text-xs text-purple-700 dark:text-purple-300 mb-1.5">
                                        <span class="font-bold flex items-center gap-1">
                                            SKBBK
                                            <i class="bx bxs-badge-check text-purple-600 text-sm"></i>
                                        </span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-purple-100 dark:bg-purple-900/80 text-purple-800 dark:text-purple-200">Lindung 24 Jam</span>
                                    </div>
                                    <div id="calc_skbbk_ee" class="font-bold text-purple-950 dark:text-purple-100 font-mono text-base">RM 14.50</div>
                                </div>

                                <!-- EIS EE -->
                                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-700 hover:border-sky-300 dark:hover:border-sky-600 transition">
                                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1.5">
                                        <span class="font-semibold">SIP / EIS</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-sky-50 dark:bg-sky-950 text-sky-600 dark:text-sky-400">0.2%</span>
                                    </div>
                                    <div id="calc_eis_ee" class="font-bold text-slate-900 dark:text-white font-mono text-base">RM 3.90</div>
                                </div>

                                <!-- LHDN PCB -->
                                <div class="p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/70 border border-slate-200 dark:border-slate-700 hover:border-rose-300 dark:hover:border-rose-600 transition">
                                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400 mb-1.5">
                                        <span class="font-semibold">LHDN PCB</span>
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-rose-50 dark:bg-rose-950 text-rose-600 dark:text-rose-400">MTD Tax</span>
                                    </div>
                                    <div id="calc_pcb" class="font-bold text-slate-900 dark:text-white font-mono text-base">RM 0.00</div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Employer Statutory Contributions -->
                        <div class="space-y-3 pt-2">
                            <div class="flex items-center justify-between border-b border-slate-100 dark:border-slate-700/80 pb-2">
                                <span class="text-xs font-bold text-slate-800 dark:text-slate-200 uppercase tracking-wider flex items-center gap-1.5">
                                    <i class="bx bx-building text-indigo-500 text-base"></i>
                                    Employer Contributions (Company Cost)
                                </span>
                                <span class="text-[11px] text-slate-400">Additional to Salary</span>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <!-- EPF ER -->
                                <div class="p-3.5 rounded-xl bg-indigo-50/60 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60">
                                    <div class="flex items-center justify-between text-[11px] text-indigo-700 dark:text-indigo-300 mb-1">
                                        <span class="font-medium">EPF Employer</span>
                                        <span id="calc_epf_er_rate_badge" class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-white dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 shadow-2xs">12%</span>
                                    </div>
                                    <div id="calc_epf_er" class="font-bold text-indigo-950 dark:text-indigo-100 font-mono text-sm sm:text-base">RM 780.00</div>
                                </div>

                                <!-- SOCSO ER -->
                                <div class="p-3.5 rounded-xl bg-indigo-50/60 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60">
                                    <div class="flex items-center justify-between text-[11px] text-indigo-700 dark:text-indigo-300 mb-1">
                                        <span class="font-medium">SOCSO Employer</span>
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-white dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 shadow-2xs">1.75%</span>
                                    </div>
                                    <div id="calc_socso_er" class="font-bold text-indigo-950 dark:text-indigo-100 font-mono text-sm sm:text-base">RM 105.00</div>
                                </div>

                                <!-- EIS ER -->
                                <div class="p-3.5 rounded-xl bg-indigo-50/60 dark:bg-indigo-950/40 border border-indigo-100 dark:border-indigo-900/60">
                                    <div class="flex items-center justify-between text-[11px] text-indigo-700 dark:text-indigo-300 mb-1">
                                        <span class="font-medium">EIS Employer</span>
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-semibold bg-white dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 shadow-2xs">0.2%</span>
                                    </div>
                                    <div id="calc_eis_er" class="font-bold text-indigo-950 dark:text-indigo-100 font-mono text-sm sm:text-base">RM 12.00</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statutory Callout Notice -->
                    <div class="mt-4 flex items-start gap-3 p-3.5 rounded-xl bg-slate-50 dark:bg-slate-900/60 border border-slate-200/80 dark:border-slate-700 text-slate-600 dark:text-slate-300 text-xs">
                        <i class="bx bx-info-circle text-indigo-600 dark:text-indigo-400 text-lg shrink-0 mt-0.5"></i>
                        <div class="leading-relaxed">
                            <span class="font-bold text-slate-900 dark:text-white">Statutory Ceiling Applied:</span>
                            SOCSO and EIS contributions are automatically capped at the statutory <strong>RM6,000</strong> monthly wage limit in compliance with PERKESO guidelines.
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </section>

    <!-- SECTION 5 (CALL TO ACTION): DARK SLATE / INDIGO THEME -->
    <section class="py-20 bg-slate-900 dark:bg-black text-white relative overflow-hidden transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center space-y-6">
            <div class="max-w-2xl mx-auto space-y-3">
                <h2 class="text-2xl sm:text-4xl font-extrabold tracking-tight">Ready to Modernize Your Payroll?</h2>
                <p class="text-slate-300 dark:text-slate-400 text-xs sm:text-sm leading-relaxed">
                    Explore our full UI kit component demo or integrate the calculation engines into your enterprise Laravel backend today.
                </p>
            </div>
            <div class="flex flex-wrap items-center justify-center gap-4">
                <x-button variant="primary" size="lg" href="/demo" icon="bx-grid-alt">
                    Open UI Kit Showcase
                </x-button>
                <x-button variant="secondary" size="lg" href="https://github.com/mralif93/payroll-management-system" target="_blank" icon="bxl-github">
                    View GitHub Repository
                </x-button>
            </div>
        </div>
    </section>

    <!-- Simulator Script Injected in Scripts Slot -->
    <x-slot name="scripts">
        <script>
            // Official PERKESO & EIS statutory table helper
            function getSocsoAndEisContributions(salary, citizenship, effectiveDate) {
                const cappedWage = Math.min(salary, 6000);
                let socsoEe = 0;
                let skbbkEe = 0;
                let socsoEr = 0;
                let eisEe = 0;
                let eisEr = 0;

                if (citizenship === 'foreign_worker') {
                    socsoEe = 0;
                    skbbkEe = 0;
                    socsoEr = Math.round(cappedWage * 0.0125 * 100) / 100;
                    eisEe = 0;
                    eisEr = 0;
                    return { socsoEe, skbbkEe, socsoEr, eisEe, eisEr };
                }

                if (citizenship === 'malaysian_over60') {
                    // Category 2 (Employment Injury Only)
                    socsoEe = 0.00;
                    skbbkEe = (effectiveDate === '2026_jun') ? 7.00 : 0.00;
                    socsoEr = Math.round(cappedWage * 0.0125 * 100) / 100;
                    eisEe = 0;
                    eisEr = 0;
                    return { socsoEe, skbbkEe, socsoEr, eisEe, eisEr };
                }

                // Category 1: Full Employment Injury & Invalidity (+ SKBBK if 2026 Jun)
                if (effectiveDate === '2026_jun') {
                    // 2026 June Schedule with SKBBK (Lindung 24 Jam)
                    if (salary <= 30) { socsoEr = 0.40; socsoEe = 0.10; skbbkEe = 0.00; }
                    else if (salary <= 50) { socsoEr = 0.70; socsoEe = 0.20; skbbkEe = 0.00; }
                    else if (salary <= 100) { socsoEr = 1.50; socsoEe = 0.50; skbbkEe = 0.00; }
                    else if (salary <= 140) { socsoEr = 2.10; socsoEe = 0.60; skbbkEe = 0.00; }
                    else if (salary <= 200) { socsoEr = 2.95; socsoEe = 0.85; skbbkEe = 0.00; }
                    else if (salary <= 300) { socsoEr = 4.35; socsoEe = 1.25; skbbkEe = 0.00; }
                    else if (salary <= 400) { socsoEr = 6.15; socsoEe = 1.75; skbbkEe = 0.00; }
                    else if (salary <= 500) { socsoEr = 7.85; socsoEe = 2.25; skbbkEe = 0.00; }
                    else if (salary <= 600) { socsoEr = 9.65; socsoEe = 2.75; skbbkEe = 0.00; }
                    else if (salary <= 700) { socsoEr = 11.35; socsoEe = 3.25; skbbkEe = 0.00; }
                    else if (salary <= 800) { socsoEr = 13.15; socsoEe = 3.75; skbbkEe = 0.00; }
                    else if (salary <= 900) { socsoEr = 14.85; socsoEe = 4.25; skbbkEe = 0.00; }
                    else if (salary <= 1000) { socsoEr = 16.65; socsoEe = 4.75; skbbkEe = 0.00; }
                    else if (salary <= 1100) { socsoEr = 18.35; socsoEe = 5.25; skbbkEe = 0.00; }
                    else if (salary <= 1200) { socsoEr = 20.15; socsoEe = 5.75; skbbkEe = 0.00; }
                    else if (salary <= 1300) { socsoEr = 21.85; socsoEe = 6.25; skbbkEe = 0.00; }
                    else if (salary <= 1400) { socsoEr = 23.65; socsoEe = 6.75; skbbkEe = 0.00; }
                    else if (salary <= 1500) { socsoEr = 25.35; socsoEe = 7.25; skbbkEe = 0.00; }
                    else if (salary <= 1600) { socsoEr = 27.15; socsoEe = 7.75; skbbkEe = 0.00; }
                    else if (salary <= 1700) { socsoEr = 28.85; socsoEe = 8.25; skbbkEe = 0.00; }
                    else if (salary <= 1800) { socsoEr = 30.65; socsoEe = 8.75; skbbkEe = 0.00; }
                    else if (salary <= 1900) { socsoEr = 32.35; socsoEe = 9.25; skbbkEe = 0.00; }
                    else if (salary <= 2000) { 
                        socsoEr = 34.15; 
                        socsoEe = 9.90;   // Act 4 Base SOCSO
                        skbbkEe = 14.50;  // 2026 Non-Employment Injury Scheme (Lindung 24 Jam)
                    } else {
                        socsoEr = Math.round(cappedWage * 0.0175 * 100) / 100;
                        socsoEe = Math.round(cappedWage * 0.005 * 100) / 100;
                        skbbkEe = Math.round(cappedWage * 0.00725 * 100) / 100;
                    }

                    // EIS Schedule (0.2% EE & 0.2% ER table matched)
                    if (salary <= 2000) {
                        eisEe = 3.90;
                        eisEr = 3.90;
                    } else {
                        eisEe = Math.round(cappedWage * 0.002 * 100) / 100;
                        eisEr = Math.round(cappedWage * 0.002 * 100) / 100;
                    }
                } else {
                    // Standard Classic Statutory Formula
                    socsoEe = Math.round(cappedWage * 0.005 * 100) / 100;
                    skbbkEe = 0.00;
                    socsoEr = Math.round(cappedWage * 0.0175 * 100) / 100;
                    eisEe = Math.round(cappedWage * 0.002 * 100) / 100;
                    eisEr = Math.round(cappedWage * 0.002 * 100) / 100;
                }

                return { socsoEe, skbbkEe, socsoEr, eisEe, eisEr };
            }

            function updateSimulator() {
                const salary = parseFloat(document.getElementById('calc_basic_salary').value) || 0;
                const effectiveDate = document.getElementById('calc_effective_date').value;
                const citizenship = document.getElementById('calc_citizenship').value;
                const taxCat = document.getElementById('calc_tax_cat').value;
                const isEpf9 = document.getElementById('calc_epf_9').checked;

                // EPF Calculations (Act 1991)
                let epfEeRate = isEpf9 ? 0.09 : 0.11;
                let epfErRate = salary <= 5000 ? 0.13 : 0.12;

                let epfEe = 0;
                let epfEr = 0;

                if (citizenship === 'foreign_worker') {
                    epfEe = Math.round(salary * 0.02 * 100) / 100;
                    epfEr = Math.round(salary * 0.02 * 100) / 100;
                } else if (citizenship === 'malaysian_over60') {
                    epfEe = 0;
                    epfEr = Math.round(salary * 0.04 * 100) / 100;
                } else {
                    epfEe = Math.round(salary * epfEeRate * 100) / 100;
                    epfEr = Math.round(salary * epfErRate * 100) / 100;
                }

                // SOCSO, SKBBK & EIS statutory calculations
                const { socsoEe, skbbkEe, socsoEr, eisEe, eisEr } = getSocsoAndEisContributions(salary, citizenship, effectiveDate);

                // Estimate PCB (Income Tax Act 1967)
                let relief = 9000;
                if (taxCat === 'married_non_working') relief += 4000;
                const epfRelief = Math.min(epfEe * 12, 4000);
                const annualIncome = Math.max(0, (salary * 12) - relief - epfRelief);
                
                let annualTax = 0;
                if (annualIncome > 100000) {
                    annualTax = 9400 + (annualIncome - 100000) * 0.25;
                } else if (annualIncome > 70000) {
                    annualTax = 3700 + (annualIncome - 70000) * 0.19;
                } else if (annualIncome > 50000) {
                    annualTax = 1500 + (annualIncome - 50000) * 0.11;
                } else if (annualIncome > 35000) {
                    annualTax = 600 + (annualIncome - 35000) * 0.06;
                } else if (annualIncome > 20000) {
                    annualTax = 150 + (annualIncome - 20000) * 0.03;
                }
                const pcb = Math.max(0, Math.round((annualTax / 12) * 100) / 100);

                // Total Deductions & Net Take-Home Pay
                const totalDeductions = epfEe + socsoEe + skbbkEe + eisEe + pcb;
                const netSalary = Math.max(0, salary - totalDeductions);

                // Update UI Display
                document.getElementById('calc_net_salary').textContent = 'RM ' + netSalary.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                document.getElementById('calc_total_deductions').textContent = '- RM ' + totalDeductions.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                
                document.getElementById('calc_epf_ee').textContent = 'RM ' + epfEe.toFixed(2);
                document.getElementById('calc_socso_ee').textContent = 'RM ' + socsoEe.toFixed(2);
                document.getElementById('calc_skbbk_ee').textContent = 'RM ' + skbbkEe.toFixed(2);
                document.getElementById('calc_eis_ee').textContent = 'RM ' + eisEe.toFixed(2);
                document.getElementById('calc_pcb').textContent = 'RM ' + pcb.toFixed(2);

                // Show/Hide SKBBK Card if applicable
                const skbbkCard = document.getElementById('calc_skbbk_card');
                if (skbbkCard) {
                    if (skbbkEe > 0) {
                        skbbkCard.classList.remove('hidden');
                    } else {
                        skbbkCard.classList.add('hidden');
                    }
                }

                document.getElementById('calc_epf_er').textContent = 'RM ' + epfEr.toFixed(2);
                document.getElementById('calc_socso_er').textContent = 'RM ' + socsoEr.toFixed(2);
                document.getElementById('calc_eis_er').textContent = 'RM ' + eisEr.toFixed(2);

                // Dynamic rate badges
                document.getElementById('calc_epf_ee_rate_badge').textContent = (epfEeRate * 100).toFixed(0) + '%';
                document.getElementById('calc_epf_er_rate_badge').textContent = (epfErRate * 100).toFixed(0) + '%';
            }

            // Initialize simulator on load
            document.addEventListener('DOMContentLoaded', updateSimulator);
        </script>
    </x-slot>
</x-layouts.app>
