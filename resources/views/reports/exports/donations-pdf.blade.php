<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Donations Report - PDF</title>
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
            grid-template-columns: repeat(4, 1fr);
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
            font-size: 14px;
            color: #666;
        }
        .summary-card .value {
            font-size: 20px;
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
        }
        table td {
            padding: 10px 12px;
            border: 1px solid #ddd;
        }
        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
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
        <h1>SaddhuSync - Donations Report</h1>
        <p>Generated on {{ now()->format('F d, Y \a\t g:i A') }}</p>
    </div>

    <div class="summary">
        <div class="summary-card">
            <h4>Total Amount</h4>
            <div class="value">Rp{{ number_format($totalAmount, 0) }}</div>
        </div>
        <div class="summary-card">
            <h4>Total Donations</h4>
            <div class="value">{{ count($donations) }}</div>
        </div>
        <div class="summary-card">
            <h4>Average Donation</h4>
            <div class="value">Rp{{ number_format(count($donations) > 0 ? $totalAmount / count($donations) : 0, 0) }}</div>
        </div>
        <div class="summary-card">
            <h4>Largest Donation</h4>
            <div class="value">Rp{{ number_format($donations->max('amount') ?? 0, 0) }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Donor</th>
                <th>Contact Name</th>
                <th>Contact Phone</th>
                <th>Province</th>
                <th>City</th>
                <th>Postal Code</th>
                <th>Address</th>
                <th>Latitude</th>
                <th>Longitude</th>
                <th>Category</th>
                <th>Method</th>
                <th class="text-right">Amount (Rp)</th>
                <th>Type</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($donations as $donation)
                <tr>
                    <td>{{ $donation->donated_at->format('M d, Y') }}</td>
                    <td>{{ $donation->is_anonymous ? 'Anonymous' : $donation->member->name ?? 'Unknown' }}</td>
                    <td>{{ $donation->contact_name }}</td>
                    <td>{{ $donation->contact_phone }}</td>
                    <td>{{ $donation->province }}</td>
                    <td>{{ $donation->city }}</td>
                    <td>{{ $donation->postal_code }}</td>
                    <td>{{ $donation->address }}</td>
                    <td>{{ $donation->latitude }}</td>
                    <td>{{ $donation->longitude }}</td>
                    <td>{{ $donation->fundCategory->name ?? '-' }}</td>
                    <td>{{ str_replace('_', ' ', $donation->donation_method) }}</td>
                    <td class="text-right">{{ number_format($donation->amount, 0) }}</td>
                    <td>{{ $donation->is_regular ? 'Regular' : 'One-time' }}</td>
                    <td>{{ $donation->verified_at ? 'Verified' : 'Pending' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="15" style="text-align: center; padding: 20px;">No donations found</td>
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
