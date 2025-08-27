<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardDailyExpense extends Model
{
    use HasFactory;

    protected $table = 'dashboard_daily_expenses';
    protected $primaryKey = 'date';
    public $incrementing = false;
    protected $keyType = 'date';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'total_expenses',
        'total_amount',
    ];
}
