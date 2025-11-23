<?php

namespace App\Providers;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        \App\Events\ExpenseCreated::class => [
            \App\Listeners\UpdateDashboardOnExpenseCreated::class,
        ],
        \App\Events\ServiceCreated::class => [
            \App\Listeners\UpdateDashboardOnServiceCreated::class,
        ],
        \App\Events\CustomerCreated::class => [
            \App\Listeners\UpdateDashboardOnCustomerCreated::class,
        ],
        \App\Events\PurchaseCreated::class => [
            \App\Listeners\UpdateDashboardOnPurchaseCreated::class,
        ],
    ];
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        parent::boot();
    }
}
