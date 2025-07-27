<?php

namespace App\Http\Controllers;

use App\CustomConst\AllStatic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AmytStockController extends Controller
{
    public function stockList(Request $request)
    {
        if (strtolower($request->query('vendor', '')) === 'customer') {
            return app()->call('App\Http\Controllers\CustomerStockController@stockList', ['request' => $request]);
        }

        $perPage = $request->query('per_page', 10);
        $stocks = \App\Models\AmytStock::with('yarnCount:id,name')->paginate($perPage);
        return $stocks;
    }

    public function purchaseToStock($id)
    {
        try {
            DB::beginTransaction();
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
            $purchase->update();
            DB::commit();
            return response()->json([
                'success' => 'success',
                'message' => 'Stock updated successfully.'
            ]);
            //code...
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'success' => 'error',
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
