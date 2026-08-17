<x-layouts.auth title="Forgot Password">
    
    <!-- Auth Card Panel -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none p-6 sm:p-8 space-y-6">
        
        <!-- Header / Branding -->
        <div class="text-center space-y-1.5">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/60 text-amber-600 dark:text-amber-400 mb-2">
                <i class="bx bx-key text-2xl"></i>
            </div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Reset Admin Password</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed">
                Enter your work email address and we will send you a password reset link to regain access.
            </p>
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

        <!-- Request Form -->
        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address Input -->
            <x-input 
                id="email" 
                name="email" 
                type="email" 
                label="Registered Work Email Address" 
                placeholder="officer@company.com.my" 
                value="{{ old('email') }}" 
                icon="bx-envelope" 
                required 
                autofocus
                helper="We'll send a secured one-time password reset link to this inbox."
            />

            <!-- Submit Button -->
            <div class="pt-2">
                <x-button type="submit" variant="primary" size="md" icon="bx-paper-plane" class="w-full justify-center shadow-lg shadow-indigo-500/25">
                    Send Password Reset Link
                </x-button>
            </div>
        </form>

        <!-- Return to Login Footer Action -->
        <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition">
                <i class="bx bx-arrow-back text-base"></i>
                <span>Return to Sign In</span>
            </a>
        </div>

    </div>

</x-layouts.auth>
