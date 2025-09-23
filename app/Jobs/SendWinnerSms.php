<?php

namespace App\Jobs;

use App\Models\Claim;
use App\Services\AirtelSmsService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWinnerSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public Claim $claim;

    public function __construct(Claim $claim)
    {
        $this->claim = $claim;
    }

    public function handle(AirtelSmsService $smsService): void
    {
        // The job's only task is to call our dedicated service
        $smsService->sendWinnerNotification($this->claim);
    }
}
