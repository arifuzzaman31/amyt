<?php

namespace App\Http\Controllers;

use App\Models\Yarn;
use App\Services\CustomerStockService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class CustomerStockController extends Controller
{
    public function __construct(private CustomerStockService $stockService) {}
    
    public function stockList(Request $request)
    {
        return response()->json($this->stockService->getAll($request->all()));
    }

    public function create()
    {
        $yarnCounts = cache()->remember('yarn_counts', 60, function () {
            return Yarn::all(); // Fetch all yarn counts from the Yarn model
        });

        return view('pages.stock.create_stock', compact('yarnCounts'));
    }

    public function stockIn(Request $request)
    {
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'in_date' => 'required',
            'dataItem.*.yarn_count_id' => 'required|exists:yarn_counts,id',
            'dataItem.*.quantity' => 'required|numeric|min:0',
            'dataItem.*.unit_price' => 'required|numeric|min:0'
        ]);
        Log::info($request->all());
        try {
            $data = $request->all();
            if ($request->hasFile('document_file')) {
                $data['document_file'] = $request->file('document_file');
            }
            if ($request->hasFile('image_file')) {
                $data['image_file'] = $request->file('image_file');
            }
            
            $purchase = $this->stockService->create($data);
            // return response()->json(, Response::HTTP_CREATED);
            return response()->json(['message' => 'Customer Stock Created Successful!','data' => $purchase], Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['message' => $th->getMessage()], Response::HTTP_BAD_REQUEST);
        }
    }
    public function loadTostockIn($id)
    {
        try {
            $purchase = $this->stockService->itemStockIn($id);
            return response()->json($purchase, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], Response::HTTP_NOT_FOUND);
        }
    }

    public function stockOut(Request $request, $id)
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
    public function stockUpdate(Request $request, $id)
    {
        $stock = \App\Models\CustomerStock::findOrFail($id);
        
        $validatedData = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'yarn_count_id' => 'required|exists:yarn_counts,id',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $stock->customer_id = $validatedData['customer_id'];
        $stock->yarn_count_id = $validatedData['yarn_count_id'];
        $stock->quantity = $validatedData['quantity'];
        $stock->unit_price = $validatedData['unit_price'];
        $stock->save();

        return response()->json(['message' => 'Stock updated successfully', 'stock' => $stock], 200);
    }
    public function destroyChallan($id)
    {
        try {
            $purchase = $this->stockService->destroyItems($id);
            return response()->json($purchase, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], Response::HTTP_NOT_FOUND);
        }
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
    public function stockHistoryUpdate(Request $request, $id)
    {
        $stockHistory = \App\Models\CustomerStockHistory::findOrFail($id);
        
        $validatedData = $request->validate([
            'yarn_count_id' => 'required|exists:yarn_counts,id',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $stockHistory->yarn_count_id = $validatedData['yarn_count_id'];
        $stockHistory->quantity = $validatedData['quantity'];
        $stockHistory->unit_price = $validatedData['unit_price'];
        $stockHistory->save();

        return response()->json(['message' => 'Stock history updated successfully', 'stock_history' => $stockHistory], 200);
    }
    public function stockHistoryDelete($id)
    {
        $stockHistory = \App\Models\CustomerStockHistory::findOrFail($id);
        $stockHistory->delete();

        return response()->json(['message' => 'Stock history deleted successfully'], 200);
    }
    public function stockHistoryCreate(Request $request)
    {
        $validatedData = $request->validate([
            'customer_item_id' => 'required|exists:customer_items,id',
            'yarn_count_id' => 'required|exists:yarn_counts,id',
            'quantity' => 'required|numeric|min:0',
            'unit_price' => 'required|numeric|min:0',
        ]);

        $stockHistory = new \App\Models\CustomerStockHistory();
        $stockHistory->customer_item_id = $validatedData['customer_item_id'];
        $stockHistory->yarn_count_id = $validatedData['yarn_count_id'];
        $stockHistory->quantity = $validatedData['quantity'];
        $stockHistory->unit_price = $validatedData['unit_price'];
        $stockHistory->save();

        return response()->json(['message' => 'Stock history created successfully', 'stock_history' => $stockHistory], 201);
    }

}
