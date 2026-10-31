<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class RegisterController extends Controller
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
            : view('auth.register');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|string|email|unique:users,email|max:50',
            'password' => 'required|string|min:5|max:25|confirmed',
        ]);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        auth()->login($user);
        
        $request->session()->regenerate();

        $fullName = str()->limit($user->fullName, 25);
        return redirect()->intended(route('home'))->with('success', "Welcome, $fullName");

    }

    
}
