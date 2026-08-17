<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class ServicesController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $services = config('northlight.services');

        return view('services.index', compact('services'));
    }
}
