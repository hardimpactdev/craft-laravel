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
        $user = $request->user();
        $twoFactorEnabled = $user && method_exists($user, 'hasEnabledTwoFactorAuthentication')
            ? $user->hasEnabledTwoFactorAuthentication()
            : filled($user?->two_factor_secret);
        $twoFactorPending = filled($user?->two_factor_secret) && ! $twoFactorEnabled;
        $fortifyFeatures = \Laravel\Fortify\Features::class;
        $canManageTwoFactor = class_exists($fortifyFeatures)
            && $fortifyFeatures::enabled($fortifyFeatures::twoFactorAuthentication());

        return Inertia::render('settings/Security', [
            'twoFactorEnabled' => $twoFactorEnabled,
            'twoFactorPending' => $twoFactorPending,
            'continueTwoFactorSetup' => $twoFactorPending
                && $request->boolean('continueTwoFactorSetup'),
            'canManageTwoFactor' => $canManageTwoFactor,
            'requiresConfirmation' => $canManageTwoFactor
                && $fortifyFeatures::optionEnabled(
                    $fortifyFeatures::twoFactorAuthentication(),
                    'confirm',
                ),
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
