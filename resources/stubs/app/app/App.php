<?php

namespace App;

class App
{
    public static function getRedirectRouteAfterLogin(): string
    {
        $redirectRouteAfterLogin = config('auth.redirect_route_after_login');

        if (gettype($redirectRouteAfterLogin) !== 'string') {
            return 'dashboard';
        }

        return $redirectRouteAfterLogin;
    }
}