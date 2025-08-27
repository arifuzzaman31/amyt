<?php
// app/Console/Commands/UpdateDashboardStats.php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\Expense;
use App\Models\Service;
use App\Models\Customer;
use App\Models\Purchase;
use Carbon\Carbon;

class UpdateDashboardStats extends Command
{
    protected $signature = 'dashboard:update-stats {--refresh}';
    protected $description = 'Update dashboard summary tables';

    public function handle()
    {
        $this->info('Updating dashboard stats...');
        
        $dates = $this->option('refresh') 
            ? [now()->toDateString(), now()->subDay()->toDateString()] 
            : [now()->toDateString()];

        foreach ($dates as $date) {
            $this->updateDailySales($date);
            $this->updateDailyExpenses($date);
            $this->updateDailyServices($date);
            $this->updateDailyCustomers($date);
            $this->updateDailyPurchases($date);
        }

        $this->info('Dashboard stats updated successfully!');
    }

    private function updateDailySales($date)
    {
        $totalOrders = Service::whereDate('created_at', $date)->count();
        $totalRevenue = Service::whereDate('created_at', $date)->sum('amount');

        DB::table('dashboard_daily_sales')->updateOrInsert(
            ['date' => $date],
            [
                'total_orders' => $totalOrders,
                'total_revenue' => $totalRevenue,
                'updated_at' => now(),
            ]
        );
    }

    private function updateDailyExpenses($date)
    {
        $totalExpenses = Expense::whereDate('created_at', $date)->count();
        $totalAmount = Expense::whereDate('created_at', $date)->sum('amount');

        DB::table('dashboard_daily_expenses')->updateOrInsert(
            ['date' => $date],
            [
                'total_expenses' => $totalExpenses,
                'total_amount' => $totalAmount,
                'updated_at' => now(),
            ]
        );
    }

    private function updateDailyServices($date)
    {
        $totalServices = Service::whereDate('created_at', $date)->count();
        $totalRevenue = Service::whereDate('created_at', $date)->sum('price');

        DB::table('dashboard_daily_services')->updateOrInsert(
            ['date' => $date],
            [
                'total_services' => $totalServices,
                'total_revenue' => $totalRevenue,
                'updated_at' => now(),
            ]
        );
    }

    private function updateDailyCustomers($date)
    {
        $newCustomers = Customer::whereDate('created_at', $date)->count();
        $activeCustomers = Customer::whereDate('last_active_at', $date)->count();

        DB::table('dashboard_daily_customers')->updateOrInsert(
            ['date' => $date],
            [
                'new_customers' => $newCustomers,
                'active_customers' => $activeCustomers,
                'updated_at' => now(),
            ]
        );
    }

    private function updateDailyPurchases($date)
    {
        $totalPurchases = Purchase::whereDate('created_at', $date)->count();
        $totalAmount = Purchase::whereDate('created_at', $date)->sum('total_amount');

        DB::table('dashboard_daily_purchases')->updateOrInsert(
            ['date' => $date],
            [
                'total_purchases' => $totalPurchases,
                'total_amount' => $totalAmount,
                'updated_at' => now(),
            ]
        );
    }
}