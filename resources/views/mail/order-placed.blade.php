@component('mail::message')
# 🛒 Order Confirmation

Hello **{{ $order->customer->name }}**,  

Thank you for shopping with us! Your order **#{{ $order->id }}** has been received. Below are the details:

---

## 📦 Order Summary
**📝 Status:** {{ ucfirst($order->status ?? 'Pending') }}  
**💰 Total Amount:** ₹{{ number_format($order->total_amount ?? 0, 2) }}  
**📅 Order Date:** {{ $order->created_at->setTimezone('Asia/Kolkata')->format('d M Y, h:i A') }}
 

---

## 🚚 Shipping Address  
@if($order->shippingAddress)
**{{ $order->shippingAddress['full_name'] ?? 'N/A' }}**  
📍 {{ $order->shippingAddress['address_line1'] ?? '' }}  
@if($order->shippingAddress['address_line2']){{ $order->shippingAddress['address_line2'] }}@endif  
🏙️ {{ $order->shippingAddress['city'] ?? '' }},  
🌍 {{ $order->shippingAddress['state'] ?? '' }} - {{ $order->shippingAddress['postal_code'] ?? '' }}  
📞 {{ $order->shippingAddress['phone'] ?? 'N/A' }}  
@else
🚫 Shipping address not available.
@endif  

---

## 🛍️ Order Items  
@component('mail::table')
| 🏷️ Product       | 🔢 Quantity | 💰 Price | 📊 Subtotal  |
|----------------|------------|---------|-------------|
@foreach ($order->orderItems as $item)
|   **  {{ $item->product_name }}** | {{ $item->quantity }} | ₹{{ number_format($item->unit_price, 2) }} | ₹{{ number_format(($item->quantity * $item->unit_price), 2) }} |
@endforeach
@endcomponent


---

@component('mail::button', ['url' => url('/order/' . $order->id), 'color' => 'success'])
🔍 View Your Order
@endcomponent  

Thanks for shopping with us!  
**{{ config('app.name') }}**  
@endcomponent
