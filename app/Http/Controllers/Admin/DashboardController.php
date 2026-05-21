<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product; // <--- Double check this points to your Product model
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
{
    // Sum all stock from products that are NOT archived
    $totalStock = Product::sum('stock');

    // Count products where stock is 10 or less
    $lowStockCount = Product::where('stock', '<=', 10)->where('stock', '>', 0)->count();

    return view('admin.admindashboard', compact('totalStock', 'lowStockCount'));
}
}