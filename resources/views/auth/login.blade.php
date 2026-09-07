<x-layouts.auth title="Staff Portal Sign In">
    
    <!-- Card Container -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.25rem] border border-slate-200/80 dark:border-slate-800 shadow-[0_25px_60px_-15px_rgba(99,102,241,0.18)] p-8 sm:p-10 transition-all text-center">
        
        <!-- Header Icon & Brand -->
        <div class="flex flex-col items-center mb-6">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-b from-indigo-500 via-indigo-600 to-blue-600 text-white shadow-[0_12px_24px_-4px_rgba(99,102,241,0.4)] mb-4">
                <i class="bx bxs-wallet text-3xl"></i>
            </div>
            <h1 class="text-2xl sm:text-[1.65rem] font-extrabold text-slate-900 dark:text-white tracking-tight">Staff Portal Sign In</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2 font-medium max-w-[280px] leading-relaxed">
                Access your payroll runs, statutory filings, and administrative console
            </p>
        </div>

        <!-- Dismissible Status / Error Alert Banner -->
        @if (session('status'))
            <x-alert variant="success" icon="bx-check-circle" dismissible="true" class="mb-6 text-left">
                {{ session('status') }}
            </x-alert>
        @endif

        @if ($errors->any())
            <x-alert variant="danger" icon="bx-error-circle" dismissible="true" class="mb-6 text-left">
                {{ $errors->first() }}
            </x-alert>
        @endif

        <!-- Info Container Card (Enterprise Identity Protection) -->
        <div class="p-6 rounded-3xl bg-slate-50/80 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-800/80 mb-6 text-center space-y-2">
            <div class="inline-flex items-center justify-center w-10 h-10 rounded-2xl bg-indigo-100/70 dark:bg-indigo-950/80 text-indigo-600 dark:text-indigo-400 mb-1">
                <i class="bx bx-shield-quarter text-2xl"></i>
            </div>
            <h2 class="text-xs font-bold text-slate-900 dark:text-white tracking-tight">
                Enterprise Identity Protection Enforced
            </h2>
            <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed max-w-[280px] mx-auto font-normal">
                Authentication for PayFlow MY is centrally managed by CentraFlow Identity Hub. Click below to sign in with your corporate credentials.
            </p>
        </div>

        <!-- Sign in with CentraFlow SSO Button -->
        <a href="{{ route('sso.login') }}" 
           class="w-full inline-flex items-center justify-center gap-3 py-3.5 px-5 rounded-2xl text-sm font-bold text-white bg-gradient-to-r from-blue-600 via-indigo-600 to-indigo-700 hover:from-blue-500 hover:to-indigo-600 shadow-[0_12px_28px_-6px_rgba(79,70,229,0.5)] active:scale-[0.99] transition-all cursor-pointer">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
            </svg>
            <span>Sign in with CentraFlow SSO</span>
        </a>

        <!-- Status Footer -->
        <div class="pt-6 text-center">
            <span class="text-[11px] text-slate-400 dark:text-slate-500 flex items-center justify-center gap-2 font-mono">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                CentraFlow OAuth 2.0 Server Active (:8004)
            </span>
        </div>

    </div>
</x-layouts.auth>



