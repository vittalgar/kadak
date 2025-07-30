<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QrBatch;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Jobs\GenerateQrPdfJob;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipArchive;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class QrExportController extends Controller
{
    // exportCsv() method remains the same
    public function exportCsv(QrBatch $batch)
    {
        $fileName = 'batch_' . $batch->id . '_' . str_replace(' ', '_', $batch->name) . '_tokens.csv';
        $tokens = $batch->qrTokens()->pluck('token');

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function () use ($tokens) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['token']); // Header row
            foreach ($tokens as $token) {
                fputcsv($file, [$token]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // exportPdf() method remains the same
    public function exportPdf(QrBatch $batch)
    {
        try {
            Log::info("--- PDF Export process started for Batch ID: {$batch->id} ---");

            Log::info("Step 1: Updating status to 'queued'...");
            $batch->update(['pdf_status' => 'queued']);
            Log::info("Step 1 SUCCESS: Status updated to 'queued'.");

            Log::info("Step 2: Dispatching GenerateQrPdfJob...");
            GenerateQrPdfJob::dispatch($batch->id);
            Log::info("Step 2 SUCCESS: Job dispatched for Batch ID: {$batch->id}.");

            return back()->with('success', 'Your PDF export has been started. The status will update automatically when complete.');
        } catch (\Throwable $e) {
            // If any part of the process fails, log the exact error.
            Log::error("!!! PDF Export FAILED for Batch ID: {$batch->id} in Controller !!!");
            Log::error("Error Message: " . $e->getMessage());
            Log::error("File: " . $e->getFile() . " on line " . $e->getLine());

            // Also update the batch status to 'failed' so the user sees it.
            $batch->update(['pdf_status' => 'failed']);

            return back()->with('error', 'Could not start the PDF export. Please check the system logs.');
        }
    }

    /**
     * Download the generated PDF file.
     */
    public function downloadPdf(QrBatch $batch)
    {
        // Check if the job is completed and the file path exists
        if ($batch->pdf_status === 'completed' && $batch->pdf_path) {

            // Get the full path to the file in the storage directory
            $filePath = Storage::disk('public')->path($batch->pdf_path);

            // Check if the file physically exists at that path
            if (file_exists($filePath)) {
                return response()->download($filePath);
            }
        }

        // If not ready or file is missing, redirect back with an error
        return back()->with('error', 'The PDF file is not yet ready or could not be found.');
    }

    /**
     * Export the QR codes as a ZIP archive of SVG files.
     */
    public function exportSvgZip(QrBatch $batch)
    {
        ini_set('max_execution_time', 300);

        $zip = new ZipArchive();
        $fileName = 'batch_' . $batch->id . '_' . str_replace(' ', '_', $batch->name) . '_svg.zip';
        $filePath = storage_path('app/' . $fileName);

        if ($zip->open($filePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
            die("An error occurred creating the ZIP file.");
        }

        $tokens = $batch->qrTokens;

        foreach ($tokens as $qrToken) {
            $svgContent = QrCode::size(300)
                ->format('svg')
                ->generate(route('spin', ['token' => $qrToken->token]));

            $humanReadableText = "<text x='150' y='320' font-family='sans-serif' font-size='10' text-anchor='middle' fill='black'>{$qrToken->token}</text>";
            $svgContentWithText = str_replace('</svg>', $humanReadableText . '</svg>', $svgContent);

            $zip->addFromString($qrToken->token . '.svg', $svgContentWithText);
        }

        $zip->close();

        return response()->download($filePath)->deleteFileAfterSend(true);
    }
}
