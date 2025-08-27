<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DashboardDailyCustomer extends Model
{
    use HasFactory;

    protected $table = 'dashboard_daily_customers';
    protected $primaryKey = 'date';
    public $incrementing = false;
    protected $keyType = 'date';
    public $timestamps = false;

    protected $fillable = [
        'date',
        'new_customers',
        'active_customers',
    ];
}
