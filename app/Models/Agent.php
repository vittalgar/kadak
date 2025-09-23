<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'contact_person',
        'location',
        'city',
        'state',
        'phone_number_1',
        'phone_number_2',
        'is_active',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function claims(): HasMany
    {
        return $this->hasMany(Claim::class);
    }
}
