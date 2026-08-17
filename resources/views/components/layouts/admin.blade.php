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
            <a href="/" class="flex items-center gap-3">
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
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto text-xs font-semibold">
            
            <div class="px-3 pb-2 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">Main Operations</div>

            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="bx bxs-dashboard text-lg"></i>
                <span>Payroll Dashboard</span>
            </a>

            <a href="{{ route('admin.payroll.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.payroll.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="bx bx-calendar-check text-lg"></i>
                <span>Monthly Payroll Runs</span>
            </a>

            <a href="{{ route('admin.employees.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.employees.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="bx bx-group text-lg"></i>
                <span>Employee Registry</span>
            </a>

            <div class="pt-5 px-3 pb-2 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">Statutory & Banking</div>

            <a href="{{ route('admin.banking.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.banking.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="bx bx-export text-lg"></i>
                <span>Statutory Exporters</span>
                <span class="ml-auto px-1.5 py-0.5 rounded text-[10px] font-bold bg-emerald-50 dark:bg-emerald-950 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-800">CP39/EIS</span>
            </a>

            <a href="{{ route('admin.banking.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white">
                <i class="bx bxs-bank text-lg"></i>
                <span>Bank Autopay (M2E/CIMB)</span>
            </a>

            <a href="{{ route('admin.tax-ea.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.tax-ea.*') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="bx bxs-file-pdf text-lg"></i>
                <span>Tax Form EA Compiler</span>
            </a>

            <div class="pt-5 px-3 pb-2 text-[10px] uppercase tracking-wider text-slate-400 dark:text-slate-500 font-bold">System Configuration</div>

            <a href="{{ route('admin.parameters') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.parameters') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="bx bx-slider text-lg"></i>
                <span>Statutory Parameters</span>
            </a>

            <a href="{{ route('admin.audit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition {{ request()->routeIs('admin.audit') ? 'bg-indigo-50 dark:bg-indigo-950/60 text-indigo-700 dark:text-indigo-300 font-bold' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white' }}">
                <i class="bx bx-shield-quarter text-lg"></i>
                <span>Audit Trails</span>
            </a>

            <a href="/demo" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white">
                <i class="bx bx-cube-alt text-lg"></i>
                <span>UI Components Kit</span>
            </a>
        </nav>

        <!-- Sidebar User Footer Profile -->
        <div class="p-4 border-t border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="w-8 h-8 rounded-full bg-indigo-600 text-white flex items-center justify-center text-xs font-bold shrink-0">
                    {{ substr(Auth::user()->name ?? 'HR', 0, 2) }}
                </div>
                <div class="overflow-hidden">
                    <div class="flex items-center gap-1.5">
                        <span class="text-xs font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name ?? 'Payroll Officer' }}</span>
                        @if(Auth::user()?->staff_id)
                            <span class="px-1 py-0.2 rounded text-[9px] font-mono font-bold bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400">{{ Auth::user()->staff_id }}</span>
                        @endif
                    </div>
                    <div class="text-[10px] text-slate-400 dark:text-slate-500 font-mono truncate">{{ Auth::user()->email ?? 'admin@payroll.my' }}</div>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                <button type="submit" class="text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition cursor-pointer" title="Sign Out">
                    <i class="bx bx-log-out text-lg"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- MAIN RIGHT CONTENT COLUMN -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
        
        <!-- Top App Bar Header -->
        <header class="h-16 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 flex items-center justify-between px-4 sm:px-6 shrink-0 transition-colors">
            
            <!-- Mobile Hamburger & Breadcrumbs -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer">
                    <i class="bx bx-menu text-lg"></i>
                </button>

                <div class="flex items-center gap-2 text-xs text-slate-500 dark:text-slate-400">
                    <span class="font-medium text-slate-400 hidden sm:inline">Console</span>
                    <span class="hidden sm:inline">/</span>
                    <span class="font-bold text-slate-800 dark:text-white truncate">{{ $headerTitle }}</span>
                </div>
            </div>

            <!-- Top Header Right Actions -->
            <div class="flex items-center gap-2 sm:gap-3">
                <!-- Theme Toggle Component -->
                <x-theme-toggle id="admin-theme-toggle-btn" />

                <!-- Notifications -->
                <button type="button" class="relative p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-600 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition cursor-pointer">
                    <i class="bx bx-bell text-lg"></i>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-rose-500"></span>
                </button>

                <!-- Live Indicator -->
                <span class="hidden sm:inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 dark:bg-emerald-950/60 text-emerald-700 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Statutory Active (2026)
                </span>
            </div>
        </header>

        <!-- Scrollable Dashboard Page Body -->
        <main class="flex-1 overflow-y-auto p-6 sm:p-8 space-y-6">
            
            <!-- Page Header Title Card -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 pb-2">
                <div>
                    <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 dark:text-white tracking-tight">{{ $headerTitle }}</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">{{ $headerSubtitle }}</p>
                </div>
                @if(isset($headerActions))
                    <div class="flex items-center gap-2.5">
                        {{ $headerActions }}
                    </div>
                @endif
            </div>

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
    </script>
    {{ $scripts ?? '' }}
</body>
</html>
