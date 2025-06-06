<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        // If the email has been changed, we need to nullify the email verification.
        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        // Redirect back to the profile edit page with the success message.
        return Redirect::route('profile.edit');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validate that the user has entered their current password to confirm deletion.
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        // Log out the user and delete their account.
        Auth::logout();
        $user->delete();

        // Invalidate the session and regenerate the CSRF token.
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect to the home page.
        return Redirect::to('/');
    }
}
