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

        //pending, shippied and delivered with only one query
        $pendingOrders = Order::where('status', 'pending')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
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