<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Yarn extends Model
{
    protected $table = "yarn_counts";
    protected $fillable = ['name','count','type'];
}
