<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    // use SoftDeletes;
    protected $fillable = ['name','company_name','address','email','phone','type','status'];
}
