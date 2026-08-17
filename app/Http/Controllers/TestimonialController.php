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

        $featured = $testimonials[0];
        $supporting = array_slice($testimonials, 1);

        return view('testimonials.index', compact('featured', 'supporting'));
    }
}
