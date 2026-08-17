<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AboutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $team = config('northlight.team');
        $business = config('northlight.business');

        return view('about.index', compact('team', 'business'));
    }
}
