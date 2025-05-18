<?php

namespace App\Http\Controllers;

use App\Models\CustomerGroup;
use Illuminate\Http\Request;

class CustomerGroupController extends Controller
{
    public function index()
    {
        return CustomerGroup::get();
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        return CustomerGroup::create($request->all());
    }

    public function show($id)
    {
        return CustomerGroup::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $group = CustomerGroup::findOrFail($id);
        $group->update($request->all());
        return $group;
    }

    public function destroy($id)
    {
        CustomerGroup::destroy($id);
        return response()->noContent();
    }
}
