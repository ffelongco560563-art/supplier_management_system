<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MyOrdersController extends Controller
{
    public function index(Request $request)
{
    $userId = Auth::id();
    // Get the status from the URL, default to 'all'
    $status = $request->query('status', 'all');

    // 1. Fetch all orders for this user to calculate counts
    $allOrders = Order::where('user_id', $userId)->get();

    // 2. Filter orders for the table
    $query = Order::where('user_id', $userId);

    if ($status !== 'all') {
        $statusMap = [
            'pending' => 'Pending',
            'approved' => 'Approved',
            'in-transit' => 'In Transit',
            'delivered' => 'Delivered',
            'canceled' => 'Canceled',
            'declined' => 'Declined',
            'processing' => 'Processing',
        ];
        $query->where('status', $statusMap[$status] ?? ucfirst($status));
    }

    $orders = $query->orderBy('created_at', 'desc')->get();

    // 3. Prepare the counts for the tab badges
    $counts = [
        'all' => $allOrders->count(),
        'pending' => $allOrders->where('status', 'Pending')->count(),
        'approved' => $allOrders->where('status', 'Approved')->count(),
        'in-transit' => $allOrders->where('status', 'In Transit')->count(),
        'delivered' => $allOrders->where('status', 'Delivered')->count(),
        'canceled' => $allOrders->where('status', 'Canceled')->count(),
        'declined' => $allOrders->where('status', 'Declined')->count(),
        'processing' => $allOrders->where('status', 'Processing')->count(),
    ];

    return view('client.my-orders', compact('orders', 'counts'));
}
}