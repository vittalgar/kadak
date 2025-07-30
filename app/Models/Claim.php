<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'token_used',
        'product_selected_id',
        'campaign_id',
        'retail_shop_id',
        'name',
        'mobile',
        'city',
        'latitude',
        'longitude',
        'prize_won',
        'status',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_selected_id');
    }

    public function collectionPoint(): BelongsTo
    {
        return $this->belongsTo(RetailShop::class, 'retail_shop_id');
    }
}
