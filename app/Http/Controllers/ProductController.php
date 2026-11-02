<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : View|RedirectResponse
    {

        // ?search[]=x and the like arrive as arrays; only text can be searched for
        $text = fn (string $key) => is_string($request->query($key)) ? $request->query($key) : null;

        $products = Product::filter($text('search'), $text('category'), $text('type'))->simplePaginate(6)->withQueryString();

        // A page past the end (an old bookmark, ?page=999) is not "no products
        // match"; start the same list again from its first page.
        if ($products->isEmpty() && $products->currentPage() > 1) {
            return redirect()->route('products.index', $request->except('page'));
        }

        return view('products.index', compact('products'));


    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product) : View
    {
        return view('products.show', compact('product'));
    }


}
