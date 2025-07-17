<?php

namespace App\Http\Controllers\Auth;

use App\App;
use App\Http\Controllers\Controller;
use HardImpact\Waymaker\Get;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class EmailVerificationPromptController extends Controller
{
    /**
     * Show the email verification prompt page.
     */
    #[Get(uri: '/verify-email', middleware: 'auth')]
    public function __invoke(Request $request): RedirectResponse|Response
    {
        if (! $request->user()) {
            return back()->with('error', 'User not found');
        }

        return $request->user()->hasVerifiedEmail()
                    ? redirect()->intended(route(App::getRedirectRouteAfterLogin(), absolute: false))
                    : Inertia::render('auth/VerifyEmail', ['status' => $request->session()->get('status')]);
    }
}
