<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function __construct(private Expense $model = new Expense()) {}

    public function index(Request $request)
    {
        $perPage = $request->input('per_page') ?? 10;
        $data = $this->model::with('expense_category:id,name')->paginate($perPage);
        return $data;
    }
    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required',
            'amount' => 'required'
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
