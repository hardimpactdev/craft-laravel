<?php

namespace App\Http\Controllers\Auth;

use App\App;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use NckRtl\RouteMaker\Get;
use NckRtl\RouteMaker\Post;

class ConfirmablePasswordController extends Controller
{
    /**
     * Show the confirm password page.
     */
    #[Get(uri: '/confirm-password', name: 'password.confirm', middleware: 'auth')]
    public function show(): Response
    {
        return Inertia::render('auth/ConfirmPassword');
    }

    /**
     * Confirm the user's password.
     */
    #[Post(uri: '/confirm-password', middleware: 'auth')]
    public function confirm(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()?->email,
            'password' => $request->password,
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }

        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route(App::getRedirectRouteAfterLogin(), absolute: false));
    }
}
