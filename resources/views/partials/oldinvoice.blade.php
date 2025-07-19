<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AL-Makkah Yarn Thread - Stock Statement</title>
    <!-- Bootstrap 4 CSS -->
    <link href="{{ asset('admin-assets/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Tailwind CSS for additional styling (e.g., rounded corners, custom fonts) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: "Inter", sans-serif;
            background-color: #f8f9fa;
        }
        .container-fluid {
            max-width: 1200px;
            margin-top: 20px;
            margin-bottom: 20px;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 2.5rem;
            font-weight: bold;
            color: #343a40;
            margin-bottom: 5px;
        }
        .header h2 {
            font-size: 1.5rem;
            color: #6c757d;
            margin-top: 0;
        }
        .info-section {
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
        .table-responsive {
            margin-top: 20px;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
            padding: 8px;
            vertical-align: middle;
        }
        .table-bordered thead th {
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
        }
        .table-bordered tbody tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        .table-bordered tbody td:first-child {
            text-align: center; /* Center SL.No */
        }
        .table-bordered .highlight-row td {
            background-color: #fff3cd; /* Light yellow for highlighted row */
        }
        .table-bordered .highlight-cell {
            background-color: #ffc107; /* Orange for highlighted cell */
            font-weight: bold;
        }
        .total-row td {
            font-weight: bold;
            background-color: #e9ecef;
            text-align: right;
        }
        .total-summary-table {
            width: auto; /* Make table fit content */
            margin-top: 30px;
            margin-left: auto; /* Align to right */
            margin-right: 0;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            overflow: hidden; /* For rounded corners */
        }
        .total-summary-table td {
            padding: 8px 15px;
            border: none;
            font-weight: bold;
        }
        .total-summary-table tr:first-child td {
            border-bottom: 1px solid #dee2e6;
        }
        .total-summary-table tr:last-child td {
            border-top: 1px solid #dee2e6;
        }
        .total-summary-table td:first-child {
            background-color: #f2f2f2;
            text-align: left;
        }
        .total-summary-table td:last-child {
            text-align: right;
        }
        .footer-section {
            margin-top: 40px;
            font-size: 0.9rem;
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="container-fluid rounded-lg shadow-lg">
        <!-- Header Section -->
        <div class="header">
            <h1 class="text-gray-800">AL-Makkah Yarn Thread</h1>
            <h2 class="text-gray-600">Yarn Stock Statement & Delivery Info - January-25</h2>
        </div>

        <!-- Party and Date Info -->
        <div class="row info-section">
            <div class="col-md-6">
                <p><strong>Party Name:</strong> {{ $customer->name ?? 'N/A' }}</p>
                <p><strong>Address:</strong> {{ $customer->address ?? 'N/A' }}</p>
            </div>
            <div class="col-md-6 text-md-right">
                <p><strong>Date:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y') }}</p>
            </div>
        </div>

        <!-- Yarn Delivery Info Table -->
        <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th rowspan="2">SL.No.</th>
                        <th rowspan="2">Delivery Challan Date</th>
                        <th rowspan="2">Delivery Challan No.</th>
                        <th rowspan="2">Yarn Count</th>
                        <th rowspan="2">Delivery Quantity In KG</th>
                        <th colspan="3">Yarn Receive Info.</th>
                    </tr>
                    <tr>
                        <th>Yarn Receive Date</th>
                        <th>Yarn Receive Challan No.</th>
                        <th>Yarn Receive Qty. in KG</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $slNo = 0;
                    @endphp
                    @foreach($combinedData as $item)
                        @if($item['is_previous_statement'] ?? false)
                            <tr class="highlight-row">
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>From Previous Statement</td>
                                <td></td>
                                <td class="highlight-cell">{{ number_format($item['yarn_receive_qty_kg'], 1) }}</td>
                            </tr>
                        @else
                            <tr>
                                <td>{{ ++$slNo }}</td>
                                @if($item['type'] === 'delivery')
                                    <td>{{ $item['data']['delivery_challan_date'] }}</td>
                                    <td>{{ $item['data']['delivery_challan_no'] }}</td>
                                    <td>{{ $item['data']['yarn_count'] }}</td>
                                    <td>{{ number_format($item['data']['delivery_quantity_kg'], 1) }}</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                @elseif($item['type'] === 'receive')
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td>{{ $item['data']['yarn_receive_date'] }}</td>
                                    <td>{{ $item['data']['yarn_receive_challan_no'] }}</td>
                                    <td>{{ number_format($item['data']['yarn_receive_qty_kg'], 1) }}</td>
                                @endif
                            </tr>
                        @endif
                    @endforeach
                    <!-- Total Delivery Row -->
                    <tr class="total-row">
                        <td colspan="4" class="text-right">Total Delivery</td>
                        <td>{{ number_format($totalDelivery, 2) }}</td>
                        <td colspan="2" class="text-right">Total Receive</td>
                        <td>{{ number_format($totalReceive, 1) }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Total Summary Table -->
        <table class="total-summary-table table table-sm">
            <tbody>
                <tr>
                    <td>Total Receive</td>
                    <td>{{ number_format($totalReceive, 1) }}</td>
                </tr>
                <tr>
                    <td>Total Delivery</td>
                    <td>{{ number_format($totalDelivery, 2) }}</td>
                </tr>
                <tr>
                    <td>Present Stock</td>
                    <td>{{ number_format($presentStock, 2) }}</td>
                </tr>
            </tbody>
        </table>

        <!-- Footer Section -->
        <div class="footer-section">
            <p>Thanks & Regards</p>
        </div>
    </div>

    <!-- Bootstrap 4 JS and dependencies (optional, for interactive components if added later) -->
    <script src="{{ asset('admin-assets/assets/js/libs/jquery-3.1.1.min.js')}}"></script>
    <script src="{{ asset('admin-assets/bootstrap/js/bootstrap.min.js')}}"></script>
</body>
</html>
