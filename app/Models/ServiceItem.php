<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    protected $fillable = [
        'service_id',
        'yarn_count_id',
        'quantity',
        'unit_attr_id',
        'unit_price',
        'extra_quantity',
        'extra_quantity_price',
        'color_id',
        'gross_weight',
        'net_weight',
        'weight_attr_id',
        'bobin',
        'remark'
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function getTotalPriceAttribute($value)
    {
        return number_format($value, 2);
    }
}
