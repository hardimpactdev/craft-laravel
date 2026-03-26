<?php

declare(strict_types=1);

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use HardImpact\Waymaker\Get;
use HardImpact\Waymaker\Put;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;

class SecurityController extends Controller
{
    /**
     * Show the user's security settings page.
     */
    #[Get(uri: '/settings/security', name: 'security.edit', middleware: ['auth', 'verified'])]
    public function edit(Request $request): Response
    {
        return Inertia::render('settings/Security', [
            'twoFactorEnabled' => ! is_null($request->user()->two_factor_secret),
            'canManageTwoFactor' => true,
        ]);
    }

    /**
     * Update the user's password.
     */
    #[Put(uri: '/settings/password', name: 'security.password', middleware: 'auth')]
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()?->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back();
    }
}
