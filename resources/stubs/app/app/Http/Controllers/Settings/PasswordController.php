<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Inertia\Response;
use NckRtl\RouteMaker\Get;
use NckRtl\RouteMaker\Put;

class PasswordController extends Controller
{
    /**
     * Show the user's password settings page.
     */
    #[Get(uri: '/settings/password', name: 'password.edit', middleware: 'auth')]
    public function edit(): Response
    {
        return Inertia::render('settings/Password');
    }

    /**
     * Update the user's password.
     */
    #[Put(uri: '/settings/password', name: 'password.update', middleware: 'auth')]
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
