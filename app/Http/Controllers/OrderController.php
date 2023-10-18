<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::all();
        return response()->json($orders);
    }


    public function show(Order $order)
    {
        return response()->json($order);
    }


    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $product = Product::find($request->input('product_id'));

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }

        if ($product->inventory < $request->input('quantity')) {
            return response()->json(['message' => 'Insufficient inventory'], 400);
        }

        // Create the order
        $order = new Order();
        $order->product_id = $request->input('product_id');
        $order->quantity = $request->input('quantity');
        $order->save();

        // Deduct the inventory
        $product->inventory -= $request->input('quantity');
        $product->save();

        return response()->json(['message' => 'Order created successfully'], 201);
    }
}
