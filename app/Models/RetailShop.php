<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RetailShop extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealer_id',
        'shop_name',
        'address',
        'city',
        'state',
        'pincode',
        'is_active',
    ];

    /**
     * Each retail shop belongs to one dealer.
     */
    public function dealer(): BelongsTo
    {
        return $this->belongsTo(Dealer::class);
    }
}
