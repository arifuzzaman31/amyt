<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory;
    protected $fillable = ['customer_group_id','name','address','company_name',
    'email','phone','type','status',];

    public function customer_group()
    {
        return $this->belongsTo(CustomerGroup::class);
    }

    public function customer_stock()
    {
        return $this->hasMany(CustomerStock::class);
    }
    
    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }

}
