<x-layouts.auth title="Admin Login">
    
    <!-- Auth Card Panel -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none p-6 sm:p-8 space-y-6">
        
        <!-- Header / Branding -->
        <div class="text-center space-y-1.5">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-indigo-50 dark:bg-indigo-950 text-indigo-600 dark:text-indigo-400 mb-2">
                <i class="bx bxs-shield text-2xl"></i>
            </div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Admin Console Login</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">Sign in with your registered administrator email</p>
        </div>

        <!-- Session Status / Flash Alert -->
        @if (session('status'))
            <x-alert variant="success" icon="bx-check-circle" dismissible="true">
                {{ session('status') }}
            </x-alert>
        @endif

        @if ($errors->any())
            <x-alert variant="danger" icon="bx-error-circle" dismissible="true">
                {{ $errors->first() }}
            </x-alert>
        @endif

        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address Input -->
            <x-input 
                id="email" 
                name="email" 
                type="email" 
                label="Work Email Address" 
                placeholder="officer@company.com.my" 
                value="{{ old('email') }}" 
                icon="bx-envelope" 
                required 
                autofocus
            />

            <!-- Password Input -->
            <div class="space-y-1.5">
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-xs font-semibold text-slate-700 dark:text-slate-300">
                        Password
                    </label>
                    <a href="{{ route('password.request') }}" class="text-[11px] font-semibold text-indigo-600 dark:text-indigo-400 hover:underline">
                        Forgot Password?
                    </a>
                </div>
                
                <div class="relative rounded-lg">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400 dark:text-slate-500">
                        <i class="bx bx-lock-alt text-base"></i>
                    </div>
                    <input 
                        id="password" 
                        name="password" 
                        type="password" 
                        placeholder="••••••••" 
                        required 
                        class="w-full rounded-xl border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-slate-900 dark:text-white pl-9 pr-10 py-2.5 text-xs shadow-xs focus:border-indigo-500 dark:focus:border-indigo-400 focus:ring-2 focus:ring-indigo-500/20 transition"
                    >
                    <button 
                        type="button" 
                        onclick="togglePasswordVisibility('password', this)" 
                        class="absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 cursor-pointer"
                        title="Toggle password view"
                    >
                        <i class="bx bx-show text-base"></i>
                    </button>
                </div>
            </div>

            <!-- Remember Me & Policy -->
            <div class="flex items-center justify-between pt-1">
                <label class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember" 
                        class="w-4 h-4 rounded border-slate-300 dark:border-slate-700 text-indigo-600 focus:ring-indigo-500 bg-white dark:bg-slate-800"
                    >
                    <span class="text-xs text-slate-600 dark:text-slate-400 font-medium">Keep me logged in</span>
                </label>

                <span class="text-[10px] text-slate-400 dark:text-slate-500 flex items-center gap-1">
                    <i class="bx bx-lock text-xs"></i> 256-Bit SSL
                </span>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <x-button type="submit" variant="primary" size="md" icon="bx-log-in-circle" class="w-full justify-center shadow-lg shadow-indigo-500/25">
                    Sign In to Admin Portal
                </x-button>
            </div>
        </form>

        <!-- Quick Demo Credentials Hint (Helpful for pairs & testers) -->
        <div class="p-3.5 rounded-2xl bg-slate-50 dark:bg-slate-800/60 border border-slate-200/80 dark:border-slate-700 text-[11px] text-slate-500 dark:text-slate-400 space-y-1">
            <div class="font-bold text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                <i class="bx bx-info-circle text-indigo-500"></i> Default Test Admin Account:
            </div>
            <div class="font-mono text-[10px] text-slate-600 dark:text-slate-400 flex items-center justify-between">
                <span>admin@payroll.my</span>
                <span class="text-slate-400">/ password</span>
            </div>
        </div>

    </div>

    <!-- Toggle Password Visibility Script -->
    <script>
        function togglePasswordVisibility(fieldId, btn) {
            const input = document.getElementById(fieldId);
            const icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bx bx-hide text-base text-indigo-600';
            } else {
                input.type = 'password';
                icon.className = 'bx bx-show text-base';
            }
        }
    </script>
</x-layouts.auth>
