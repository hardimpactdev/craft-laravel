<?php

namespace {{namespace}};

class App
{
    public static function getRedirectRouteAfterLogin(): string
    {
        return 'Controllers.DashboardController.show';
    }
}