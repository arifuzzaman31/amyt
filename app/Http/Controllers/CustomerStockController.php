<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerStockController extends Controller
{
    public function stockList(Request $request)
    {
        $perPage = $request->query('per_page', 10);
        $stocks = \App\Models\CustomerStock::with(['yarnCount:id,name','customer:id,name'])->paginate($perPage);
        return $stocks;
    }
}
