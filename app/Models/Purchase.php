<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'supplier_id',
        'purchase_date',
        'challan_no',
        // 'dataItem' will be handled by creating PurchaseItem records
        'description',
        'discount',
        'discount_type',
        // 'document_link', // We will use document_path and image_path
        'payment_status',
        'status',
        'total_amount',
        'document_path', // Path for stored document
        'image_path',    // Path for stored image
        'auto_generated_id',
        'additional_info',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'purchase_date' => 'date',
        'dataItem' => 'array', // If you want to automatically cast JSON to array and vice-versa
    ];

    /**
     * Get the supplier that owns the purchase.
     */
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Get the items for the purchase.
     */
    public function items()
    {
        return $this->hasMany(PurchaseItem::class);
    }
}
