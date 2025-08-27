<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardDailyPurchase extends Model
{
    use HasFactory;

    protected $table = 'dashboard_daily_purchases';
    protected $primaryKey = 'date';
    public $incrementing = false;
    protected $keyType = 'date';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'total_purchases',
        'total_amount',
    ];
}
