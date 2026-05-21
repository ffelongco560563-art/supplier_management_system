<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('client.order-milk', compact('products'));
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'contact_name' => 'required',
            'phone_number' => 'required',
            'address' => 'required',
            'total_price' => 'required',
            'cart_items' => 'required'
        ]);

        try {

            $latestOrder = Order::latest()->first();

            if ($latestOrder && preg_match('/(\d+)$/', $latestOrder->order_number, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = 1;
            }

            $formattedId = 'PO-' . date('Y') . '-' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $user = auth()->user();

            $enrichedItems = collect($request->cart_items)->map(function ($item) {
    $product = \App\Models\Product::find($item['product_id']);
    $item['image'] = $product && $product->image_path
        ? asset('storage/' . $product->image_path)
        : null;
    return $item;
})->toArray();

$order = Order::create([
    'order_number' => $formattedId,
    'user_id' => $user->id,
    'customer_name' => $request->contact_name,
    'customer_email' => $user->email,
    'phone_number' => $request->phone_number,
    'address' => $request->address,
    'message_instructions' => $request->message_instructions,
    'total_price' => $request->total_price,
    'status' => 'Pending',
    'product_details' => $enrichedItems,
]);

            return response()->json([
                'success' => true,
                'order_id' => $formattedId
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}