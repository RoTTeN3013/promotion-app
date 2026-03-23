<?php

namespace App\Services;

use App\Models\Export;
use App\Models\Submission;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use ZipArchive;

class ExportService
{
    private const CHUNK_SIZE = 100;
    private const EXPORT_SUBDIR = 'exports';

    private const HEADER = ['Teljes név', 'Bankszámlaszám', 'Összeg (Ft)'];

    public function download(Export $export): BinaryFileResponse
    {
        $absolutePath = $this->generateAndPersistFile($export);
        $filename = basename($absolutePath);

        $contentType = str_ends_with(strtolower($filename), '.zip')
            ? 'application/zip'
            : 'text/csv; charset=utf-8';

        return response()->download($absolutePath, $filename, ['Content-Type' => $contentType]);
    }

    public function generateAndPersistFile(Export $export): string
    {
        $privateRoot = storage_path('app/private/');

        if ($export->file_path) {
            $existingAbsolutePath = $privateRoot . ltrim($export->file_path, '/');

            if (is_file($existingAbsolutePath)) {
                return $existingAbsolutePath;
            }
        }

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

        $tmpDir = storage_path('app/private/' . self::EXPORT_SUBDIR . '/');
        if (!is_dir($tmpDir)) {
            mkdir($tmpDir, 0755, true);
        }

        if (count($chunks) <= 1) {
            [$absolutePath, $relativePath] = $this->singleCsvFile($chunks[0] ?? [], $tmpDir, $export);
            $export->update(['file_path' => $relativePath]);

            return $absolutePath;
        }

        [$absolutePath, $relativePath] = $this->zippedCsvFile($chunks, $tmpDir, $export);
        $export->update(['file_path' => $relativePath]);

        return $absolutePath;
    }

    private function buildCsvString(array $rows): string
    {
        $handle = fopen('php://temp', 'r+');
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

    private function singleCsvFile(array $rows, string $tmpDir, Export $export): array
    {
        $filename = 'export_' . $export->id . '_' . now()->format('Y-m-d_His') . '.csv';
        $path = $tmpDir . $filename;
        file_put_contents($path, $this->buildCsvString($rows));

        return [$path, self::EXPORT_SUBDIR . '/' . $filename];
    }

    private function zippedCsvFile(array $chunks, string $tmpDir, Export $export): array
    {
        $zipName = 'export_' . $export->id . '_' . $export->date_from->format('Y-m-d') . '_' . $export->date_to->format('Y-m-d') . '.zip';
        $zipPath = $tmpDir . $zipName;

        $zip = new ZipArchive();
        $zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE);

        foreach ($chunks as $index => $chunk) {
            $csvName = 'export_part_' . ($index + 1) . '.csv';
            $zip->addFromString($csvName, $this->buildCsvString($chunk));
        }

        $zip->close();

        return [$zipPath, self::EXPORT_SUBDIR . '/' . $zipName];
    }
}
