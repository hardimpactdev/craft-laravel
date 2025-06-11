<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\ProfileUpdateRequest;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;
use NckRtl\RouteMaker\Delete;
use NckRtl\RouteMaker\Get;
use NckRtl\RouteMaker\Patch;

class ProfileController extends Controller
{
    /**
     * Show the user's profile settings page.
     */
    #[Get(uri: '/settings/profile', name: 'profile.edit', middleware: 'auth')]
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Profile', [
            'mustVerifyEmail' => $request->user() instanceof Authenticatable,
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    #[Patch(uri: '/settings/profile', name: 'profile.update', middleware: 'auth')]
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()?->fill($request->validated());

        if ($request->user()?->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()?->save();

        return to_route('profile.edit');
    }

    /**
     * Delete the user's profile.
     */
    #[Delete(uri: '/settings/profile', name: 'profile.destroy', middleware: 'auth')]
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        if ($user) {
            $user->delete();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
