<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): View|RedirectResponse
    {
     
        $orders = Order::with('orderItems.product')
                ->where('user_id', auth()->id())
                ->latest('id')
                ->simplePaginate();

        // A page past the end (an old bookmark, a typed number) has no rows, so the
        // view would say "No Orders Found" to someone who has orders. Send them to
        // the first page instead.
        if ($orders->isEmpty() && $orders->currentPage() > 1) {
            return redirect()->route('orders.index');
        }

        return view('orders.index', compact('orders'));

    }
}
