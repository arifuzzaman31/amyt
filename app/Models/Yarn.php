<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Yarn extends Model
{
    protected $table = "yarn_counts";
    protected $fillable = ['name','count','type'];

    public function customerStock()
    {
        return $this->hasMany(CustomerStock::class, 'yarn_count_id')->withDefault([
            'quantity' => 0,
        ]);
    }
    public function amytStock()
    {
        return $this->hasOne(AmytStock::class, 'yarn_count_id')->withDefault([
            'quantity' => 0,
        ]);
    }
}
