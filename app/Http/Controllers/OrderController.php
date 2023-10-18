<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        return DB::transaction(function () use ($request) {
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

            // Deduct the inventory with optimistic locking
            $product->where('id', $request->input('product_id'))
                ->where('version', $product->version) // Check version
                ->update([
                    'inventory' => $product->inventory - $request->input('quantity'),
                    'version' => $product->version + 1, // Increment version
                ]);

            if ($product->wasChanged() == false) {
                return response()->json(['message' => 'Inventory update failed due to a race condition'], 409);
            }

            // Create the order
            $order = new Order;
            $order->product_id = $request->input('product_id');
            $order->quantity = $request->input('quantity');
            $order->save();

            return response()->json(['message' => 'Order created successfully'], 201);
        });
    }
}
