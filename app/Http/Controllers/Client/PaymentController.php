<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $orders = Order::where('user_id', $userId)
            ->whereIn('status', ['Approved', 'Processing', 'In Transit', 'Delivered'])
            ->orderBy('created_at', 'desc')
            ->get();

        $counts = [
            'all' => $orders->count(),
            'unpaid' => $orders->where('payment_status', 'unpaid')->count(),
            'paid' => $orders->where('payment_status', 'paid')->count(),
        ];

        return view('client.payments', compact('orders', 'counts'));
    }

    public function pay(Request $request, $id)
    {
        $request->validate(['payment_method' => 'required|string']);
        $order = Order::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
        $order->update([
            'payment_status' => 'paid',
            'payment_method' => $request->payment_method,
            'payment_date' => now(),
        ]);
        return back()->with('success', 'Payment submitted successfully!');
    }
}