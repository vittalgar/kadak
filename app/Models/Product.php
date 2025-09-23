<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku', 'is_active'];

    public function campaigns(): BelongsToMany
    {
        return $this->belongsToMany(Campaign::class);
    }
}
