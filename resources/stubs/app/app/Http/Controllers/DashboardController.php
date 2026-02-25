<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use HardImpact\Waymaker\Get;

class DashboardController extends Controller
{
    #[Get(uri: '/dashboard', middleware: 'auth')]
    public function show(): \Inertia\ResponseFactory|\Inertia\Response
    {
        return inertia('Dashboard');
    }
}
