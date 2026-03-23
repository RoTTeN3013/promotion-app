<?php

namespace App\Filament\Resources\Submissions\Pages;

use App\Mail\SubmissionStatusChangedMail;
use App\Filament\Resources\Submissions\SubmissionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditSubmission extends EditRecord
{
    protected static string $resource = SubmissionResource::class;

    protected ?string $previousStatus = null;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->previousStatus = $this->record?->status;

        return $data;
    }

    protected function afterSave(): void
    {
        $currentStatus = $this->record?->status;

        if ($this->previousStatus === $currentStatus) {
            return;
        }

        $submission = $this->record->loadMissing('user');

        if ($submission->user?->email) {
            Mail::to($submission->user->email)->send(new SubmissionStatusChangedMail($submission));
        }
    }
}
