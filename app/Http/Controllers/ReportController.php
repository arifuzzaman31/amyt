<?php

namespace App\Http\Controllers;

use App\Models\Yarn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function totalStockList(Request $request)
    {
        $yarns = Yarn::leftJoin('customer_stocks', 'yarn_counts.id', '=', 'customer_stocks.yarn_count_id')
            ->leftJoin('amyt_stocks', 'yarn_counts.id', '=', 'amyt_stocks.yarn_count_id')
            ->select(
                'yarn_counts.id',
                'yarn_counts.name',
                'yarn_counts.count',
                'yarn_counts.type',
                DB::raw('COALESCE(amyt_stocks.quantity, 0) as amyt_stock_quantity'),
                DB::raw('SUM(COALESCE(customer_stocks.quantity, 0)) as customer_stock_quantity')
            )
            ->when($request->search, function ($query) use ($request) {
                $query->where('yarn_counts.name', 'like', '%' . $request->search . '%');
            })
            ->groupBy('yarn_counts.id', 'yarn_counts.name', 'yarn_counts.count', 'yarn_counts.type', 'amyt_stocks.quantity')
            ->orderBy('yarn_counts.name', 'asc')
            ->paginate(20);

        return response()->json($yarns, 200);
    }
}
