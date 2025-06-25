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
    public function index(Request $request)
    {
        $query = Order::query();
    
        if ($request->ajax()) {
            if ($request->search) {
                $query->where('id', 'like', "%{$request->search}%")
                      ->orWhereHas('customer', function ($q) use ($request) {
                          $q->where('name', 'like', "%{$request->search}%");
                      });
            }
    
            if ($request->status) {
                $query->where('status', $request->status);
            }
    
            if ($request->date_start && $request->date_end) {
                $query->whereBetween('created_at', [$request->date_start, $request->date_end]);
            }
    
            // Eager load 'customer' in AJAX branch to prevent lazy loading errors.
            $orders = $query->with('customer')->latest()->simplePaginate(5);
    
            $html = view('pages.admin.orders.partials.orders_table', compact('orders'))->render();
            $pagination = view('pagination::simple-tailwind', ['paginator' => $orders])->render();
    
            return response()->json(['html' => $html, 'pagination' => $pagination]);
        }
    
        // For non-AJAX requests, eager loading is already added.
        $orders = $query->with('customer')->latest()->simplePaginate(5);
        return view('pages.admin.orders.index', compact('orders'));
    }
    

    /**
     * Show the details of a specific order.
     */
    public function show(Order $order)
    {
        try {
            $order->load('customer', 'orderItems.product');
            return view('pages.admin.orders.show', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Failed to load order details: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified order.
     */
    public function edit(Order $order)
    {
        try {
            return view('pages.admin.orders.edit', compact('order'));
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Failed to load edit form: ' . $e->getMessage());
        }
    }

    /**
     * Update the order status.
     */
    public function update(Request $request, Order $order)
    {
        try {
            $request->validate([
                'status' => 'required|string|in:pending,shipped,delivered',
            ]);

            $order->update(['status' => $request->status]);
            return redirect()->route('admin.orders.index')->with('success', 'Order status updated successfully.');
        }catch (\Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

}