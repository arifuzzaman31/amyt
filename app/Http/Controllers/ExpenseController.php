<?php

namespace App\Http\Controllers;

use App\Events\ExpenseCreated;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;

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
        try {
            $request->validate([
                'expense_date' => 'required',
                'amount' => 'required'
            ]);

            $expense = $this->model::create($request->all());
            Log::info("Dispatching ExpenseCreated event for expense #{$expense->id}");
            // ExpenseCreated::dispatch($expense);
            Event::dispatch(new ExpenseCreated($expense));
            return $expense;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['error' => $e->getMessage()], 400);
            // Handle invalid date format if necessary
        }
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
