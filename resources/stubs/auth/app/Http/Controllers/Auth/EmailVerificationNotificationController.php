<?php

namespace App\Http\Controllers\Auth;

use App\App;
use App\Http\Controllers\Controller;
use HardImpact\Waymaker\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmailVerificationNotificationController extends Controller
{
    /**
     * Send a new email verification notification.
     */
    #[Post(uri: '/email/verification-notification', middleware: ['auth', 'throttle:6,1'])]
    public function sendNotification(Request $request): RedirectResponse
    {
        if (! $request->user()) {
            return back()->with('error', 'User not found');
        }

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->intended(route(App::getRedirectRouteAfterLogin(), absolute: false));
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'verification-link-sent');
    }
}
