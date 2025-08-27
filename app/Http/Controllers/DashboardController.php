<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\DashboardDailySale;
use App\Models\DashboardDailyExpense;
use App\Models\DashboardDailyService;
use App\Models\DashboardDailyCustomer;
use App\Models\DashboardDailyPurchase;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('dashboard_stats', now()->addMinutes(15), function () {
            return [
                'today_sales' => DashboardDailySale::where('date', today())->first(),
                'today_expenses' => DashboardDailyExpense::where('date', today())->first(),
                'today_services' => DashboardDailyService::where('date', today())->first(),
                'today_customers' => DashboardDailyCustomer::where('date', today())->first(),
                'today_purchases' => DashboardDailyPurchase::where('date', today())->first(),
                'monthly_revenue' => $this->getMonthlyRevenue(),
                'monthly_expenses' => $this->getMonthlyExpenses(),
                'monthly_services' => $this->getMonthlyServices(),
                'monthly_customers' => $this->getMonthlyCustomers(),
                'monthly_purchases' => $this->getMonthlyPurchases(),
            ];
        });

        return view('dashboard', compact('stats'));
    }

    private function getMonthlyRevenue()
    {
        return Cache::flexible('monthly_revenue', 
            [now()->addMinutes(5), now()->addHours(2)], 
            function () {
                return DashboardDailySale::selectRaw(
                    'DATE_FORMAT(date, "%Y-%m") as month, ' .
                    'SUM(total_revenue) as revenue'
                )
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get();
            }
        );
    }

    private function getMonthlyExpenses()
    {
        return Cache::flexible('monthly_expenses', 
            [now()->addMinutes(5), now()->addHours(2)], 
            function () {
                return DashboardDailyExpense::selectRaw(
                    'DATE_FORMAT(date, "%Y-%m") as month, ' .
                    'SUM(total_amount) as amount'
                )
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get();
            }
        );
    }

    private function getMonthlyServices()
    {
        return Cache::flexible('monthly_services', 
            [now()->addMinutes(5), now()->addHours(2)], 
            function () {
                return DashboardDailyService::selectRaw(
                    'DATE_FORMAT(date, "%Y-%m") as month, ' .
                    'SUM(total_revenue) as revenue'
                )
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get();
            }
        );
    }

    private function getMonthlyCustomers()
    {
        return Cache::flexible('monthly_customers', 
            [now()->addMinutes(5), now()->addHours(2)], 
            function () {
                return DashboardDailyCustomer::selectRaw(
                    'DATE_FORMAT(date, "%Y-%m") as month, ' .
                    'SUM(new_customers) as new_customers'
                )
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get();
            }
        );
    }

    private function getMonthlyPurchases()
    {
        return Cache::flexible('monthly_purchases', 
            [now()->addMinutes(5), now()->addHours(2)], 
            function () {
                return DashboardDailyPurchase::selectRaw(
                    'DATE_FORMAT(date, "%Y-%m") as month, ' .
                    'SUM(total_amount) as amount'
                )
                ->groupBy('month')
                ->orderBy('month', 'desc')
                ->take(12)
                ->get();
            }
        );
    }
}
