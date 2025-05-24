<?php

namespace App\Http\Controllers;

use App\Models\Yarn;
use Illuminate\Http\Request;

class YarnController extends Controller
{
    public function __construct(protected Yarn $model = new Yarn()) {}

    public function index()
    {
        return $this->model::get();
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        return $this->model::create($request->all());
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
