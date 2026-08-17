<x-layouts.auth title="Set New Password">
    
    <!-- Auth Card Panel -->
    <div class="bg-white dark:bg-slate-900 rounded-3xl border border-slate-200 dark:border-slate-800 shadow-xl shadow-slate-200/50 dark:shadow-none p-6 sm:p-8 space-y-6">
        
        <!-- Header / Branding -->
        <div class="text-center space-y-1.5">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/60 text-emerald-600 dark:text-emerald-400 mb-2">
                <i class="bx bx-lock-open text-2xl"></i>
            </div>
            <h1 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight">Create New Password</h1>
            <p class="text-xs text-slate-500 dark:text-slate-400">
                Please enter a secure password containing at least 8 characters.
            </p>
        </div>

        <!-- Errors Alert -->
        @if ($errors->any())
            <x-alert variant="danger" icon="bx-error-circle" dismissible="true">
                {{ $errors->first() }}
            </x-alert>
        @endif

        <!-- Reset Password Form -->
        <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">

            <!-- Email Address Input -->
            <x-input 
                id="email" 
                name="email" 
                type="email" 
                label="Email Address" 
                placeholder="officer@company.com.my" 
                value="{{ old('email', $email) }}" 
                icon="bx-envelope" 
                required 
                autofocus
            />

            <!-- New Password Input -->
            <x-input 
                id="password" 
                name="password" 
                type="password" 
                label="New Password" 
                placeholder="Minimum 8 characters" 
                icon="bx-lock-alt" 
                required 
            />

            <!-- Confirm Password Input -->
            <x-input 
                id="password_confirmation" 
                name="password_confirmation" 
                type="password" 
                label="Confirm New Password" 
                placeholder="Re-enter password" 
                icon="bx-check-shield" 
                required 
            />

            <!-- Submit Button -->
            <div class="pt-2">
                <x-button type="submit" variant="primary" size="md" icon="bx-save" class="w-full justify-center shadow-lg shadow-indigo-500/25">
                    Update Password &amp; Login
                </x-button>
            </div>
        </form>

        <!-- Return Action -->
        <div class="pt-2 border-t border-slate-100 dark:border-slate-800 text-center">
            <a href="{{ route('login') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-indigo-600 dark:text-slate-400 dark:hover:text-indigo-400 transition">
                <i class="bx bx-arrow-back text-base"></i>
                <span>Back to Sign In</span>
            </a>
        </div>

    </div>

</x-layouts.auth>
