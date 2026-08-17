<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $business = config('northlight.business');
        $hours = config('northlight.hours');
        $reasons = config('northlight.contact_reasons');

        return view('contact.create', compact('business', 'hours', 'reasons'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|max:80',
            'email' => 'required|email|max:80',
            'phone_number' => 'nullable|max:25',
            'reason' => 'required|in:' . implode(',', config('northlight.contact_reasons')),
            'preferred_date' => 'nullable|date|after_or_equal:today',
            'message' => 'required|max:1000',
        ]);

        Log::info('Northlight contact/booking request received', $validated);

        return redirect()
            ->route('contact.create')
            ->with('success', "Thanks, {$validated['name']}! We've received your request and will get back to you within one business day.");
    }
}
