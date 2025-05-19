<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = ['expense_category_id','expense_date','amount','description'];

    public function expense_category()
    {
        return $this->belongsTo(ExpenseCategory::class);
    }
}
