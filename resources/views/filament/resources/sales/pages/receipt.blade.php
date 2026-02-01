<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt #{{ $sale->id }}</title>
    <style>
        @page {
            size: 80mm 200mm;
            margin: 0;
        }

        body {
            font-family: 'Courier New', Courier, monospace;
            width: 80mm;
            margin: 0;
            padding: 5mm;
            font-size: 12px;
            line-height: 1.2;
        }

        .text-center {
            text-center: center;
        }

        .text-right {
            text-align: right;
        }

        .font-bold {
            font-weight: bold;
        }

        .border-b {
            border-bottom: 1px dashed #000;
            margin: 5px 0;
        }

        .flex {
            display: flex;
            justify-content: space-between;
        }

        .header {
            margin-bottom: 15px;
            text-align: center;
        }

        .item-row {
            margin-bottom: 3px;
        }

        .footer {
            margin-top: 15px;
            text-align: center;
            font-size: 10px;
        }
    </style>
</head>

<body onload="window.print(); window.onafterprint = function() { window.close(); }">
    <div class="header">
        <div class="font-bold" style="font-size: 16px;">JEWELRY IMS</div>
        <div>Standardized Luxury & Quality</div>
        <div>Accra, Ghana</div>
        <div>Tel: +233 24 000 0000</div>
    </div>

    <div class="border-b"></div>

    <div>Date: {{ $sale->sale_date->format('d/m/Y H:i') }}</div>
    <div>Receipt: #{{ str_pad($sale->id, 6, '0', STR_PAD_LEFT) }}</div>
    <div>Customer: {{ $sale->customer->name }}</div>

    <div class="border-b"></div>

    <div class="font-bold flex">
        <span>Item</span>
        <span>Total</span>
    </div>

    <div class="border-b"></div>

    @foreach($sale->items as $item)
        <div class="item-row">
            <div>{{ $item->product->name }}</div>
            <div class="flex">
                <span>{{ $item->quantity }} x {{ number_format($item->unit_selling_price, 2) }}</span>
                <span>{{ number_format($item->quantity * $item->unit_selling_price, 2) }}</span>
            </div>
        </div>
    @endforeach

    <div class="border-b"></div>

    <div class="flex">
        <span>Subtotal:</span>
        <span>GH₵ {{ number_format($sale->total_amount + ($sale->discount ?? 0), 2) }}</span>
    </div>
    @if($sale->discount > 0)
        <div class="flex">
            <span>Discount:</span>
            <span>-GH₵ {{ number_format($sale->discount, 2) }}</span>
        </div>
    @endif
    <div class="flex font-bold">
        <span>GRAND TOTAL:</span>
        <span>GH₵ {{ number_format($sale->total_amount, 2) }}</span>
    </div>
    <div class="flex">
        <span>Paid:</span>
        <span>GH₵ {{ number_format($sale->amount_paid, 2) }}</span>
    </div>
    <div class="flex">
        <span>Balance:</span>
        <span>GH₵ {{ number_format($sale->total_amount - $sale->amount_paid, 2) }}</span>
    </div>

    <div class="border-b"></div>

    <div class="footer">
        <div class="font-bold">Thank You for Your Business!</div>
        <div>Items once sold are not returnable under specific conditions.</div>
        <div>Software by Antigravity</div>
    </div>
</body>

</html>