<?php

namespace App\Http\Controllers\Auth;

use App\App;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;
use NckRtl\RouteMaker\Get;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated user's email address as verified.
     */
    #[Get(uri: '/verify-email/{id}/{hash}', middleware: ['auth', 'signed', 'throttle:6,1'])]
    public function __invoke(EmailVerificationRequest $request): RedirectResponse
    {
        if ($request->user()?->hasVerifiedEmail()) {
            return redirect()->intended(route(App::getRedirectRouteAfterLogin(), absolute: false).'?verified=1');
        }

        if ($request->user()?->markEmailAsVerified()) {
            /** @var \Illuminate\Contracts\Auth\MustVerifyEmail $user */
            $user = $request->user();
            event(new Verified($user));
        }

        return redirect()->intended(route(App::getRedirectRouteAfterLogin(), absolute: false).'?verified=1');
    }
}
