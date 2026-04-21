<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\UpdateAddressRequest; // You need to generate this
use App\Notifications\TwoFactorStatusChanged;
use App\Notifications\UserActionNotification;
use App\Notifications\CriticalSecurityAlert;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profile.edit', ['user' => $request->user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $old2fa = $user->two_factor_enabled;

        $user->fill($request->safe()->except('two_factor_enabled'));
        $user->two_factor_enabled = $request->boolean('two_factor_enabled');

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        if ($old2fa !== $user->two_factor_enabled) {
            $user->notify(new TwoFactorStatusChanged($user->two_factor_enabled));
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateAddress(UpdateAddressRequest $request): RedirectResponse
    {
        $request->user()->addresses()->updateOrCreate(
            ['is_default' => true],
            $request->validated()
        );

        return back()->with('status', 'address-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Notification::send(
            User::where('role', 'admin')->get(), 
            new CriticalSecurityAlert([
                'event'  => 'User Account Permanently Deleted',
                'target' => $user->email,
                'ip'     => $request->ip()
            ])
        );

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}