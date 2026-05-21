<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Truck;
use Illuminate\Http\Request;

class LogisticsController extends Controller
{
    public function index()
    {
        $trucks = Truck::all();
        $orders = Order::whereIn('status', ['Processing', 'In Transit', 'Delivered'])->get();
        return view('admin.logistics', compact('trucks', 'orders'));
    }

    public function storeTruck(Request $request)
    {
        $request->validate(['driver_name' => 'required|string|max:255']);
        $count = Truck::count() + 1;
        $truckId = 'TRK-' . date('Y') . '-' . str_pad($count, 2, '0', STR_PAD_LEFT);
        Truck::create([
            'truck_id' => $truckId,
            'driver_name' => $request->driver_name,
            'status' => 'unassigned',
        ]);
        return back()->with('success', 'Truck driver added successfully.');
    }

    public function updateTruck(Request $request, Truck $truck)
    {
        $request->validate([
            'driver_name' => 'required|string|max:255'
        ]);

        $truck->update([
            'driver_name' => $request->driver_name
        ]);

        return back()->with('success', 'Truck driver updated successfully.');
    }

    public function deleteTruck(Truck $truck)
    {
        $truck->delete();

        return back()->with('success', 'Truck driver deleted successfully.');
    }
}