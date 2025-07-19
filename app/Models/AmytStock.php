<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AmytStock extends Model
{
    // use SoftDeletes;
    // one to one relationship with YarnCount
    protected $fillable = [
        'yarn_count_id',
        'quantity', // Current stock quantity
    ];
    /**
     * Get the yarn count associated with the stock.
     */

    public function yarnCount()
    {
        return $this->belongsTo(Yarn::class, 'yarn_count_id');
    }
    /**
     * Get the stock quantity.
     *
     * @return float
     */
    public function getQuantityAttribute($value)
    {
        return (float) $value; // Ensure quantity is always returned as a float
    }
    /**
     * Set the stock quantity.
     *
     * @param float $value
     */
    public function setQuantityAttribute($value)
    {
        $this->attributes['quantity'] = (float) $value; // Ensure quantity is always stored as a float
    }
    /**
     * Increment the stock quantity.
     *
     * @param float $amount
     * @return void
     */
    public function incrementQuantity(float $amount): void
    {
        $this->quantity += $amount;
        $this->save();
    }
    /**
     * Decrement the stock quantity.
     *
     * @param float $amount
     * @return void
     */
    public function decrementQuantity(float $amount): void
    {
        $this->quantity -= $amount;
        if ($this->quantity < 0) {
            $this->quantity = 0; // Prevent negative stock
        }
        $this->save();
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockHistory()
    {
       // return $this->hasMany(StockHistory::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function customerStocks()
    {
       // return $this->hasMany(CustomerStock::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function purchaseItems()
    {
        return $this->hasMany(PurchaseItem::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salesItems()
    {
       // return $this->hasMany(SalesItem::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockAdjustments()
    {
       // return $this->hasMany(StockAdjustment::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockTransfers()
    {
       // return $this->hasMany(StockTransfer::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockReturns()
    {
        //return $this->hasMany(StockReturn::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockDamages()
    {
       // return $this->hasMany(StockDamage::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockAdjustmentsHistory()
    {
       // return $this->hasMany(StockAdjustmentHistory::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockTransfersHistory()
    {
       // return $this->hasMany(StockTransferHistory::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockReturnsHistory()   
    {
       // return $this->hasMany(StockReturnHistory::class, 'yarn_count_id', 'yarn_count_id');
    }
    /**
     * Get the stock history for the yarn count.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stockDamagesHistory()
    {
      //  return $this->hasMany(StockDamageHistory::class, 'yarn_count_id', 'yarn_count_id');
    }
}
