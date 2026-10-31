<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // The cart's Log In and Create Account buttons ask to come back to
        // checkout afterwards. Only that one destination is accepted; a URL
        // from the query string is never used.
        if (request('next') === 'checkout') {
            session()->put('url.intended', route('checkout.create'));
        }

        return auth()->check() 
            ? redirect()->route('home') 
            : view('auth.login');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if (auth()->attempt($credentials)) {

            $request->session()->regenerate();
                
            $fullName = str()->limit(auth()->user()->fullName, 25);
            return redirect()->intended(route('home'))->with('success', "Welcome, $fullName");
        }
 
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');

    }

    
}
