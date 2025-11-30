<?php

namespace App\Listeners;
use App\Events\ExpenseCreated;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateDashboardOnExpenseCreated implements ShouldQueue
{
    public function handle(ExpenseCreated $event)
    {
        $expense = $event->expense;
        $date = $expense->expense_date ?? $expense->created_at->toDateString();
        
        Log::info("Processing dashboard update for expense #{$expense->id} on date {$date}");
        
        try {
            DB::transaction(function () use ($date, $expense) {
                // Check if record exists for this date
                $exists = DB::table('dashboard_daily_expenses')
                    ->where('date', $date)
                    ->exists();
                
                if ($exists) {
                    // Update existing record
                    DB::table('dashboard_daily_expenses')
                        ->where('date', $date)
                        ->increment('total_expenses');
                    
                    DB::table('dashboard_daily_expenses')
                        ->where('date', $date)
                        ->increment('total_amount', $expense->amount);
                } else {
                    // Create new record
                    DB::table('dashboard_daily_expenses')->insert([
                        'date' => $date,
                        'total_expenses' => 1,
                        'total_amount' => $expense->amount,
                        'updated_at' => now(),
                    ]);
                }
            });
            
            Log::info("Dashboard updated successfully for expense #{$expense->id}");
        } catch (\Exception $e) {
            Log::error("Failed to update dashboard for expense #{$expense->id}: " . $e->getMessage());
            // Optionally rethrow to trigger queue retry
            throw $e;
        }
    }
    
    public function failed(ExpenseCreated $event, $exception)
    {
        Log::error("Dashboard update job failed for expense #{$event->expense->id}: " . $exception->getMessage());
    }
}