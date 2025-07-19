<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerItem extends Model
{
    protected $fillable = [
        'customer_id',
        'auto_generated_id',
        'in_date',
        'challan_no',
        'document_link',
        'total_amount',
        'payment_status',
        'discount',
        'discount_type',
        'status',
        'additional_info',
        'description',
        'is_stocked'
    ];
    protected $casts = [
        'in_date' => 'date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function yarnCount()
    {
        return $this->belongsTo(Yarn::class);
    }

    public function customerStockHistories()
    {
        return $this->hasMany(CustomerStockHistory::class);
    }
}
