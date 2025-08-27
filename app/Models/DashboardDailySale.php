<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardDailySale extends Model
{
    use HasFactory;

    protected $table = 'dashboard_daily_sales';
    protected $primaryKey = 'date';
    public $incrementing = false;
    protected $keyType = 'date';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'total_orders',
        'total_revenue',
    ];
}
