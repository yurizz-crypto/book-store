<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $user = Auth::user();

        // Check if 2FA is enabled for this user [cite: 49]
        if ($user->two_factor_enabled) {
            // Generate a 6-digit OTP [cite: 50]
            $user->two_factor_code = rand(100000, 999999);
            $user->two_factor_expires_at = now()->addMinutes(10);
            $user->save();

            // Send the email notification using your Gmail SMTP setup [cite: 58, 238]
            $user->notify(new \App\Notifications\TwoFactorCode());

            // Log the user out immediately so they can't browse yet [cite: 122]
            Auth::logout();

            // Store the user's ID in the session to identify them during verification
            $request->session()->put('2fa_user_id', $user->id);

            return redirect()->route('2fa.index');
        }

        $request->session()->regenerate();
        
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
