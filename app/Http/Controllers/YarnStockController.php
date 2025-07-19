<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\CustomerItem;
use App\Models\CustomerStock;
use App\Models\CustomerStockHistory;
use App\Models\Service;
use App\Models\ServiceItem;
use App\Models\YarnCount; // Assuming you have a YarnCount model for yarn_counts table
use Illuminate\Support\Facades\DB;

class YarnStockController extends Controller
{
    public function showStockStatement(Request $request)
    {
        // Fetch customer details (assuming 'customers' table exists and Customer model)
        $customerId = $request->customerId;
        $customer = Customer::find($customerId);

        if (!$customer) {
            // Handle case where customer is not found
            abort(404, 'Customer not found.');
        }

        // --- 1. Fetch "From Previous Statement" (Initial Stock) ---
        // This assumes customer_stocks holds the initial or current balance for a customer.
        // You might need to adjust this logic based on how 'previous statement' is truly calculated.
        $previousStatementQty = CustomerStock::where('customer_id', $customerId)
                                            // ->where('date', '<', '2025-01-01') // Example: filter by date if needed
                                            ->sum('quantity'); // Sum all quantities for the customer

        // If 'previous statement' is a specific entry, you might fetch it differently.
        // For now, we'll sum all existing stock for the customer as a placeholder.
        // In a real application, this would likely be a calculated balance up to a certain date.

        // --- 2. Fetch Yarn Delivery Info ---
        $deliveryInfo = CustomerItem::with(['customerStockHistories.yarnCount'])
            ->where('customer_id', $customerId)
            // Filter by date range for January-25 if needed
            // ->whereBetween('in_date', ['2025-01-01', '2025-01-31'])
            ->orderBy('in_date', 'asc')
            ->get()
            ->map(function ($item) {
                // Sum quantities for each customer_item from its stock histories
                $totalQuantity = $item->customerStockHistories->sum('quantity');
                $yarnCountNames = $item->customerStockHistories->pluck('yarnCount.name')->unique()->implode(', ');

                return [
                    'delivery_challan_date' => $item->in_date->format('j-M-y'), // Format date as '2-Jan-25'
                    'delivery_challan_no' => $item->challan_no,
                    'yarn_count' => $yarnCountNames, // Concatenate unique yarn counts
                    'delivery_quantity_kg' => $totalQuantity,
                ];
            });

        // Calculate Total Delivery Quantity
        $totalDelivery = $deliveryInfo->sum('delivery_quantity_kg');


        // --- 3. Fetch Yarn Receive Info ---
        $receiveInfo = Service::with(['items.yarnCount'])
            ->where('customer_id', $customerId)
            // Filter by date range for January-25 if needed
            // ->whereBetween('service_date', ['2025-01-01', '2025-01-31'])
            ->orderBy('service_date', 'asc')
            ->get()
            ->map(function ($service) {
                // Sum quantities for each service from its service items
                $totalQuantity = $service->serviceItems->sum('quantity');
                return [
                    'yarn_receive_date' => $service->service_date->format('j-M-y'), // Format date as '5-Jan-25'
                    'yarn_receive_challan_no' => $service->invoice_no,
                    'yarn_receive_qty_kg' => $totalQuantity,
                ];
            });

        // Calculate Total Receive Quantity
        $totalReceive = $receiveInfo->sum('yarn_receive_qty_kg') + $previousStatementQty; // Include previous statement in total receive

        // Combine delivery and receive info for display, matching the table rows
        // This part requires careful merging if you want them interleaved as in the image.
        // For simplicity, we'll keep them separate and iterate in the view.
        // If a direct 1:1 mapping between delivery and receive is intended,
        // your schema might need a linking table or a more complex query.
        // For the provided table structure, it looks like separate lists that are then merged.

        // For the purpose of populating the table as shown, we'll create a combined array
        // and then sort it by date. This is a simplified approach.
        $combinedData = [];

        // Add previous statement as the first "receive" entry
        $combinedData[] = [
            'type' => 'receive',
            'date_sort' => null, // No specific date for sorting, will be first
            'is_previous_statement' => true,
            'yarn_receive_qty_kg' => $previousStatementQty,
        ];

        foreach ($deliveryInfo as $delivery) {
            $combinedData[] = [
                'type' => 'delivery',
                'date_sort' => \Carbon\Carbon::createFromFormat('j-M-y', $delivery['delivery_challan_date']),
                'data' => $delivery,
            ];
        }

        foreach ($receiveInfo as $receive) {
            $combinedData[] = [
                'type' => 'receive',
                'date_sort' => \Carbon\Carbon::createFromFormat('j-M-y', $receive['yarn_receive_date']),
                'data' => $receive,
            ];
        }

        // Sort the combined data by date for chronological display
        usort($combinedData, function($a, $b) {
            if ($a['is_previous_statement'] ?? false) return -1; // Keep previous statement at top
            if ($b['is_previous_statement'] ?? false) return 1;
            return $a['date_sort'] <=> $b['date_sort'];
        });

        // Calculate Present Stock
        $presentStock = $totalReceive - $totalDelivery;

        return view('partials.oldinvoice', compact(
            'customer',
            'combinedData',
            'totalDelivery',
            'totalReceive',
            'presentStock'
        ));
    }
}
