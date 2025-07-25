<?php

namespace App\Services;

use App\CustomConst\AllStatic;
use App\Models\CustomerItem;
use App\Models\CustomerStock;
use Illuminate\Support\Facades\DB;

class CustomerStockService
{
    public function getAll($data)
    {
        $perPage = $data['per_page'] ?? 10;
        $stocks = \App\Models\CustomerItem::with(['customer:id,name'])->paginate($perPage);
        return $stocks;
    }

    public function create($data)
    {
        $customerItemsData = [];
        if (isset($data['dataItem'])) {
            $customerItemsData = is_string($data['dataItem']) ? json_decode($data['dataItem'], true) : $data['dataItem'];
            unset($data['dataItem']); // Remove from main purchase data
        }

        $itemData = $data;
        try {
            //code...
            if (isset($data['document_link']) && $data['document_link'] instanceof \Illuminate\Http\UploadedFile) {
                $itemData['document_link'] = storeFile($data['document_link'], 'purchases/documents');
            }
            // Remove the file object itself, as we only want to store the path
            unset($itemData['document_link']);


            if (isset($data['image_file']) && $data['image_file'] instanceof \Illuminate\Http\UploadedFile) {
                $itemData['image_path'] = storeFile($data['image_file'], 'customer-item/images');
            }
            // Remove the file object itself
            unset($itemData['image_file']);

            // Remove document_link if it's still being passed, to avoid conflict if schema has it
            unset($itemData['document_link']);

            if (!isset($itemData['discount_type'])) {
                $itemData['discount_type'] = null;
            }
            if (!isset($itemData['in_date'])) {
                $itemData['in_date'] = now();
            }
            DB::beginTransaction();
            $stockItem = new CustomerItem();
            $stockItem->fill($itemData);
            $stockItem->save();

            if (!empty($customerItemsData) && is_array($customerItemsData)) {
                foreach ($customerItemsData as $itemData) {
                    $item = new \App\Models\CustomerStockHistory($itemData);
                    $stockItem->customerStockHistories()->save($item);
                }
            }
            $stockItem->load('customerStockHistories'); // Eager load items after creation
            DB::commit();
            return $stockItem;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    public function destroyItems($id)
    {
        DB::beginTransaction();
        try {
            $challan = CustomerItem::with('customerStockHistories')->findOrFail($id);

            foreach ($challan->customerStockHistories as $history) {
                // Reduce quantity from customer_stocks
                $customerStock = CustomerStock::where('customer_id', $challan->customer_id)
                    ->where('yarn_count_id', $history->yarn_count_id)
                    ->first();

                if ($customerStock) {
                    $customerStock->quantity -= $history->quantity;
                    if ($customerStock->quantity < 0) {
                        $customerStock->quantity = 0; // Prevent negative stock
                    }
                    $customerStock->save();
                }
                // Delete stock history
                $history->delete();
            }

            // Delete the main CustomerItem
            $challan->delete();

            DB::commit();

            return ['success' => true, 'message' => 'Challan and related data deleted successfully.'];
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th; // Let controller catch and return proper error
        }
    }

    public function itemStockIn($id)
    {
        try {
            $result = \App\Models\CustomerItem::with('customerStockHistories')->find($id);
            if (!$result) {
                return ['status' => false, 'message' => 'No items found'];
            }
            // Create or update the stock entry
            $result->customerStockHistories()->each(function ($item) use ($result) {
                $stock = \App\Models\CustomerStock::firstOrNew([
                    'yarn_count_id' => $item->yarn_count_id,
                    'customer_id' => $result->customer_id
                ]);
                $stock->customer_id = $result->customer_id;
                $stock->quantity += $item->quantity; // Increment the quantity

                $stock->save();
            });
            $result->is_stocked = AllStatic::ALL_STATIC['IS_STOCK']['STOCK'];
            $result->save(); // Save the updated customer item status
            return [
                'success' => true,
                'message' => 'Stock updated successfully.'
            ];
            //code...
        } catch (\Throwable $th) {
            return [
                'success' => false,
                'message' => $th->getMessage()
            ];
        }
    }
    public function stockOut($data, $id)
    {
        $stock = \App\Models\CustomerStock::findOrFail($id);
        $stock->delete();

        return response()->json(['message' => 'Stock removed successfully'], 200);
    }
    public function stockDetails($id)
    {
        $stock = \App\Models\CustomerStock::with(['yarnCount:id,name', 'customer:id,name'])->findOrFail($id);
        return response()->json($stock);
    }
    public function stockUpdate($data, $id)
    {
        $stock = \App\Models\CustomerStock::findOrFail($id);

        $data = $data->validate([
            'customer_id' => 'required|exists:customers,id',
            'yarn_count_id' => 'required|exists:yarn_counts,id',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $stock->customer_id = $data['customer_id'];
        $stock->yarn_count_id = $data['yarn_count_id'];
        $stock->quantity = $data['quantity'];
        $stock->unit_price = $data['unit_price'];
        $stock->save();

        return response()->json(['message' => 'Stock updated successfully', 'stock' => $stock], 200);
    }
    public function stockHistory($id)
    {
        $stockHistories = \App\Models\CustomerStockHistory::where('customer_item_id', $id)
            ->with(['yarnCount:id,name'])
            ->get();

        return response()->json($stockHistories);
    }
    public function stockHistoryDetails($id)
    {
        $stockHistory = \App\Models\CustomerStockHistory::with(['yarnCount:id,name'])
            ->findOrFail($id);

        return response()->json($stockHistory);
    }
    public function stockHistoryUpdate($data, $id)
    {
        $stockHistory = \App\Models\CustomerStockHistory::findOrFail($id);

        $data = $data->validate([
            'yarn_count_id' => 'required|exists:yarn_counts,id',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $stockHistory->yarn_count_id = $data['yarn_count_id'];
        $stockHistory->quantity = $data['quantity'];
        $stockHistory->unit_price = $data['unit_price'];
        $stockHistory->save();

        return response()->json(['message' => 'Stock history updated successfully', 'stock_history' => $stockHistory], 200);
    }
    public function stockHistoryDelete($id)
    {
        $stockHistory = \App\Models\CustomerStockHistory::findOrFail($id);
        $stockHistory->delete();

        return response()->json(['message' => 'Stock history deleted successfully'], 200);
    }
    public function stockHistoryCreate($data)
    {
        $data = $data->validate([
            'customer_item_id' => 'required|exists:customer_items,id',
            'yarn_count_id' => 'required|exists:yarn_counts,id',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $stockHistory = new \App\Models\CustomerStockHistory();
        $stockHistory->customer_item_id = $data['customer_item_id'];
        $stockHistory->yarn_count_id = $data['yarn_count_id'];
        $stockHistory->quantity = $data['quantity'];
        $stockHistory->unit_price = $data['unit_price'];
        $stockHistory->save();

        return response()->json(['message' => 'Stock history created successfully', 'stock_history' => $stockHistory], 201);
    }
}
