<?php
namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Purchase;

class PurchaseCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Purchase $purchase;

    /**
     * Create a new event instance.
     */
    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return [];
    }
}