<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CentraFlowSsoClientController extends Controller
{
    /**
     * Redirect the user to CentraFlow SSO authorization dialog.
     */
    public function redirect(Request $request)
    {
        // 1. Generate CSRF state token
        $state = Str::random(40);
        $request->session()->put('oauth_state', $state);

        // 2. Build OAuth authorization query
        $query = http_build_query([
            'client_id'     => config('services.centraflow.client_id', env('CENTRAFLOW_CLIENT_ID')),
            'redirect_uri'  => config('services.centraflow.redirect_uri', env('CENTRAFLOW_REDIRECT_URI')),
            'response_type' => 'code',
            'scope'         => config('services.centraflow.scopes', env('CENTRAFLOW_SCOPES', '')),
            'state'         => $state,
        ]);

        $authUrl = rtrim(config('services.centraflow.host', env('CENTRAFLOW_HOST', 'http://localhost:8004')), '/') . '/oauth/authorize?' . $query;

        return redirect()->away($authUrl);
    }

    /**
     * Handle the OAuth callback from CentraFlow.
     */
    public function callback(Request $request)
    {
        // 1. Verify CSRF state token
        $savedState = $request->session()->pull('oauth_state');
        if (empty($savedState) || $savedState !== $request->query('state')) {
            return redirect()->route('login')->withErrors(['oauth' => 'Invalid or expired OAuth state token.']);
        }

        if ($request->has('error')) {
            return redirect()->route('login')->withErrors(['oauth' => 'CentraFlow authorization was denied.']);
        }

        $host = rtrim(config('services.centraflow.host', env('CENTRAFLOW_HOST', 'http://localhost:8004')), '/');
        $clientId = config('services.centraflow.client_id', env('CENTRAFLOW_CLIENT_ID'));
        $clientSecret = config('services.centraflow.client_secret', env('CENTRAFLOW_CLIENT_SECRET'));
        $redirectUri = config('services.centraflow.redirect_uri', env('CENTRAFLOW_REDIRECT_URI'));

        // 2. Exchange authorization code for access token
        try {
            $tokenResponse = Http::asForm()->post($host . '/oauth/token', [
                'grant_type'    => 'authorization_code',
                'client_id'     => $clientId,
                'client_secret' => $clientSecret,
                'redirect_uri'  => $redirectUri,
                'code'          => $request->query('code'),
            ]);
        } catch (\Exception $e) {
            Log::error('CentraFlow SSO Token Exchange Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['oauth' => 'Could not connect to CentraFlow SSO server: ' . $e->getMessage()]);
        }

        if (! $tokenResponse->successful()) {
            return redirect()->route('login')->withErrors(['oauth' => 'Could not exchange code with CentraFlow: ' . $tokenResponse->body()]);
        }

        $tokenPayload = $tokenResponse->json();
        $accessToken = $tokenPayload['access_token'] ?? null;

        if (! $accessToken) {
            return redirect()->route('login')->withErrors(['oauth' => 'No access token received from CentraFlow.']);
        }

        // 3. Retrieve user profile from CentraFlow master directory
        try {
            $userResponse = Http::withToken($accessToken)
                ->acceptJson()
                ->get($host . '/api/v1/me');
        } catch (\Exception $e) {
            Log::error('CentraFlow SSO User Profile Error: ' . $e->getMessage());
            return redirect()->route('login')->withErrors(['oauth' => 'Failed to reach CentraFlow profile endpoint: ' . $e->getMessage()]);
        }

        if (! $userResponse->successful()) {
            return redirect()->route('login')->withErrors(['oauth' => 'Failed retrieving profile from CentraFlow.']);
        }

        $profile = $userResponse->json('data') ?? $userResponse->json();

        if (empty($profile['email'])) {
            return redirect()->route('login')->withErrors(['oauth' => 'CentraFlow profile does not contain an email address.']);
        }

        // 4. Find or provision user in local sub-system database
        $user = User::updateOrCreate(
            ['email' => $profile['email']],
            [
                'name'     => $profile['name'] ?? 'CentraFlow User',
                'status'   => 'active',
                'password' => bcrypt(Str::random(32)),
            ]
        );

        // Assign default payroll role if user has no role
        if ($user->roles()->count() === 0) {
            $defaultRole = Role::where('name', 'payroll_officer')->first() ?? Role::first();
            if ($defaultRole) {
                $user->roles()->sync([$defaultRole->id]);
            }
        }

        // Update login activity metadata
        $user->update([
            'last_login_at' => now(),
            'last_login_ip' => $request->ip(),
        ]);

        // 5. Authenticate user into local session
        Auth::login($user, true);

        // Store token in session if sub-system needs to call CentraFlow APIs
        $request->session()->put('centraflow_access_token', $accessToken);

        return redirect()->intended(route('admin.dashboard'));
    }
}
