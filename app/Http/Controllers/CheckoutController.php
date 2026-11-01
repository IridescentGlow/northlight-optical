<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use App\Services\CartService;


class CheckoutController extends Controller
{

    private CartService $cartService;


    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $subTotal = $this->cartService->getCartSubTotal();

        return view('checkout.create', compact('subTotal'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // People type these the way they read them: (503) 555-0142, +1 503 555 0142,
        // 4242 4242 4242 4242. Keep the digits so the length rules below judge the
        // number, not the spacing. Anything that is not a string is left for
        // the rules to reject.
        $this->keepDigits($request, 'phone_number', 'phone');
        $this->keepDigits($request, 'card_number', 'card');

        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|max:100',
            'phone_number' => 'required|string|digits:10',
            'address' => 'required|string|max:150',
            // A US ZIP (the shop is in Portland, OR), ZIP+4, or a six-character
            // code such as M5V 3L9 or 560001, which is all the old size:6 rule took.
            'postal' => ['required', 'string', 'regex:/^(\d{5}(-\d{4})?|[A-Za-z0-9]{3} ?[A-Za-z0-9]{3})$/'],
            'order_notes' => 'nullable|string|max:150',
            'card_number' => 'required|string|digits:16',
            'expiry_date' => ['required', 'string', 'size:5', 'regex:/^(0[1-9]|1[0-2])\/\d{2}$/'],
            'cvv' => 'required|string|digits:3',
        ], [
            'postal.regex' => 'Enter a 5-digit ZIP code, such as 97209, or a six-character postal code.',
        ]);

        // A card is good through the last day of the month printed on it, so
        // 01/26 is already expired in October 2026 even though the year matches.
        [$expiryMonth, $expiryYear] = array_map('intval', explode('/', $request->expiry_date));
        if ($expiryYear < (int) date('y') || ($expiryYear === (int) date('y') && $expiryMonth < (int) date('n'))) {
            // withInput() skips the handler's $dontFlash list, so leave the card out by hand
            return back()->withErrors(['expiry_date' => 'The expiry date field is invalid.'])
                ->withInput($request->except(['card_number', 'cvv']));
        }
        
        DB::transaction(function() use($request){
    
            $cartItems = $this->cartService->getCartItems();
            $subTotal = $this->cartService->getCartSubTotal();
            $tax = round($subTotal * 0.05, 2);

           $order = Order::createOrder($request, $subTotal, $tax, $cartItems);

           $request->session()->put('order_number', $order->order_number);

        });


        return redirect()->route('confirmation');

    }

    /**
     * Drop spaces, dashes, dots and brackets from a field that should be only digits.
     * A phone number may also start with the +1 country code.
     */
    private function keepDigits(Request $request, string $key, string $kind): void
    {
        $value = $request->input($key);

        if (! is_string($value)) {
            return;
        }

        $digits = preg_replace('/[\s().-]/', '', $value);

        if ($kind === 'phone') {
            $digits = preg_replace('/^\+?1(?=\d{10}$)/', '', $digits);
        }

        $request->merge([$key => $digits]);
    }
}
