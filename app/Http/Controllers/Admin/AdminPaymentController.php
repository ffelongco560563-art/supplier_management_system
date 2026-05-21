<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminPaymentController extends Controller
{
    public function index()
    {
        $orders = Order::whereIn('status', [
                'Pending',
                'Approved',
                'Processing',
                'In Transit',
                'Delivered'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        $counts = [
            'all' => $orders->count(),
            'unpaid' => $orders->where('payment_status', 'unpaid')->count(),
            'paid' => $orders->where('payment_status', 'paid')->count(),
        ];

        return view('admin.payments', compact('orders', 'counts'));
    }

    public function confirm($id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'payment_status' => 'paid',
            'payment_date' => now()
        ]);

        return back()->with('success', 'Payment confirmed.');
    }
}