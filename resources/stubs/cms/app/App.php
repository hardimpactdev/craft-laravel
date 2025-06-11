<?php

namespace App;

class App
{
    public static function getRedirectRouteAfterLogin(): string
    {
        return 'Controllers.DashboardController.show';
    }
}
