<?php

namespace App\Http\Controllers;

use NckRtl\RouteMaker\Get;

class DashboardController extends Controller
{
    #[Get(uri: '/dashboard')]
    public function show(): \Inertia\ResponseFactory|\Inertia\Response
    {
        return inertia('Dashboard');
    }
}
