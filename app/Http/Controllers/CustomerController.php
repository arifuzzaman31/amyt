<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        return Customer::with('customer_group:id,name')->get();
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        return Customer::create($request->all());
    }

    public function show($id)
    {
        return Customer::with('customer_stock')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $group = Customer::findOrFail($id);
        $group->update($request->all());
        return $group;
    }

    public function destroy($id)
    {
        Customer::destroy($id);
        return response()->noContent();
    }
}
