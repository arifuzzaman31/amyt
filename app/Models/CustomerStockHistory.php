<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerStockHistory extends Model
{
    protected $fillable = [
        'customer_item_id',
        'yarn_count_id',
        'quantity',
        'unit_price',
    ];
    public function customerItem()
    {
        return $this->belongsTo(CustomerItem::class);
    }
    public function yarnCount()
    {
        return $this->belongsTo(Yarn::class, 'yarn_count_id');
    }
}
