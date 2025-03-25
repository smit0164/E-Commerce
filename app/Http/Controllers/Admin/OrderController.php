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
        try {
            $query = Order::with('customer')->latest();
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            $orders = $query->simplePaginate(10);
            return view('pages.admin.orders.index', compact('orders'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to load orders: ' . $e->getMessage());
        }
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->with('error', 'Validation failed: ' . $e->getMessage())
                        ->withErrors($e->errors())
                        ->withInput();
        } catch (\Exception $e) {
            return redirect()->route('admin.orders.index')->with('error', 'Failed to update order: ' . $e->getMessage());
        }
    }

}