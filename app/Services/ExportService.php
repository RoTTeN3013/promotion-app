<?php

namespace App\Services;

use App\Models\Export;
use App\Models\Submission;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class ExportService
{
    private const CHUNK_SIZE = 100;

    private const HEADER = ['Teljes név', 'Bankszámlaszám', 'Összeg (Ft)'];

    public function exportCsv(Export $export): BinaryFileResponse
    {
        $submissions = Submission::query()
            ->with('user')
            ->where('promotion_id', $export->promotion_id)
            ->where('status', 'approved')
            ->whereDate('purchase_date', '>=', $export->date_from)
            ->whereDate('purchase_date', '<=', $export->date_to)
            ->get();

        $rows = $submissions->map(function (Submission $submission): array {
            $amount = collect($submission->items ?? [])
                ->sum(fn ($item) => is_array($item) ? (int) ($item['price'] ?? 0) : 0);

            return [
                trim(($submission->user?->first_name ?? '') . ' ' . ($submission->user?->last_name ?? '')),
                $submission->user?->bank_account_no ?? '',
                $amount,
            ];
        })->all();

        $chunks = array_chunk($rows, self::CHUNK_SIZE);

        $tmpDir = storage_path('app/private/exports/');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        if (count($chunks) <= 1) {
            return $this->singleCsvResponse($chunks[0] ?? [], $tmpDir);
        }

        return $this->zippedCsvResponse($chunks, $tmpDir, $export);
    }

    private function buildCsvString(array $rows): string
    {
        $handle = fopen('php://temp', 'r+');
        // UTF-8 BOM so Excel opens it correctly
        fwrite($handle, "\xEF\xBB\xBF");
        fputcsv($handle, self::HEADER);
        foreach ($rows as $row) {
            fputcsv($handle, $row);
        }
        rewind($handle);
        $content = stream_get_contents($handle);
        fclose($handle);
        return $content;
    }

    private function singleCsvResponse(array $rows, string $tmpDir): BinaryFileResponse
    {
        $filename = 'export_' . now()->format('Y-m-d_His') . '.csv';
        $path = $tmpDir . $filename;
        file_put_contents($path, $this->buildCsvString($rows));

        return response()
            ->download($path, $filename, ['Content-Type' => 'text/csv; charset=utf-8'])
            ->deleteFileAfterSend(true);
    }

    private function zippedCsvResponse(array $chunks, string $tmpDir, Export $export): BinaryFileResponse
    {
        $zipName = 'export_' . $export->date_from->format('Y-m-d') . '_' . $export->date_to->format('Y-m-d') . '.zip';
        $zipPath = $tmpDir . $zipName;

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($chunks as $index => $chunk) {
            $csvName = 'export_part_' . ($index + 1) . '.csv';
            $zip->addFromString($csvName, $this->buildCsvString($chunk));
        }

        $zip->close();

        return response()
            ->download($zipPath, $zipName, ['Content-Type' => 'application/zip'])
            ->deleteFileAfterSend(true);
    }
}
