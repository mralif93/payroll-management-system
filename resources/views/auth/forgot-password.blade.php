<x-layouts.auth title="Recover Password">
    
    <!-- Card Container -->
    <div class="bg-white dark:bg-slate-900 rounded-[2.25rem] border border-slate-200/80 dark:border-slate-800 shadow-[0_25px_60px_-15px_rgba(99,102,241,0.18)] p-8 sm:p-10 transition-all text-left">
        
        <!-- Back to login link -->
        <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors mb-6">
            <i class="bx bx-arrow-back text-sm"></i>
            <span>Back to sign in</span>
        </a>

        <!-- Header & Icon -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-amber-500/15 text-amber-600 dark:text-amber-400 border border-amber-300/30 mb-4 shadow-sm">
                <i class="bx bx-key text-3xl"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-slate-900 dark:text-white">Recover Password</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 mt-1.5 font-medium">Enter your registered work email to receive reset instructions</p>
        </div>

        <!-- Session Status / Flash Alert -->
        @if (session('status'))
            <div class="mb-6 px-4 py-3.5 rounded-2xl bg-emerald-50/70 dark:bg-emerald-950/30 border border-emerald-300 dark:border-emerald-800/80 text-emerald-800 dark:text-emerald-300 text-xs flex items-center gap-2.5 animate__animated animate__fadeIn">
                <i class="bx bx-check-circle text-base text-emerald-600 dark:text-emerald-400 shrink-0"></i>
                <span class="font-medium">{{ session('status') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-6 px-4 py-3.5 rounded-2xl bg-rose-50/70 dark:bg-rose-950/30 border border-rose-300 dark:border-rose-800/80 text-rose-800 dark:text-rose-300 text-xs flex items-center gap-2.5 animate__animated animate__shakeX">
                <i class="bx bx-error-circle text-base text-rose-600 dark:text-rose-400 shrink-0"></i>
                <span class="font-medium">{{ $errors->first() }}</span>
            </div>
        @endif

        <!-- Forgot Password Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
            @csrf

            <!-- Email Input -->
            <div>
                <label for="email" class="block text-xs font-bold text-slate-700 dark:text-slate-300 uppercase tracking-wider mb-2">
                    Registered Work Email
                </label>
                <div class="relative rounded-xl shadow-xs">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-lg">
                        <i class="bx bx-envelope"></i>
                    </div>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        value="{{ old('email') }}"
                        autocomplete="email"
                        placeholder="officer@payroll.my"
                        class="block w-full pl-10 pr-4 py-3 text-sm rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/70 dark:bg-slate-800/80 focus:bg-white dark:focus:bg-slate-900 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-500/20 text-slate-900 dark:text-white placeholder:text-slate-400 transition-all outline-none"
                    >
                </div>
                @error('email')
                    <p class="mt-1.5 text-xs text-rose-600 font-medium flex items-center gap-1">
                        <i class="bx bx-error-circle"></i>
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Submit Button -->
            <button
                type="submit"
                class="w-full inline-flex items-center justify-center gap-2 py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-500 shadow-lg shadow-indigo-600/30 border border-indigo-500/30 active:scale-[0.99] transition-all cursor-pointer"
            >
                <i class="bx bx-paper-plane text-lg"></i>
                <span>Send Reset Instructions</span>
            </button>
        </form>

        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 text-center">
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Contact Payroll administrator at
                <a href="mailto:support@payroll.my" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">support@payroll.my</a>
            </p>
        </div>
    </div>

</x-layouts.auth>
