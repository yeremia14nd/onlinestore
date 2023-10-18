<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric|min:0',
            'inventory' => 'required|integer|min:0',
        ]);

        // Create the product
        $product = new Product();
        $product->name = $request->input('name');
        $product->price = $request->input('price');
        $product->inventory = $request->input('inventory');
        $product->save();

        return response()->json(['message' => 'Product created successfully'], 201);
    }
}
