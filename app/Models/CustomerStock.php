<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerStock extends Model
{
    // use SoftDeletes;
    // one to one relationship with YarnCount
    protected $fillable = [
        'yarn_count_id',
        'quantity', // Current stock quantity
    ];
    /**
     * Get the yarn count associated with the stock.
     */
    public function yarnCount()
    {
        return $this->belongsTo(Yarn::class, 'yarn_count_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
