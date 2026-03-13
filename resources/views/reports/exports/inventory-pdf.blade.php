<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory Report - PDF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            background-color: #fff;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            margin: 0 0 5px 0;
            color: #2C3E50;
        }
        .header p {
            margin: 5px 0;
            color: #666;
            font-size: 14px;
        }
        .summary {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .summary-card {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            background-color: #f9f9f9;
        }
        .summary-card h4 {
            margin: 0 0 10px 0;
            font-size: 12px;
            color: #666;
        }
        .summary-card .value {
            font-size: 18px;
            font-weight: bold;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table th {
            background-color: #3E2723;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #333;
            font-size: 13px;
        }
        table td {
            padding: 10px 12px;
            border: 1px solid #ddd;
            font-size: 12px;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            border-top: 1px solid #ddd;
            padding-top: 15px;
            font-size: 12px;
            color: #666;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            .no-print {
                display: none;
            }
            table {
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>SaddhuSync - Inventory Stock Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h4>Total Items</h4>
            <div class="value">{{ $items->count() }}</div>
        </div>
        <div class="summary-card">
            <h4>Total Value</h4>
            <div class="value">Rp{{ number_format($totalValue, 0) }}</div>
        </div>
        <div class="summary-card">
            <h4>Low Stock Items</h4>
            <div class="value">{{ $items->filter(fn($i) => $i->isLowStock())->count() }}</div>
        </div>
        <div class="summary-card">
            <h4>Categories</h4>
            <div class="value">{{ $items->groupBy('category')->count() }}</div>
        </div>
        <div class="summary-card">
            <h4>Avg Unit Price</h4>
            <div class="value">Rp{{ number_format($items->avg('purchase_price') ?? 0, 0) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Item Name</th>
                <th>Category</th>
                <th class="text-center">Quantity</th>
                <th class="text-right">Unit Price (Rp)</th>
                <th class="text-right">Total Value (Rp)</th>
                <th class="text-center">Reorder Level</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($items as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category }}</td>
                    <td class="text-center">{{ $item->quantity }} {{ $item->unit }}</td>
                    <td class="text-right">{{ number_format($item->purchase_price ?? 0, 0) }}</td>
                    <td class="text-right">{{ number_format(($item->purchase_price ?? 0) * $item->quantity, 0) }}</td>
                    <td class="text-center">{{ $item->reorder_level ?? '-' }}</td>
                    <td class="text-center">{{ $item->isLowStock() ? 'Low Stock' : 'In Stock' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px;">No inventory items found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>This is an automated report from SaddhuSync Management System</p>
        <p style="margin-top: 10px;">Print this page using your browser's print function (Ctrl+P or Cmd+P) to save as PDF</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
