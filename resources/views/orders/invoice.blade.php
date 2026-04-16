<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->id }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; font-size: 14px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); }
        .header { width: 100%; margin-bottom: 30px; }
        .header td { padding: 5px; vertical-align: top; }
        .header .title { font-size: 35px; font-weight: bold; color: #001BB7; }
        .header .invoice-details { text-align: right; }
        .customer-details { margin-bottom: 30px; }
        .table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        .table th, .table td { padding: 10px; border-bottom: 1px solid #ddd; text-align: left; }
        .table th { background-color: #f5f5f5; color: #001BB7; font-weight: bold; }
        .table .right { text-align: right; }
        .totals { width: 50%; float: right; border-collapse: collapse; }
        .totals th, .totals td { padding: 8px; text-align: right; }
        .totals .grand-total { font-size: 18px; font-weight: bold; color: #001BB7; border-top: 2px solid #333; }
        .clear { clear: both; }
        .footer { margin-top: 50px; text-align: center; color: #777; font-size: 12px; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <table class="header">
            <tr>
                <td class="title">PageTurner</td>
                <td class="invoice-details">
                    <strong>Invoice #:</strong> {{ $order->id }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}<br>
                    <strong>Status:</strong> {{ strtoupper($order->status) }}
                </td>
            </tr>
        </table>

        <div class="customer-details">
            <strong>Billed To:</strong><br>
            {{ $order->user->first_name }} {{ $order->user->last_name }}<br>
            {{ $order->user->email }}
        </div>

        <table class="table">
            <thead>
                <tr>
                    <th>Book Title</th>
                    <th class="right">Price</th>
                    <th class="right">Qty</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
            @foreach($order->orderItems as $item)
                <tr>
                    <td>{{ $item->book->title }}</td>
                    <td class="right">PHP {{ number_format($item->price, 2) }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">PHP {{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals">
            <tr>
                <th>Subtotal:</th>
                <td>PHP {{ number_format($order->total_amount / 1.12, 2) }}</td>
            </tr>
            <tr>
                <th>Estimated Tax (12%):</th>
                <td>PHP {{ number_format($order->total_amount - ($order->total_amount / 1.12), 2) }}</td>
            </tr>
            <tr class="grand-total">
                <th>Total Amount:</th>
                <td>PHP {{ number_format($order->total_amount, 2) }}</td>
            </tr>
        </table>
        
        <div class="clear"></div>

        <div class="footer">
            Thank you for shopping with PageTurner! If you have any questions about this invoice, please contact support.
        </div>
    </div>
</body>
</html>