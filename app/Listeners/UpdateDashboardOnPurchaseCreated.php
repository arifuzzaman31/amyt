<?php

namespace App\Listeners;

use App\Events\PurchaseCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateDashboardOnPurchaseCreated implements ShouldQueue
{
    public function handle(PurchaseCreated $event)
    {
        $purchase = $event->purchase;
        $date = $purchase->created_at->toDateString();

        DB::transaction(function () use ($date, $purchase) {
            DB::table('dashboard_daily_purchases')
                ->where('date', $date)
                ->increment('total_purchases');
            
            DB::table('dashboard_daily_purchases')
                ->where('date', $date)
                ->increment('total_amount', $purchase->total_amount);
        });
    }
}
