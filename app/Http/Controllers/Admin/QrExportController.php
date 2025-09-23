<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateQrPdfJob;
use App\Models\QrBatch;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipArchive;

class QrExportController extends Controller
{
    /**
     * Dispatch a job to generate the PDF in the background.
     */
    public function exportPdf(QrBatch $batch)
    {
        // Prevent re-generating if it's already completed or processing
        if (in_array($batch->pdf_status, ['completed', 'processing', 'queued'])) {
            return back()->with('error', 'PDF generation is already completed or in progress for this batch.');
        }

        // Set the status to 'queued' and dispatch the job
        $batch->update(['pdf_status' => 'queued']);
        GenerateQrPdfJob::dispatch($batch->id);

        return back()->with('success', 'PDF generation has been queued. The page will auto-refresh, and a download link will appear when it is complete.');
    }

    /**
     * Download the already generated PDF.
     */
    public function downloadPdf(QrBatch $batch)
    {
        if ($batch->pdf_status !== 'completed' || !$batch->pdf_path || !Storage::disk('public')->exists($batch->pdf_path)) {
            return back()->with('error', 'The PDF for this batch is not available for download.');
        }
        return Storage::disk('public')->download($batch->pdf_path);
    }

    /**
     * Generate and download a CSV of all tokens in the batch.
     */
    public function exportCsv(QrBatch $batch)
    {
        $tokens = $batch->qrTokens()->pluck('token')->implode("\n");
        $fileName = 'batch_' . $batch->id . '_tokens.csv';
        return response($tokens, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
        ]);
    }

    /**
     * Generate a ZIP file containing individual SVG files for each QR code.
     */
    public function exportSvgZip(QrBatch $batch)
    {
        $zipFileName = 'batch_' . $batch->id . '_svgs.zip';
        $zipPath = storage_path('app/temp/' . $zipFileName);

        // Ensure temp directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            foreach ($batch->qrTokens as $qrToken) {
                $svgContent = QrCode::size(200)->format('svg')->generate(env('QR_CODE_BASE_URL') . '/spin/' . $qrToken->token);
                $zip->addFromString($qrToken->token . '.svg', $svgContent);
            }
            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
