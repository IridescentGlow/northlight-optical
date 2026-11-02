<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Product;
use App\Services\CartService;

class CartController extends Controller
{

    private CartService $cartService;


    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
        
    }

    /**
     * Display a listing of the resource.
     */
    public function index() 
    {



        $cartItems = $this->cartService->getCartItems();

        $subTotal = $this->cartService->getCartSubTotal();
        
        return view('cart.index', compact('cartItems', 'subTotal'));

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'product_slug' => 'required|string|exists:products,slug',
            'quantity' => 'nullable|integer|min:1|max:' . CartService::MAX_PER_PRODUCT,
        ]);

        $quantity = (int) ($request->quantity ?? 1);

        $product = Product::where('slug', $request->product_slug)->FirstOrFail();

        $capped = $this->cartService->addCartItem($product, $quantity);

        return redirect()->back()->with('cart', $capped
            ? 'Cart Updated. You can order up to ' . CartService::MAX_PER_PRODUCT . ' of one frame.'
            : 'Cart Updated.');

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // The quantity menu on the cart page sends PATCH; the remove button sends PUT.
        if ($request->isMethod('PATCH')) {
            $request->validate([
                'quantity' => 'bail|required|integer|min:1|max:' . CartService::MAX_PER_PRODUCT,
            ]);

            $this->cartService->setCartItemQuantity((int) $id, (int) $request->quantity);

            return redirect()->route('cart.index')->with('cart', 'Cart Updated.');
        }

        
        $this->cartService->removeCartItem((int) $id);
        
        return redirect()->route('cart.index')->with('cart', 'Cart Updated.');
    
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {

        $this->cartService->emptyCart();

        return redirect()->route('cart.index')->with('cart', 'Cart Updated.');
    }
}
