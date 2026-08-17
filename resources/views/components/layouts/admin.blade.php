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

    <!-- MOBILE SIDEBAR OVERLAY & DRAWER -->
    <div id="mobile-sidebar-backdrop" onclick="toggleMobileSidebar()" class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 lg:hidden transition-opacity"></div>

    <!-- SIDEBAR NAVIGATION (Desktop & Mobile Drawer) -->
    <aside id="admin-sidebar" class="fixed inset-y-0 left-0 -translate-x-full lg:translate-x-0 lg:static w-64 bg-white dark:bg-slate-900 border-r border-slate-200 dark:border-slate-800 flex flex-col shrink-0 z-50 transition-transform duration-300 ease-in-out">
        
        <!-- Sidebar Brand Logo -->
        <div class="h-16 flex items-center justify-between px-6 border-b border-slate-100 dark:border-slate-800">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-500/20">
                    <i class="bx bxs-wallet text-lg"></i>
                </div>
                <div>
                    <span class="text-base font-extrabold text-slate-900 dark:text-white tracking-tight">PayFlow<span class="text-indigo-600 dark:text-indigo-400">MY</span></span>
                    <span class="block text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Admin Console</span>
                </div>
            </a>
            <!-- Mobile Close Button -->
            <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-1.5 rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer">
                <i class="bx bx-x text-xl"></i>
            </button>
        </div>

        <!-- Sidebar Navigation Menu -->
        <nav class="flex-1 px-3.5 py-5 space-y-6 overflow-y-auto text-xs">
            
            <!-- Group 1: Core Navigation -->
            <div class="space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bxs-dashboard text-lg"></i>
                    <span>Dashboard</span>
                </a>
            </div>

            <!-- Group 2: User Access & Identity -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
                    User &amp; Access Control
                </div>
                <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bx-user text-lg"></i>
                    <span>Users</span>
                </a>
                <a href="{{ route('admin.roles.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.roles.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bx-shield-quarter text-lg"></i>
                    <span>Access Roles</span>
                </a>
            </div>

            <!-- Group 3: Payroll Operations -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
                    Payroll &amp; HR Management
                </div>
                <a href="{{ route('admin.payroll.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.payroll.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bx-calendar-check text-lg"></i>
                    <span>Payroll Runs</span>
                </a>
                <a href="{{ route('admin.employees.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.employees.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bx-group text-lg"></i>
                    <span>Employee Registry</span>
                </a>
            </div>

            <!-- Group 4: Banking & Exporters -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
                    Banking &amp; Exporters
                </div>
                <a href="{{ route('admin.banking.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.banking.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bxs-bank text-lg"></i>
                    <span>Bank Autopay (M2E/CIMB)</span>
                </a>
                <a href="{{ route('admin.banking.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white">
                    <i class="bx bx-export text-lg"></i>
                    <span>Statutory Exporters</span>
                    <span class="ml-auto px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">CP39/EIS</span>
                </a>
                <a href="{{ route('admin.tax-ea.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.tax-ea.*') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bxs-file-pdf text-lg"></i>
                    <span>Tax Form EA Compiler</span>
                </a>
            </div>

            <!-- Group 5: Configuration & Audit -->
            <div class="space-y-1">
                <div class="px-3 pb-1 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">
                    System Governance
                </div>
                <a href="{{ route('admin.parameters') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.parameters') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bx-slider text-lg"></i>
                    <span>Statutory Parameters</span>
                </a>
                <a href="{{ route('admin.audit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.audit') ? 'bg-indigo-600 text-white shadow-md shadow-indigo-500/20 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                    <i class="bx bx-shield-quarter text-lg"></i>
                    <span>Audit Trails</span>
                </a>
                <a href="/demo" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white">
                    <i class="bx bx-cube-alt text-lg"></i>
                    <span>UI Components Kit</span>
                </a>
            </div>
        </nav>

        <!-- Sidebar Version Footer -->
        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between text-[11px] text-slate-400">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="font-semibold text-slate-600 dark:text-slate-300">PayFlow Engine</span>
            </div>
            <span class="font-mono text-[10px]">v2.4 (2026)</span>
        </div>
    </aside>

    <!-- MAIN RIGHT CONTENT COLUMN -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top App Bar Header -->
        <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 sm:px-6 shrink-0 transition-colors z-20">
            
            <!-- Mobile Hamburger & Breadcrumbs -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer">
                    <i class="bx bx-menu text-lg"></i>
                </button>

                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <span class="font-medium text-slate-400 hidden sm:inline">PayFlow Console</span>
                    <span class="hidden sm:inline text-slate-300 dark:text-slate-600">/</span>
                    <span class="font-bold text-slate-800 dark:text-white truncate">{{ $title ?? 'Admin Console' }}</span>
                </div>
            </div>

            <!-- Top Header Right Actions with Standardized UI Kit Controls -->
            <div class="flex items-center gap-3">
                <!-- Theme Toggle -->
                <div class="flex items-center">
                    <x-theme-toggle id="admin-theme-toggle-btn" />
                </div>

                <!-- Notifications Button -->
                <button type="button" class="relative w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-slate-800 transition cursor-pointer shadow-xs">
                    <i class="bx bx-bell text-base"></i>
                    <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-rose-500 ring-2 ring-white dark:ring-slate-900"></span>
                </button>

                <!-- Live Compliance Indicator Badge -->
                <div class="hidden md:flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200/80 dark:border-emerald-800/80 shadow-xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Statutory 2026</span>
                </div>

                <!-- Divider -->
                <div class="h-5 w-px bg-slate-200 dark:bg-slate-800 hidden sm:block"></div>

                <!-- Modern Standardized User Profile Dropdown -->
                <div class="relative" id="user-dropdown-container">
                    <button 
                        type="button" 
                        id="user-dropdown-toggle"
                        onclick="toggleUserDropdown()"
                        class="flex items-center gap-2.5 pl-1.5 pr-3 py-1 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 hover:border-slate-300 dark:hover:border-slate-700 transition shadow-xs cursor-pointer group"
                    >
                        <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-xs">
                            {{ substr(Auth::user()->name ?? 'HR', 0, 2) }}
                        </div>
                        <div class="text-left hidden sm:block">
                            <span class="text-xs font-bold text-slate-800 dark:text-white block leading-tight group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition">{{ Auth::user()->name ?? 'Payroll Officer' }}</span>
                            <span class="text-[10px] text-slate-400 font-mono">{{ Auth::user()->staff_id ?? 'ADM-001' }}</span>
                        </div>
                        <i class="bx bx-chevron-down text-slate-400 text-sm hidden sm:block group-hover:text-slate-600 dark:group-hover:text-slate-200 transition"></i>
                    </button>

                    <!-- Dropdown Content Panel -->
                    <div 
                        id="user-dropdown-menu"
                        class="hidden absolute right-0 mt-2 w-72 bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-slate-800 shadow-xl overflow-hidden z-50 animate-in fade-in zoom-in-95 duration-150"
                    >
                        <!-- Profile Card Header -->
                        <div class="p-4 bg-slate-50/80 dark:bg-slate-850 border-b border-slate-100 dark:border-slate-800">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-indigo-600 text-white flex items-center justify-center text-sm font-black shadow-sm shrink-0">
                                    {{ substr(Auth::user()->name ?? 'HR', 0, 2) }}
                                </div>
                                <div class="overflow-hidden">
                                    <h4 class="text-xs font-extrabold text-slate-900 dark:text-white truncate">{{ Auth::user()->name ?? 'Payroll Officer' }}</h4>
                                    <p class="text-[11px] text-slate-400 dark:text-slate-500 font-mono truncate">{{ Auth::user()->email ?? 'admin@payroll.my' }}</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-between mt-3 pt-2.5 border-t border-slate-200/60 dark:border-slate-800">
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-indigo-50 dark:bg-indigo-950 text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-indigo-800">
                                    {{ Auth::user()->roles->first()->display_name ?? 'Payroll Officer' }}
                                </span>
                                <span class="text-[10px] font-mono text-slate-400">ID: {{ Auth::user()->staff_id ?? 'ADM-001' }}</span>
                            </div>
                        </div>

                        <!-- Menu Quick Actions -->
                        <div class="p-2 space-y-0.5 text-xs text-slate-700 dark:text-slate-200 font-medium">
                            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                <i class="bx bx-user-pin text-base text-slate-400"></i>
                                <span>User &amp; Role Management</span>
                            </a>
                            <a href="{{ route('admin.employees.index') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                <i class="bx bx-user text-base text-slate-400"></i>
                                <span>My Organization</span>
                            </a>
                            <a href="{{ route('admin.parameters') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                <i class="bx bx-slider text-base text-slate-400"></i>
                                <span>Statutory Parameters</span>
                            </a>
                            <a href="{{ route('admin.audit') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                <i class="bx bx-shield-quarter text-base text-slate-400"></i>
                                <span>Audit Trails &amp; Logs</span>
                            </a>
                            <a href="/demo" class="flex items-center gap-2.5 px-3 py-2 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-indigo-600 dark:hover:text-indigo-400 transition">
                                <i class="bx bx-cube-alt text-base text-slate-400"></i>
                                <span>UI Components Kit</span>
                            </a>
                        </div>

                        <!-- Logout Action -->
                        <div class="p-2 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50">
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-between px-3 py-2 rounded-xl text-xs font-bold text-rose-600 dark:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-950/50 transition cursor-pointer">
                                    <span class="flex items-center gap-2">
                                        <i class="bx bx-log-out text-base"></i>
                                        <span>Sign Out Session</span>
                                    </span>
                                    <i class="bx bx-chevron-right text-base text-rose-400"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </header>

        <!-- Scrollable Dashboard Page Body -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8 space-y-6">
            
            @if(isset($headerActions))
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
                    @if(isset($headerTitle))
                        <div>
                            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $headerTitle }}</h1>
                            @if(isset($headerSubtitle))
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $headerSubtitle }}</p>
                            @endif
                        </div>
                    @endif
                    <div class="flex items-center gap-2.5 ml-auto">
                        {{ $headerActions }}
                    </div>
                </div>
            @endif

            <!-- Injected View Content -->
            {{ $slot }}

        </main>
    </div>

    <!-- Global Theme & Mobile Navigation Script -->
    <script>
        function toggleDarkMode() {
            const isDark = document.documentElement.classList.contains('dark');
            if (isDark) {
                document.documentElement.classList.remove('dark');
                localStorage.theme = 'light';
            } else {
                document.documentElement.classList.add('dark');
                localStorage.theme = 'dark';
            }
        }

        function toggleMobileSidebar() {
            const sidebar = document.getElementById('admin-sidebar');
            const backdrop = document.getElementById('mobile-sidebar-backdrop');
            
            if (sidebar.classList.contains('-translate-x-full')) {
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            } else {
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }
        }

        function toggleUserDropdown() {
            const menu = document.getElementById('user-dropdown-menu');
            menu.classList.toggle('hidden');
        }

        function openModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }
        }

        function closeModal(id) {
            const modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const container = document.getElementById('user-dropdown-container');
            const menu = document.getElementById('user-dropdown-menu');
            if (container && menu && !container.contains(event.target)) {
                menu.classList.add('hidden');
            }
        });
    </script>
    {{ $scripts ?? '' }}
</body>
</html>
