<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the orders.
     */
    public function index()
    {
        $orders = Order::with('customer')->latest()->paginate(10);
        return view('pages.admin.orders.index', compact('orders'));
    }

    /**
     * Show the details of a specific order.
     */
    public function show(Order $order)
    {
        $try=$order->load('customer', 'orderItems.product');
        return view('pages.admin.orders.show', compact('order'));
    }
    public function edit(Order $order)
    {
        return view('pages.admin.orders.edit', compact('order'));
    }


    /**
     * Update the order status.
     */
    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|string|in:pending,processing,shipped,delivered,canceled',
        ]);

        $order->update(['status' => $request->status]);
        return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
        
    }

    /**
     * Export orders as CSV.
     */
    public function exportCsv()
    {
        $orders = Order::with('customer')->get();
        $csvData = "Order ID, Customer Name, Total Amount, Status, Date\n";

        foreach ($orders as $order) {
            $csvData .= "{$order->id}, {$order->customer->name}, \${$order->total_amount}, {$order->status}, {$order->created_at}\n";
        }

        return Response::make($csvData, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders.csv"',
        ]);
    }
}
