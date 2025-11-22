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
        
        // Group expenses by date and sum amounts
        $groupedData = $this->model::selectRaw('expense_date, SUM(amount) as total_amount, COUNT(*) as count')
            ->groupBy('expense_date')
            ->orderBy('expense_date', 'desc')
            ->paginate($perPage);
        
        return $groupedData;
    }
    public function store(Request $request)
    {
        try {
            $request->validate([
                'expense_date' => 'required',
                'amount' => 'required'
            ]);

            $data = $request->all();
            // Remove expense_category_id if not provided (making it optional)
            if (!isset($data['expense_category_id']) || $data['expense_category_id'] == 0) {
                unset($data['expense_category_id']);
            }

            $expense = $this->model::create($data);
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

    public function getByDate(Request $request)
    {
        $date = $request->input('date');
        
        if (!$date) {
            return response()->json(['error' => 'Date parameter is required'], 400);
        }

        $expenses = $this->model::where('expense_date', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'date' => $date,
            'expenses' => $expenses,
            'total_amount' => $expenses->sum('amount'),
            'count' => $expenses->count()
        ]);
    }
}
