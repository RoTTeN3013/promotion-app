<?php

namespace App\Http\Controllers;

use App\Models\Export;
use App\Services\ExportService;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    public function __construct(private ExportService $exportService) {}

    public function download(Export $export): BinaryFileResponse
    {
        abort_unless(Auth::guard('admin')->check(), 403);

        return $this->exportService->download($export);
    }
}
