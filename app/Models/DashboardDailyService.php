<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardDailyService extends Model
{
    use HasFactory;

    protected $table = 'dashboard_daily_services';
    protected $primaryKey = 'date';
    public $incrementing = false;
    protected $keyType = 'date';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'total_services',
        'total_revenue',
    ];
}
