<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorController extends Controller
{
    public function index()
    {
        if (!session()->has('2fa_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.two-factor');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|integer',
        ]);

        $user = User::findOrFail(session()->get('2fa_user_id'));

        if ($request->code == $user->two_factor_code && now()->lt($user->two_factor_expires_at)) {
            $user->resetTwoFactorCode();
            
            Auth::login($user);
            session()->forget('2fa_user_id');

            return redirect()->route('dashboard');
        }

        return back()->withErrors(['code' => 'The provided code is invalid or has expired.']);
    }
}