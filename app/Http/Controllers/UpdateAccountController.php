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
        
        // Changing the sign-in email needs the current password, so someone at an
        // unattended signed-in browser cannot move the account to their own address.
        // A name-only change does not. The field has its own name because the delete
        // form on the same page already shows errors for "current_password".
        $request->validate([
            'first_name' => 'required|string|max:50',
            'last_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:50|' . Rule::unique('users', 'email')->ignore(auth()->user()),
            'email_current_password' => [
                'bail', Rule::requiredIf($request->input('email') !== auth()->user()->email),
                'nullable', 'string', 'max:64', 'current_password',
            ],
        ], [], ['email_current_password' => 'current password']);

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
        // Deleting the account also deletes its order history, and nothing could be undone
        // from a stolen or left-open session, so the password is asked for first. bail: the
        // current_password rule throws on a list (current_password[]=x).
        $request->validate([
            'current_password' => 'bail|required|string|max:64|current_password',
        ]);

        auth()->user()->orders()->delete();
        
        auth()->user()->delete();

        auth()->logout();
 
        $request->session()->invalidate();
     
        $request->session()->regenerateToken();
     
        return redirect()->route('home')->with('success', 'Account Deleted.');

    }

    
}
