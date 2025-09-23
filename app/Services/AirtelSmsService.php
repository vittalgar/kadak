<?php

namespace App\Services;

use App\Models\Claim;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AirtelSmsService
{
    protected string $baseUrl = 'https://iqsms.airtel.in/api/v1/send-prepaid-sms';

    public function sendWinnerNotification(Claim $claim)
    {
        // Eager load the relationships to ensure data is available
        $claim->load(['collectionPoint']);

        // Construct the message by replacing {#var#} placeholders
        $message = "Dear {$claim->name},\n";
        $message .= "Your claim request is recorded\n";
        $message .= "Claim ID: {$claim->claim_id}\n";
        $message .= "Please contact our support team {$claim->collectionPoint->shop_name} at {$claim->collectionPoint->phone_number_1} for assistance\n";
        $message .= "Location: {$claim->collectionPoint->location}, {$claim->collectionPoint->city}\n";
        $message .= "Working hours: 11:00 AM to 7:00 PM\n\n";
        $message .= "Regards,\nBharath Beverages\n8985303030";

        $payload = [
            'customerId' => config('services.airtel.customer_id'),
            'destinationAddress' => [$claim->mobile],
            'dltTemplateId' => config('services.airtel.dlt_template_id'),
            'entityId' => config('services.airtel.entity_id'),
            'message' => $message,
            'messageType' => config('services.airtel.message_type'),
            'sourceAddress' => config('services.airtel.sender_id'),
        ];

        try {

            $response = Http::withBasicAuth(
                config('services.airtel.username'),
                config('services.airtel.password')
            )
                ->withHeaders([
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ])
                ->timeout(65)
                ->post($this->baseUrl, $payload);

            if ($response->successful()) {
                Log::info("Airtel SMS sent successfully to {$claim->mobile}.");
            } else {
                $errorDetails = json_encode($response->json() ?? $response->body());
                Log::error("Airtel SMS failed for {$claim->mobile}: " . $errorDetails);
            }
        } catch (\Exception $e) {
            Log::error("Airtel SMS exception for {$claim->mobile}: " . $e->getMessage());
        }
    }
}
