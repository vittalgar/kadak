<?php

namespace App\Mail;

use App\Models\Claim;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SuspiciousClaimDetected extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Claim $claim;
    public int $suspiciousCount;

    public function __construct(Claim $claim, int $suspiciousCount)
    {
        $this->claim = $claim;
        $this->suspiciousCount = $suspiciousCount;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Suspicious Claim Activity Detected',
        );
    }

    public function content(): Content
    {
        // For simplicity, we'll use a Markdown view.
        return new Content(
            markdown: 'emails.suspicious-claim',
        );
    }
}
