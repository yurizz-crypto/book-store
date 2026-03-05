@component('mail::message')
# Order #{{ $order->id }} Update

Hi {{ $user->first_name ?? 'Reader' }},

Your order status for **PageTurner.** has been updated to: **{{ strtoupper($order->status) }}**.

@component('mail::table')
| Item | Qty | Subtotal |
| :--- | :---: | :--- |
@foreach($order->orderItems as $item)
| {{ $item->book->title ?? 'Book' }} | {{ $item->quantity }} | ₱{{ number_format($item->unit_price * $item->quantity, 2) }} |
@endforeach
| **Total** | | **₱{{ number_format($order->total_amount, 2) }}** |
@endcomponent

@component('mail::button', ['url' => route('orders.show', $order), 'color' => 'orange'])
View Order Progress
@endcomponent

@php 
    $address = $user->addresses()->where('is_default', true)->first(); 
@endphp

@if($address)
@component('mail::subcopy')
**Shipping Address:** {{ $address->street_address }}, {{ $address->city }}
@endcomponent
@endif

Thanks,<br>
{{ config('app.name') }}
@endcomponent