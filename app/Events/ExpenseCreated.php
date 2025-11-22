<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Expense;

class ExpenseCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Expense $expense;

    /**
     * Create a new event instance.
     */
    public function __construct(Expense $expense)
    {
        $this->expense = $expense;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}