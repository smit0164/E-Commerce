@component('mail::message')
# Order Confirmation

Thank you for your order, {{ $customerName }}!

Your order ID is **{{ $orderId }}**. We’ll notify you once it ships.

@component('mail::button', ['url' => url('/order/' . $orderId)])
View Your Order
@endcomponent

Thanks,<br>
@endcomponent