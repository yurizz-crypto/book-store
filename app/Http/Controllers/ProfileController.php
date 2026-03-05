<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Notifications\TwoFactorStatusChanged;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

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

        $user->fill($request->validated());
        
        $user->two_factor_enabled = $request->has('two_factor_enabled');
        $user->save();

        if ($old2fa !== $user->two_factor_enabled) {
            $user->notify(new TwoFactorStatusChanged($user->two_factor_enabled));
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function updateAddress(Request $request)
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

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
