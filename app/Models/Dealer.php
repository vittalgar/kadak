<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Dealer extends Model
{
    use HasFactory;

    protected $fillable = [
        'dealership_name',
        'contact_person',
        'phone_number',
        'city',
        'state',
        'is_active',
    ];

    /**
     * A dealer has one user account for logging in.
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'dealer_id', 'id');
    }

    /**
     * Automatically delete the associated user when a dealer is deleted.
     * This is an Eloquent model event.
     */
    protected static function boot()
    {
        parent::boot();
        static::deleting(function ($dealer) {
            $dealer->user()->delete();
        });
    }

    /**
     * A dealer is responsible for many retail shops.
     */
    public function retailShops(): HasMany
    {
        return $this->hasMany(RetailShop::class);
    }

    /**
     * Get all claims for this dealer through their retail shops.
     */
    public function claims(): HasManyThrough
    {
        return $this->hasManyThrough(Claim::class, RetailShop::class);
    }
}
