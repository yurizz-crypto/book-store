<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Notifications\TwoFactorStatusChanged;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Notifications\UserActionNotification;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $old2fa = $user->two_factor_enabled;

        // Fill validated data (first_name, middle_name, last_name, email)
        $user->fill($request->safe()->except('two_factor_enabled'));

        // Handle 2FA toggle separately
        $user->two_factor_enabled = $request->boolean('two_factor_enabled');

        // Check if email changed to reset verification
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        // Notify if 2FA status changed
        if ($old2fa !== $user->two_factor_enabled) {
            $user->notify(new TwoFactorStatusChanged($user->two_factor_enabled));

            $statusString = $user->two_factor_enabled ? 'Enabled' : 'Disabled';
            $user->notify(new UserActionNotification('Security Alert', [
                'alert' => "Two-Factor Authentication was {$statusString}."
            ]));
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's address.
     */
    public function updateAddress(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'street_address' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:100'],
        ]);

        $request->user()->addresses()->updateOrCreate(
            ['is_default' => true],
            $validated
        );

        return back()->with('status', 'address-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        $admins = \App\Models\User::where('role', 'admin')->get();
        \Illuminate\Support\Facades\Notification::send($admins, new \App\Notifications\CriticalSecurityAlert([
            'event' => 'User Account Permanently Deleted',
            'target' => $user->email,
            'ip' => $request->ip()
        ]));

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}