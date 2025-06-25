<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total_amount');
        $totalCustomers = Customer::count(); // Get total customers from the `customers` table

        $orderCounts = Order::selectRaw('status, COUNT(*) as count')
        ->whereIn('status', ['pending', 'shipped', 'delivered'])
        ->groupBy('status')
        ->pluck('count', 'status');
       
        // Retrieve counts with a default value of 0 if not present
        $pendingOrders   = $orderCounts->get('pending', 0);
        $shippedOrders   = $orderCounts->get('shipped', 0);
        $deliveredOrders = $orderCounts->get('delivered', 0);
        $latestOrders = Order::with('customer')->latest()->take(5)->get();
    
        return view('pages.admin.dashboard', compact(
            'totalOrders',
            'totalRevenue',
            'totalCustomers',
            'pendingOrders',
            'shippedOrders',
            'deliveredOrders',
            'latestOrders'
        ));
    }
    

}