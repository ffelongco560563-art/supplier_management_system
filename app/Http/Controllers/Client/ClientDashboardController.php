<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        if (Schema::hasColumn('orders', 'user_id')) {
            $myOrdersCount = Order::where('user_id', $userId)->count();
            
            $activeDeliveries = Order::where('user_id', $userId)
                                     ->whereIn('status', ['processing', 'shipped'])
                                     ->count();
                                     
            // Limits to 5 rows for pagination
            $recentOrders = Order::where('user_id', $userId)
                                 ->latest()
                                 ->paginate(5);
        } else {
            $myOrdersCount = 0;
            $activeDeliveries = 0;
            $recentOrders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 5);
        }

        return view('client.dashboard', compact('myOrdersCount', 'recentOrders', 'activeDeliveries'));
    }

    
}