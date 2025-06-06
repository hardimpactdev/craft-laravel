<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use Inertia\Response;
use NckRtl\RouteMaker\Get;
use NckRtl\RouteMaker\Post;

class ForgotPasswordController extends Controller
{
    /**
     * Show the password reset link request page.
     */
    #[Get(uri: '/forgot-password', name: 'password.request', middleware: 'guest')]
    public function show(Request $request): Response
    {
        return Inertia::render('auth/ForgotPassword', [
            'status' => $request->session()->get('status'),
        ]);
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    #[Post(uri: '/forgot-password', name: 'password.email', middleware: 'guest')]
    public function sendResetLink(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        Password::sendResetLink(
            $request->only('email')
        );

        return back()->with('status', __('A reset link will be sent if the account exists.'));
    }
}
