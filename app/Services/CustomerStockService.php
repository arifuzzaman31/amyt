<?php
namespace App\Services;

use App\CustomConst\AllStatic;
use App\Models\CustomerItem;
use Illuminate\Support\Facades\DB;

class CustomerStockService 
{
    public function getAll($data)
    {
        $perPage = $data->query('per_page', 10);
        $stocks = \App\Models\CustomerStock::with(['yarnCount:id,name','customer:id,name'])->paginate($perPage);
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
            if (isset($data['document_file']) && $data['document_file'] instanceof \Illuminate\Http\UploadedFile) {
                $itemData['document_path'] = storeFile($data['document_file'], 'purchases/documents');
            }
            // Remove the file object itself, as we only want to store the path
            unset($itemData['document_file']);


            if (isset($data['image_file']) && $data['image_file'] instanceof \Illuminate\Http\UploadedFile) {
                $itemData['image_path'] = storeFile($data['image_file'], 'purchases/images');
            }
            // Remove the file object itself
            unset($itemData['image_file']);

            // Remove document_link if it's still being passed, to avoid conflict if schema has it
            unset($itemData['document_link']);

            if (!isset($itemData['discount_type'])) {
                $itemData['discount_type'] = null;
            }
            DB::beginTransaction();
            $stockItem = new CustomerItem();
            $stockItem->fill($itemData);
            $stockItem->save();

            if (!empty($customerItemsData) && is_array($customerItemsData)) {
                foreach ($customerItemsData as $itemData) {
                    $item = new \App\Models\CustomerStockHistory($itemData);
                    $stockItem->items()->save($item);
                }
            }
            $stockItem->load('items.yarn'); // Eager load items after creation
            DB::commit();
            return $stockItem;
        } catch (\Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    public function stockIn($id)
    {
        try {
            $result = \App\Models\CustomerItem::with('items')->find($id);
            if (!$result) {
                return res('error', 'No items found for this Stock.');
            }
            // Create or update the stock entry
            $result->items()->each(function ($item) {
                $stock = \App\Models\CustomerStock::firstOrNew(['yarn_count_id' => $item->yarn_count_id]);
                $stock->quantity += $item->quantity; // Increment the quantity
                $stock->save();
            });
            $result->is_stocked = AllStatic::ALL_STATIC['IS_STOCK']['STOCK'];
            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully.'
            ]);
            //code...
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
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