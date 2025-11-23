<?php

namespace App\Listeners;

use App\Events\ServiceCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;

class UpdateDashboardOnServiceCreated implements ShouldQueue
{
    public function handle(ServiceCreated $event)
    {
        $service = $event->service;
        $date = $service->created_at->toDateString();

        DB::transaction(function () use ($date, $service) {
            DB::table('dashboard_daily_services')
                ->where('date', $date)
                ->increment('total_services');
            
            DB::table('dashboard_daily_services')
                ->where('date', $date)
                ->increment('total_revenue', $service->price);
        });
    }
}