<?php

namespace App\Jobs;

use App\Models\QrBatch;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Throwable;

class GenerateQrPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $batchId;

    /**
     * The number of times the job may be attempted.
     * @var int
     */
    public $tries = 1;

    public function __construct(int $batchId)
    {
        $this->batchId = $batchId;
    }

    public function handle(): void
    {
        $batch = QrBatch::find($this->batchId);
        if (!$batch) {
            Log::error("GenerateQrPdfJob: Could not find QrBatch with ID {$this->batchId}");
            return;
        }

        $batch->update(['pdf_status' => 'processing']);
        Log::info("PDF generation STARTED for batch ID: {$this->batchId}");

        try {
            ini_set('memory_limit', '1G');
            ini_set('max_execution_time', 1800);

            $tokens = $batch->qrTokens;
            $pdf = Pdf::loadView('pdf.qr-codes', ['tokens' => $tokens, 'batchName' => $batch->name]);

            $fileName = 'exports/batch_' . $batch->id . '_' . str_replace(' ', '_', $batch->name) . '_qrcodes.pdf';

            Storage::disk('public')->put($fileName, $pdf->output());

            $batch->update([
                'pdf_path' => $fileName,
                'pdf_status' => 'completed'
            ]);

            Log::info("PDF generation COMPLETED for batch ID: {$this->batchId}");
        } catch (Throwable $e) {
            $this->fail($e); // Manually fail the job if an exception occurs
        }
    }

    /**
     * NEW: This method is called automatically if the job fails.
     */
    public function failed(Throwable $exception): void
    {
        // Log the exact error that caused the failure.
        Log::error("PDF generation FAILED for batch ID: {$this->batchId}. Error: " . $exception->getMessage());

        // Find the batch and update its status to 'failed'.
        $batch = QrBatch::find($this->batchId);
        if ($batch) {
            $batch->update(['pdf_status' => 'failed']);
        }
    }
}
