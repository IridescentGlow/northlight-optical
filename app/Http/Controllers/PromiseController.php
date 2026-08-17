<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class PromiseController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(): View
    {
        $promise = config('northlight.promise');

        return view('promise.index', compact('promise'));
    }
}
