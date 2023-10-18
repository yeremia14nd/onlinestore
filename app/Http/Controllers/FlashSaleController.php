<?php

namespace App\Http\Controllers;

use App\Models\FlashSale;
use App\Models\Product;
use Illuminate\Http\Request;

class FlashSaleController extends Controller
{
    public function index()
    {
        $flashSales = FlashSale::all();
        return response()->json($flashSales);
    }


    public function show(FlashSale $flashSale)
    {
        return response()->json($flashSale);
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'discount' => 'required|numeric|min:1',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
        ]);

        $product = Product::find($request->input('product_id'));

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        // Create the flash sale
        $flashSale = new FlashSale();
        $flashSale->product_id = $request->input('product_id');
        $flashSale->discount = $request->input('discount');
        $flashSale->start_time = $request->input('start_time');
        $flashSale->end_time = $request->input('end_time');
        $flashSale->save();

        return response()->json(['message' => 'Flash sale created successfully'], 201);
    }
}
