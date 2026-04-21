<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Notifications\TwoFactorCode;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $user = $request->authenticate();

        if ($user->two_factor_enabled) {
            
            $user->update([
                'two_factor_code' => rand(100000, 999999),
                'two_factor_expires_at' => now()->addMinutes(10)
            ]);

            $user->notify(new TwoFactorCode());

            $request->session()->put('2fa_user_id', $user->id);

            return redirect()->route('2fa.index');
        }

        Auth::login($user, $request->boolean('remember'));
        
        $request->session()->regenerate();
        
        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}