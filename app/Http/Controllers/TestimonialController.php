<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class TestimonialController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $testimonials = config('northlight.testimonials');

        return view('testimonials.index', compact('testimonials'));
    }
}
