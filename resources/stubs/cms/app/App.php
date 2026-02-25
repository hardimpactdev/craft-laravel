<?php

declare(strict_types=1);

namespace App;

class App
{
    public static function getRedirectRouteAfterLogin(): string
    {
        return 'Controllers.DashboardController.show';
    }
}
