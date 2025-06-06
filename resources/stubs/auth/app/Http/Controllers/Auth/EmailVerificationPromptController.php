<?php

namespace App\Http\Controllers\Auth;

use App\App;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use NckRtl\RouteMaker\Get;

class EmailVerificationPromptController extends Controller
{
    /**
     * Show the email verification prompt page.
     */
    #[Get(uri: '/verify-email', name: 'verification.notice', middleware: 'auth')]
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
