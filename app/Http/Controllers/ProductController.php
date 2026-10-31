<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) : View
    {

        // ?search[]=x and the like arrive as arrays; only text can be searched for
        $text = fn (string $key) => is_string($request->query($key)) ? $request->query($key) : null;

        $products = Product::filter($text('search'), $text('category'), $text('type'))->simplePaginate(6)->withQueryString();

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
