<?php

namespace App\Http\Controllers;

use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function __construct(protected Attribute $model = new Attribute()) {}

    public function index()
    {
        return $this->model::orderBy('type')->get();
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255'
            ]);
    
            $result = $this->model::create($request->all());
            if (!$result) {
                return response()->json(['status' => false, 'message' => 'Attribute not added'], 500);
            }
            // If the attribute is successfully created, return a success response
            return response()->json(['status' => true, 'message' => 'Attribute added Successfully'], 201);
            //code...
        } catch (\Throwable $th) {
            return response()->json(['status' => false, 'message' => $th->getMessage()], 500);   
        }
    }

    public function show($id)
    {
        return $this->model::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $group = $this->model::findOrFail($id);
        $group->update($request->all());
        return $group;
    }

    public function destroy($id)
    {
        $this->model::destroy($id);
        return response()->noContent();
    }
}
