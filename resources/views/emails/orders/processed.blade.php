@component('mail::message')
# Attention Admin!

A new order has been placed on the **PageTurner** system.

**Customer:** {{ $customerName }}  
**Order ID:** #{{ $order->id }}  
**Total Amount:** ₱{{ $total }}

@component('mail::table')
| Book Title | Qty | Price |
| :--- | :---: | :--- |
@foreach($order->orderItems as $item)
| {{ $item->book->title ?? 'Deleted Book' }} | {{ $item->quantity }} | ₱{{ number_format($item->unit_price, 2) }} |
@endforeach
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'orange'])
Review Order Details
@endcomponent

Please check the admin dashboard for fulfillment.

Thanks,<br>
{{ config('app.name') }}
@endcomponent