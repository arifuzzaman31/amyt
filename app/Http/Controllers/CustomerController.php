<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('limit') ?? 5; // You can adjust this as needed
        $search = $request->input('search');

        $query = Customer::with('customer_group:id,name');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('company_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
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
