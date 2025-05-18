<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    // use SoftDeletes;
    protected $fillable = ['customer_group_id','name','address',
    'email','phone','type','status',];

    public function customer_group()
    {
        return $this->belongsTo(CustomerGroup::class);
    }
}
