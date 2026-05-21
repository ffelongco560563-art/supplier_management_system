<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Truck;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('user')->orderBy('created_at', 'desc')->get();
        $trucks = Truck::all();
        return view('admin.Order', compact('orders', 'trucks'));
    }

    public function approve(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'Approved', 'decline_reason' => null]);
        return back()->with('success', 'Order approved successfully.');
    }

    public function assign(Request $request, $id)
    {
        $request->validate(['truck_id' => 'required']);
        $order = Order::findOrFail($id);
        $truck = Truck::where('truck_id', $request->truck_id)->first();
        $order->update(['status' => 'Processing', 'truck_id' => $request->truck_id]);
        if ($truck) $truck->update(['status' => 'processing']);
        return back()->with('success', 'Order assigned to truck and moved to Processing.');
    }

    public function ship(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $truck = Truck::where('truck_id', $order->truck_id)->first();
        $order->update(['status' => 'In Transit']);
        if ($truck) $truck->update(['status' => 'in_transit']);
        return back()->with('success', 'Order is now In Transit.');
    }

    public function deliver(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $truck = Truck::where('truck_id', $order->truck_id)->first();
    $order->update(['status' => 'Delivered']);
    if ($truck) $truck->update(['status' => 'unassigned']); // Free the truck
    return back()->with('success', 'Order marked as Delivered.');
}

    public function decline(Request $request, $id)
    {
        $request->validate(['reason' => 'required']);
        $order = Order::findOrFail($id);
        $order->update(['status' => 'Declined', 'decline_reason' => $request->reason]);
        return back()->with('success', 'Order declined.');
    }

    public function restore($id)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => 'Pending', 'decline_reason' => null]);
        return back()->with('success', 'Order restored to pending.');
    }
}