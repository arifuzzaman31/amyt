<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function __construct(private Supplier $model = new Supplier()) {}

    public function index(Request $request)
    {
        $perPage = $request->input('per_page') ?? 10;
        $services = $this->model::paginate($perPage);
        return $services;
    }

    public function search(Request $request)
{
    $query = $request->input('q');
    $page = $request->input('page', 1);
    
    $suppliers = Customer::where('name', 'like', "%{$query}%")
        ->paginate(20, ['*'], 'page', $page);
    
    return response()->json($suppliers);
}

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required'
        ]);

        return $this->model::create($request->all());
    }

    public function show($id)
    {
        return $this->model::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $expense = $this->model::findOrFail($id);
        $expense->update($request->all());
        return $expense;
    }

    public function destroy($id)
    {
        $this->model::destroy($id);
        return response()->noContent();
    }
}
