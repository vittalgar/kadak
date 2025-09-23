<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Mail\SuspiciousClaimDetected;
use Illuminate\Support\Facades\Mail;

class Claim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_id',
        'token_used',
        'product_selected_id',
        'campaign_id',
        'agent_id',
        'name',
        'mobile',
        'city',
        'state',
        'latitude',
        'longitude',
        'prize_won',
        'status',
        'is_suspicious'
    ];

    protected static function booted(): void
    {
        // This 'created' event runs automatically after a new claim is saved.
        static::created(function (Claim $claim) {
            // Only run the check if we have coordinates.
            if ($claim->latitude && $claim->longitude) {

                // Define the threshold: e.g., 5 claims from the same location in 1 hour.
                $maxClaims = 5;
                $timeFrame = now()->subHour();

                // Count how many other claims share the exact same coordinates in the timeframe.
                $suspiciousCount = Claim::where('latitude', $claim->latitude)
                    ->where('longitude', $claim->longitude)
                    ->where('created_at', '>=', $timeFrame)
                    ->count();

                if ($suspiciousCount >= $maxClaims) {
                    // If the threshold is met, flag the claim.
                    $claim->is_suspicious = true;
                    $claim->save();

                    // Send an email alert to the admin.
                    // Make sure to set your admin email in the .env file (e.g., ADMIN_EMAIL=admin@kadak.com)
                    $adminEmail = config('mail.admin_email');
                    if ($adminEmail) {
                        Mail::to($adminEmail)->send(new SuspiciousClaimDetected($claim, $suspiciousCount));
                    }
                }
            }
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_selected_id');
    }

    public function campaign(): BelongsTo
    {
        return $this->belongsTo(Campaign::class);
    }

    public function collectionPoint(): BelongsTo
    {
        return $this->belongsTo(Agent::class, 'agent_id');
    }
    
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
