<?php

namespace App\Http\Controllers;


class ConfirmationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke() 
    {
        
        $orderNumber = session('order_number');

        // Only checkout sets the order number. Without it nothing was just ordered
        // (a typed address, a refreshed page), so there is nothing to confirm and
        // the cart must stay as it is.
        if ($orderNumber === null) {
            return redirect()->route('orders.index');
        }

        session()->forget('cart_items');
        session()->forget('order_number');

        return view('confirmation.show', compact('orderNumber'));

    }
}
