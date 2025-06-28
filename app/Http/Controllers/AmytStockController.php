<?php

namespace App\Http\Controllers;

use App\CustomConst\AllStatic;
use Illuminate\Http\Request;

class AmytStockController extends Controller
{
    public function stockList(Request $request)
    {
        if (strtolower($request->query('vendor', '')) !== 'amyt') {
            return redirect()->route('customer.stock.list'); 
        }

        $perPage = $request->query('per_page', 10);
        $stocks = \App\Models\AmytStock::with('yarnCount:id,name')->paginate($perPage);
        return $stocks;
        return view('pages.stock.amyt_stock', compact('stocks'));
    }

    public function purchaseToStock($id)
    {
        try {
            $purchase = \App\Models\Purchase::with('items')->find($id);
            if (!$purchase) {
                return redirect()->back()->with('error', 'No purchase items found for this purchase.');
            }
            // Create or update the stock entry
            $purchase->items()->each(function ($item) {
                $stock = \App\Models\AmytStock::firstOrNew(['yarn_count_id' => $item->yarn_count_id]);
                $stock->quantity += $item->quantity; // Increment the quantity
                $stock->save();
            });
            $purchase->is_stocked = AllStatic::ALL_STATIC['IS_STOCK']['STOCK'];
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
}
