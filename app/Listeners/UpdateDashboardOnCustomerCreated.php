<?php

namespace App\Listeners;

use App\Events\CustomerCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateDashboardOnCustomerCreated implements ShouldQueue
{
    public function handle(CustomerCreated $event)
    {
        $customer = $event->customer;
        $date = $customer->created_at->toDateString();

        DB::table('dashboard_daily_customers')
            ->where('date', $date)
            ->increment('new_customers');
    }
}
