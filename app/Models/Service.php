<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    // use SoftDeletes;
    protected $fillable = [
        'service_date',
        'customer_id',
        'invoice_no',
        'document_link',
        'description',
        'payment_status',
        'discount',
        'discount_type',
        'status',
        'addition_info'
    ];
    // protected $casts = [
    //     'service_date' => 'datetime'
    // ];
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
    public function items()
    {
        return $this->hasMany(ServiceItem::class);
    }
    public function getDocumentLinkAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
    public function getImagePathAttribute($value)
    {
        return $value ? asset('storage/' . $value) : null;
    }
}
