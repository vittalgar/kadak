<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'generated_by_user_id',
        'pdf_path',
        'pdf_status',
        'campaign_id',
    ];

    /**
     * The attributes that should be cast.
     * This ensures dates are always Carbon instances.
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function generator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'generated_by_user_id');
    }

    public function qrTokens(): HasMany
    {
        return $this->hasMany(QrToken::class, 'batch_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }
}
