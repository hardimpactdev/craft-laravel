<?php

namespace App\Http\Controllers\Auth;

use App\App;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use HardImpact\Waymaker\Get;
use HardImpact\Waymaker\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

class LoginController extends Controller
{
    /**
     * Show the login page.
     */
    #[Get(uri: '/login', middleware: 'guest')]
    public function show(Request $request): Response
    {
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    #[Post(uri: '/login', middleware: 'guest')]
    public function login(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route(App::getRedirectRouteAfterLogin(), absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    #[Post(uri: '/logout', middleware: 'auth')]
    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
