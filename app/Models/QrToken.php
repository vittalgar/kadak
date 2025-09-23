<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QrToken extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token',
        'status',
        'prize',
        'batch_id',
        'product_selected_id',
        'name',
        'mobile',
        'city',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(QrBatch::class, 'batch_id');
    }
}
