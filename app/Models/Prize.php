<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prize extends Model
{
    use HasFactory;

    protected $fillable = [
        'campaign_id',
        'name',
        'show',
        'parent_id',
        'category',
        'stock_oct',
        'stock_nov',
        'stock_dec',
        'is_active',
    ];

    /**
     * Each prize belongs to a single campaign.
     */
    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    /**
     * Get the parent prize category for this prize.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Prize::class, 'parent_id');
    }

    /**
     * Get the sub-prizes for this prize category.
     */
    public function subPrizes(): HasMany
    {
        return $this->hasMany(Prize::class, 'parent_id');
    }
}
