@props([
    'title' => 'Admin Dashboard - PayFlow MY',
    'headerTitle' => 'Payroll Dashboard',
    'headerSubtitle' => 'Manage Malaysian payroll runs, statutory filings, and bank autopay exports.',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Prevent FOUC for Dark Mode -->
    <script>
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <!-- Styles / Scripts via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $head ?? '' }}
</head>
<body class="h-full bg-slate-100/70 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased selection:bg-indigo-500 selection:text-white transition-colors duration-300 flex overflow-hidden" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <!-- Mobile Sidebar Backdrop Overlay -->
    <div id="sidebar-backdrop" onclick="toggleMobileSidebar()" class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-40 lg:hidden hidden transition-opacity duration-300"></div>

    <!-- SIDEBAR NAVIGATION -->
    <aside id="admin-sidebar" class="fixed lg:static inset-y-0 left-0 -translate-x-full lg:translate-x-0 w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col shrink-0 z-50 transition-transform duration-300 ease-in-out">
        
        <!-- Sidebar Brand Logo & Mobile Close Button -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100 dark:border-slate-800">
            <a href="/" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-500/20 shrink-0">
                    <i class="bx bxs-wallet text-lg"></i>
                </div>
                <div>
                    <span class="text-base font-extrabold text-slate-900 dark:text-white tracking-tight">PayFlow<span class="text-indigo-600 dark:text-indigo-400">MY</span></span>
                    <span class="block text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Admin Console</span>
                </div>
            </a>
            <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition">
                <i class="bx bx-x text-xl"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto text-xs font-semibold">
            
            <div class="px-3 pb-2 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">Main Operations</div>

            <a href="/admin" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('admin') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} transition">
                <i class="bx bxs-dashboard text-lg"></i>
                <span>Payroll Dashboard</span>
            </a>

            <a href="/admin/employees" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/employees*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} transition">
                <i class="bx bx-group text-lg"></i>
                <span>Employee Registry</span>
            </a>

            <a href="/admin/payroll-runs" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/payroll-runs*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} transition">
                <i class="bx bx-calendar-check text-lg"></i>
                <span>Monthly Payroll Runs</span>
            </a>

            <div class="pt-5 px-3 pb-2 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">Statutory & Banking</div>

            <a href="/admin/exports" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/exports*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} transition">
                <i class="bx bx-export text-lg"></i>
                <span>Statutory Exporters</span>
                <span class="ml-auto px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">CP39/EIS</span>
            </a>

            <a href="/admin/bank-autopay" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/bank-autopay*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} transition">
                <i class="bx bxs-bank text-lg"></i>
                <span>Bank Autopay (M2E/CIMB)</span>
            </a>

            <a href="/admin/form-ea" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/form-ea*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} transition">
                <i class="bx bxs-file-pdf text-lg"></i>
                <span>Tax Form EA Compiler</span>
            </a>

            <div class="pt-5 px-3 pb-2 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">System Configuration</div>

            <a href="/admin/parameters" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/parameters*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} transition">
                <i class="bx bx-slider text-lg"></i>
                <span>Statutory Parameters</span>
            </a>

            <a href="/admin/audit-trail" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('admin/audit-trail*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} transition">
                <i class="bx bx-history text-lg"></i>
                <span>Audit Trails</span>
            </a>

            <a href="/demo" class="flex items-center gap-3 px-3 py-2.5 rounded-xl {{ request()->is('demo*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }} transition">
                <i class="bx bx-cube-alt text-lg"></i>
                <span>UI Components Kit</span>
            </a>
        </nav>

        <!-- Sidebar User Footer Profile -->
        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                    {{ substr(auth()->user()?->name ?? 'HR', 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <div class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()?->name ?? 'Payroll Officer' }}</div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono truncate">{{ auth()->user()?->email ?? 'admin@company.com.my' }}</div>
                </div>
            </div>
            <a href="/" class="text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 p-1 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition" title="Exit to Public Site">
                <i class="bx bx-log-out text-lg"></i>
            </a>
        </div>
    </aside>

    <!-- MAIN RIGHT CONTENT COLUMN -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top App Bar Header -->
        <header class="h-16 bg-white/95 dark:bg-slate-900/95 backdrop-blur-md border-b border-slate-200/80 dark:border-slate-800/80 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0 transition-colors sticky top-0 z-30 shadow-xs">
            
            <!-- Left: Mobile Hamburger, Logo & Context Breadcrumbs -->
            <div class="flex items-center gap-3 min-w-0">
                <!-- Hamburger Button for Mobile -->
                <button 
                    type="button" 
                    onclick="toggleMobileSidebar()" 
                    class="lg:hidden p-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 text-slate-700 dark:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-700 transition cursor-pointer shadow-2xs"
                    title="Open Navigation Menu"
                >
                    <i class="bx bx-menu text-xl"></i>
                </button>

                <!-- Breadcrumbs Hierarchy -->
                <nav class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400 min-w-0" aria-label="Breadcrumb">
                    <a href="/admin" class="font-medium text-slate-400 hover:text-indigo-600 dark:hover:text-indigo-400 transition hidden sm:inline truncate">
                        Console
                    </a>
                    <i class="bx bx-chevron-right text-slate-300 dark:text-slate-700 hidden sm:inline text-sm shrink-0"></i>
                    <span class="font-bold text-slate-900 dark:text-white truncate max-w-[140px] sm:max-w-[240px] md:max-w-none">
                        {{ $headerTitle ?? 'System Governance' }}
                    </span>
                </nav>
            </div>

            <!-- Right: Interactive Controls & User Profile -->
            <div class="flex items-center gap-2 sm:gap-3 shrink-0">
                
                <!-- Theme Toggle Button -->
                <button 
                    type="button" 
                    onclick="toggleDarkMode()" 
                    class="p-2 sm:px-2.5 sm:py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-amber-400 hover:bg-slate-100 dark:hover:bg-slate-700 transition cursor-pointer flex items-center gap-1.5 shadow-2xs"
                    title="Toggle Theme"
                >
                    <i class="bx bx-moon dark:hidden text-base"></i>
                    <i class="bx bx-sun hidden dark:inline text-base text-amber-400"></i>
                    <span class="text-[11px] font-semibold text-slate-600 dark:text-slate-300 hidden md:inline">
                        <span class="dark:hidden">Light</span>
                        <span class="hidden dark:inline">Dark</span>
                    </span>
                </button>

                <!-- Notifications Flyout Trigger & Menu -->
                <div class="relative" id="notifications-menu-container">
                    <button 
                        type="button" 
                        onclick="toggleNotificationsMenu(event)"
                        class="relative p-2 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-800/80 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 transition cursor-pointer shadow-2xs"
                        title="System Notifications"
                    >
                        <i class="bx bx-bell text-lg"></i>
                        <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900 animate-pulse"></span>
                    </button>

                    <!-- Notifications Flyout -->
                    <div id="notifications-menu" class="hidden absolute right-0 mt-2.5 w-80 sm:w-88 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl z-50 overflow-hidden transform opacity-0 scale-95 transition-all duration-200">
                        <div class="p-3.5 border-b border-slate-100 dark:border-slate-800 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/50">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-900 dark:text-white">Notifications</span>
                                <span class="px-1.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400">2 New</span>
                            </div>
                            <span class="text-[10px] text-slate-400 cursor-pointer hover:underline">Mark all read</span>
                        </div>
                        <div class="divide-y divide-slate-100 dark:divide-slate-800/60 max-h-72 overflow-y-auto text-xs">
                            <div class="p-3.5 flex items-start gap-3 hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 flex items-center justify-center font-bold text-sm shrink-0">
                                    <i class="bx bx-check-shield"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-800 dark:text-white leading-tight">Statutory Rules Active</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">2026 EPF & SOCSO SKBBK tables loaded.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 block font-mono">10 mins ago</span>
                                </div>
                            </div>
                            <div class="p-3.5 flex items-start gap-3 hover:bg-slate-50/60 dark:hover:bg-slate-800/40 transition cursor-pointer">
                                <div class="w-8 h-8 rounded-xl bg-indigo-50 dark:bg-indigo-950/60 text-indigo-600 flex items-center justify-center font-bold text-sm shrink-0">
                                    <i class="bx bx-calendar-check"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-slate-800 dark:text-white leading-tight">August 2026 Payroll</p>
                                    <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-0.5">Batch ready for review and disbursement.</p>
                                    <span class="text-[10px] text-slate-400 mt-1 block font-mono">1 hr ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statutory Badge (Tablet & Desktop) -->
                <div class="hidden lg:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 dark:bg-emerald-950/50 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800/60 shadow-2xs text-xs font-semibold">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Statutory 2026</span>
                </div>

                <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block mx-0.5"></div>

                <!-- User Profile Interactive Dropdown -->
                <div class="relative" id="user-menu-container">
                    <button 
                        type="button" 
                        onclick="toggleUserMenu(event)"
                        class="flex items-center gap-2.5 p-1 sm:px-2.5 sm:py-1.5 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50/80 dark:bg-slate-800/60 hover:bg-slate-100 dark:hover:bg-slate-800 transition cursor-pointer shadow-2xs group select-none"
                    >
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-700 text-white font-bold flex items-center justify-center text-xs shadow-xs shrink-0 group-hover:scale-105 transition-transform">
                            {{ substr(auth()->user()?->name ?? 'PA', 0, 2) }}
                        </div>
                        <div class="hidden sm:block text-left">
                            <div class="text-xs font-bold text-slate-900 dark:text-white leading-tight truncate max-w-[110px]">
                                {{ auth()->user()?->name ?? 'Payroll Officer' }}
                            </div>
                            <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono leading-tight">
                                ADM-001
                            </div>
                        </div>
                        <i class="bx bx-chevron-down text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-200 transition-transform duration-200 text-base" id="user-menu-chevron"></i>
                    </button>

                    <!-- Dropdown Flyout Menu -->
                    <div id="user-menu" class="hidden absolute right-0 mt-2.5 w-64 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-xl z-50 overflow-hidden transform opacity-0 scale-95 transition-all duration-200">
                        
                        <!-- Header with User info -->
                        <div class="p-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 to-indigo-800 text-white font-bold flex items-center justify-center text-sm shadow-xs shrink-0">
                                {{ substr(auth()->user()?->name ?? 'PA', 0, 2) }}
                            </div>
                            <div class="overflow-hidden">
                                <div class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ auth()->user()?->name ?? 'Payroll Officer' }}</div>
                                <div class="text-[11px] text-slate-400 dark:text-slate-500 font-mono truncate">{{ auth()->user()?->email ?? 'admin@company.com.my' }}</div>
                                <span class="inline-block px-1.5 py-0.2 rounded text-[9px] font-bold bg-indigo-100 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 mt-1 uppercase">Administrator</span>
                            </div>
                        </div>

                        <!-- Menu Actions -->
                        <div class="p-2 space-y-1 text-xs font-medium">
                            <a href="/admin/parameters" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                <i class="bx bx-slider text-base text-slate-400"></i>
                                <span>System Parameters</span>
                            </a>
                            <a href="/admin/audit-trail" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                <i class="bx bx-history text-base text-slate-400"></i>
                                <span>Audit Trails</span>
                            </a>
                            <a href="/demo" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-700 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                <i class="bx bx-cube-alt text-base text-slate-400"></i>
                                <span>UI Components Kit</span>
                            </a>
                        </div>

                        <!-- Sign Out -->
                        <div class="p-2 border-t border-slate-100 dark:border-slate-800">
                            <a href="/" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/40 font-semibold transition">
                                <i class="bx bx-log-out text-base"></i>
                                <span>Sign Out / Public Exit</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- Scrollable Dashboard Page Body -->
        <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 space-y-6">
            
            <!-- Page Header Title Card (Shown if not explicitly hidden) -->
            @if(!($hideHeader ?? false) && (isset($headerTitle) || isset($headerActions)))
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
                    <div>
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $headerTitle ?? '' }}</h1>
                        @if(isset($headerSubtitle))
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $headerSubtitle }}</p>
                        @endif
                    </div>
                    @if(isset($headerActions))
                        <div class="flex items-center gap-2.5 flex-wrap">
                            {{ $headerActions }}
                        </div>
                    @endif
                </div>
            @endif

            <!-- Injected View Content -->
            {{ $slot }}

        </main>
    </div>

    <!-- Global Scripts -->
    <script>
        function toggleDarkMode() {
            if (document.documentElement.classList.contains('dark')) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        function toggleUserMenu(event) {
            event.stopPropagation();
            const menu = document.getElementById('user-menu');
            const chevron = document.getElementById('user-menu-chevron');
            const notifMenu = document.getElementById('notifications-menu');

            if (notifMenu && !notifMenu.classList.contains('hidden')) {
                notifMenu.classList.add('hidden', 'opacity-0', 'scale-95');
                notifMenu.classList.remove('opacity-100', 'scale-100');
            }

            if (menu.classList.contains('hidden')) {
                menu.classList.remove('hidden');
                setTimeout(() => {
                    menu.classList.remove('opacity-0', 'scale-95');
                    menu.classList.add('opacity-100', 'scale-100');
                }, 10);
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                menu.classList.add('opacity-0', 'scale-95');
                menu.classList.remove('opacity-100', 'scale-100');
                setTimeout(() => menu.classList.add('hidden'), 150);
                if (chevron) chevron.classList.remove('rotate-180');
            }
        }

        function toggleNotificationsMenu(event) {
            event.stopPropagation();
            const notifMenu = document.getElementById('notifications-menu');
            const userMenu = document.getElementById('user-menu');
            const chevron = document.getElementById('user-menu-chevron');

            if (userMenu && !userMenu.classList.contains('hidden')) {
                userMenu.classList.add('opacity-0', 'scale-95');
                userMenu.classList.remove('opacity-100', 'scale-100');
                setTimeout(() => userMenu.classList.add('hidden'), 150);
                if (chevron) chevron.classList.remove('rotate-180');
            }

            if (notifMenu.classList.contains('hidden')) {
                notifMenu.classList.remove('hidden');
                setTimeout(() => {
                    notifMenu.classList.remove('opacity-0', 'scale-95');
                    notifMenu.classList.add('opacity-100', 'scale-100');
                }, 10);
            } else {
                notifMenu.classList.add('opacity-0', 'scale-95');
                notifMenu.classList.remove('opacity-100', 'scale-100');
                setTimeout(() => notifMenu.classList.add('hidden'), 150);
            }
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            const userContainer = document.getElementById('user-menu-container');
            const notifContainer = document.getElementById('notifications-menu-container');
            const userMenu = document.getElementById('user-menu');
            const notifMenu = document.getElementById('notifications-menu');
            const chevron = document.getElementById('user-menu-chevron');

            if (userContainer && !userContainer.contains(e.target) && userMenu && !userMenu.classList.contains('hidden')) {
                userMenu.classList.add('opacity-0', 'scale-95');
                userMenu.classList.remove('opacity-100', 'scale-100');
                setTimeout(() => userMenu.classList.add('hidden'), 150);
                if (chevron) chevron.classList.remove('rotate-180');
            }

            if (notifContainer && !notifContainer.contains(e.target) && notifMenu && !notifMenu.classList.contains('hidden')) {
                notifMenu.classList.add('opacity-0', 'scale-95');
                notifMenu.classList.remove('opacity-100', 'scale-100');
                setTimeout(() => notifMenu.classList.add('hidden'), 150);
            }
        });
    </script>
    {{ $scripts ?? '' }}
</body>
</html>
