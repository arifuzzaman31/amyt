<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceItem extends Model
{
    protected $fillable = [
        'service_id',
        'yarn_count_id',
        'unit_attr_id',
        'quantity',
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
    
    protected $casts = [
        'quantity' => 'float',
        'unit_price' => 'float',
        'extra_quantity' => 'float',
        'extra_quantity_price' => 'float',
        'gross_weight' => 'float',
        'net_weight' => 'float',
        'bobin' => 'integer'
    ];

    // Mutators to convert empty strings to null for integer fields
    public function setUnitAttrIdAttribute($value)
    {
        $this->attributes['unit_attr_id'] = $value === '' ? null : $value;
    }

    public function setColorIdAttribute($value)
    {
        $this->attributes['color_id'] = $value === '' ? null : $value;
    }

    public function setWeightAttrIdAttribute($value)
    {
        $this->attributes['weight_attr_id'] = $value === '' ? null : $value;
    }

    public function setBobinAttribute($value)
    {
        $this->attributes['bobin'] = $value === '' ? null : $value;
    }

    // Relationships
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function yarnCount()
    {
        return $this->belongsTo(Yarn::class, 'yarn_count_id');
    }

    public function unitAttr()
    {
        return $this->belongsTo(Attribute::class, 'unit_attr_id');
    }

    public function color()
    {
        return $this->belongsTo(Attribute::class, 'color_id');
    }

    public function weightAttr()
    {
        return $this->belongsTo(Attribute::class, 'weight_attr_id');
    }

    // Accessors for formatted values
    public function getUnitPriceAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getQuantityAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getExtraQuantityAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getExtraQuantityPriceAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getGrossWeightAttribute($value)
    {
        return number_format($value, 2);
    }

    public function getNetWeightAttribute($value)
    {
        return number_format($value, 2);
    }

    // Calculated attributes
    public function getTotalPriceAttribute()
    {
        // Calculate total price: (quantity * unit_price) + (extra_quantity * extra_quantity_price)
        $quantity = $this->getOriginal('quantity');
        $unitPrice = $this->getOriginal('unit_price');
        $extraQuantity = $this->getOriginal('extra_quantity');
        $extraQuantityPrice = $this->getOriginal('extra_quantity_price');
        
        $regularPrice = $quantity * $unitPrice;
        $extraPrice = $extraQuantity * $extraQuantityPrice;
        
        return number_format($regularPrice + $extraPrice, 2);
    }
}
