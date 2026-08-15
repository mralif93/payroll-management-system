@props([
    'title' => 'PayFlow MY - Malaysian Payroll Management System',
    'description' => 'Automated and 100% compliant Malaysian payroll management system with built-in statutory engines and bank autopay exports.',
])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }}</title>
    <meta name="description" content="{{ $description }}">

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
<body class="min-h-screen flex flex-col bg-slate-50 dark:bg-slate-950 text-slate-800 dark:text-slate-100 antialiased selection:bg-indigo-500 selection:text-white transition-colors duration-300" style="font-family: 'Plus Jakarta Sans', sans-serif;">

    <!-- TOP NAVIGATION HEADER -->
    <header class="sticky top-0 z-40 w-full border-b border-slate-200/80 dark:border-slate-800 bg-white/85 dark:bg-slate-900/85 backdrop-blur-md shrink-0 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 sm:h-20">
                
                <!-- Brand Logo -->
                <a href="/" class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-600 via-indigo-700 to-blue-800 flex items-center justify-center text-white font-bold shadow-md shadow-indigo-500/20">
                        <i class="bx bxs-wallet text-xl"></i>
                    </div>
                    <div>
                        <span class="text-lg font-extrabold text-slate-900 dark:text-white tracking-tight">PayFlow<span class="text-indigo-600 dark:text-indigo-400">MY</span></span>
                        <span class="hidden sm:block text-[10px] font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Malaysian Payroll Engine</span>
                    </div>
                </a>

                <!-- Nav Menu Links -->
                <nav class="hidden md:flex items-center gap-8 text-xs font-semibold text-slate-600 dark:text-slate-300">
                    <a href="/#features" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Statutory Modules</a>
                    <a href="/#compliance" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Compliance Map</a>
                    <a href="/#calculator-preview" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Simulator</a>
                    <a href="/demo" class="text-indigo-600 dark:text-indigo-400 hover:underline flex items-center gap-1">
                        <span>UI Kit Demo</span>
                        <span class="px-1.5 py-0.5 rounded text-[10px] bg-indigo-50 dark:bg-indigo-950/60 border border-indigo-200 dark:border-indigo-800">12 Parts</span>
                    </a>
                </nav>

                <!-- Actions & Dark Mode Toggle -->
                <div class="flex items-center gap-3">
                    <!-- Theme Toggle Button -->
                    <button 
                        type="button" 
                        onclick="toggleDarkMode()" 
                        class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-amber-400 hover:bg-slate-200 dark:hover:bg-slate-700 transition cursor-pointer"
                        title="Toggle Light / Dark Mode"
                    >
                        <i class="bx bx-moon dark:hidden text-lg"></i>
                        <i class="bx bx-sun hidden dark:inline text-lg"></i>
                    </button>

                    <x-button variant="secondary" size="sm" href="/demo" icon="bx-code-alt">
                        UI Kit
                    </x-button>
                    <x-button variant="dark" size="sm" href="/admin" icon="bx-shield-quarter">
                        Admin Portal
                    </x-button>
                </div>
            </div>
        </div>
    </header>

    <!-- MAIN BODY CONTENT -->
    <main class="flex-1 w-full">
        {{ $slot }}
    </main>

    <!-- GLOBAL FOOTER -->
    <footer class="shrink-0 mt-auto border-t border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 py-8 transition-colors">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500 dark:text-slate-400">
            <div class="flex items-center gap-2">
                <span class="font-bold text-slate-800 dark:text-white">PayFlow MY</span>
                <span>•</span>
                <span>Malaysian Payroll Management System</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="/demo" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">UI Kit Reference</a>
                <a href="/#compliance" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Compliance Map</a>
                <a href="/admin" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Admin Portal</a>
                <a href="https://github.com/mralif93/payroll-management-system" target="_blank" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">GitHub</a>
            </div>
        </div>
    </footer>

    <!-- Global Theme Script -->
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
    </script>
    {{ $scripts ?? '' }}
</body>
</html>
