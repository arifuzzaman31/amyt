<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AmytStockController extends Controller
{
    public function stockList(Request $request)
    {
        // Fetch all stocks with pagination
        // if query parameter 'vendor' is amyt, then use AmytStock model
        // Otherwise, you can handle CustomerStock or other models as needed
        if ($request->query('vendor') !== 'amyt') {
            return redirect()->route('customer.stock.list'); // Redirect to customer stock list if not amyt
        }
        // Fetch stocks from AmytStock model with pagination
        // You can adjust the perPage value based on your requirements
        // Default to 10 items per page
        $request->validate([
            'per_page' => 'integer|min:1|max:100', // Validate per_page query parameter
        ]);
            // Fetch stocks with pagination
            // Assuming you have a model named AmytStock
            // Adjust the model name and relationships as per your application structure
        \App\Models\AmytStock::withoutGlobalScopes(); // If you have global scopes, you might want to disable them
        \App\Models\AmytStock::withoutTrashed(); // If you are using soft deletes and want to ignore them
        \App\Models\AmytStock::withoutAppends(); // If you have appends that you don't want to include in the response
        
        $perPage = $request->query('per_page', 10); // Default to 10 items per page
        $stocks = \App\Models\AmytStock::with('yarnCount')->paginate($perPage);
        
        return view('pages.stock.amyt_stock', compact('stocks'));
    }
}
