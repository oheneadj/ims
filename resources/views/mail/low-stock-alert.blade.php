<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 5px;
        }

        .header {
            background: #f8f9fa;
            padding: 10px 20px;
            border-bottom: 2px solid #ffc107;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            color: #856404;
            font-size: 24px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #ffc107;
            color: #000;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
        }

        .footer {
            font-size: 12px;
            color: #777;
            margin-top: 30px;
            border-t: 1px solid #eee;
            padding-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Low Stock Alert</h1>
        </div>
        <p>Hello Admin,</p>
        <p>The following products have reached or dropped below their minimum stock threshold (5 units):</p>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>SKU</th>
                    <th>Current Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->sku ?? 'N/A' }}</td>
                        <td style="color: #dc3545; font-weight: bold;">{{ $product->quantity_in_stock }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>Please consider restock these items soon to avoid service interruptions.</p>

        <p><a href="{{ url('/admin/products') }}" class="btn">Manage Inventory</a></p>

        <div class="footer">
            <p>This is an automated notification from your Jewelry IMS.</p>
        </div>
    </div>
</body>

</html>