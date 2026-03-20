<?php

namespace App\Filament\Resources\Exports\Pages;

use App\Filament\Resources\Exports\ExportResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateExport extends CreateRecord
{
    protected static string $resource = ExportResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['exported_by'] = Auth::id();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return route('exports.download', $this->record);
    }
}
