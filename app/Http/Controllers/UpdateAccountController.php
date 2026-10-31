<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UpdateAccountController extends Controller
{
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        return view('account.update-account');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|' . Rule::unique('users', 'email')->ignore(auth()->user()),
        ]);

        auth()->user()->update([
           'first_name' => $request->first_name,
           'last_name' => $request->last_name,
           'email' => $request->email
        ]);

        return redirect()->route('update.account.create')->with('success', 'Account Updated.');


    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {

        auth()->user()->orders()->delete();
        
        auth()->user()->delete();

        auth()->logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect()->route('home')->with('success', 'Account Deleted.');

    }

    
}
