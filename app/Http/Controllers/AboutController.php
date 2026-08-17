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

        $languageCount = collect($team)->pluck('languages')->flatten()->unique()->count();

        return view('about.index', compact('team', 'business', 'languageCount'));
    }
}
