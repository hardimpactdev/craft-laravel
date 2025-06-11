<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;
use NckRtl\RouteMaker\Get;

class AppearanceController extends Controller
{
    #[Get(uri: '/settings/appearance', name: 'appearance', middleware: 'auth')]
    public function edit(): Response
    {
        return Inertia::render('settings/Appearance');
    }
}
