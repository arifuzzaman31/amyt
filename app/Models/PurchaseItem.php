<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'yarn_count_id', // Assuming this corresponds to a Yarn model or similar
        'quantity',
        'unit_price',
    ];

    /**
     * Get the purchase that owns the item.
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Get the yarn associated with the purchase item.
     * Assuming you have a Yarn model and yarn_counts table.
     */
    public function yarn()
    {
        return $this->belongsTo(Yarn::class, 'yarn_count_id');
    }
}