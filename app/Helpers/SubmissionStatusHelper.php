<?php

namespace App\Helpers;

class SubmissionStatusHelper
{
    public static function statuses(): array
    {
        return [
            'submitted' => 'Feltöltve',
            'under_review' => 'Ellenőrzés alatt',
            'need_data' => 'Információ szükséges',
            'updated' => 'Frissítve',
            'approved' => 'Elfogadva',
            'rejected' => 'Elutasítva',
            'appealed' => 'Fellebbezve',
            'paid' => 'Kifizetve',
        ];
    }

    public static function label(?string $status): string
    {
        if (!$status) {
            return '-';
        }

        return self::statuses()[$status] ?? $status;
    }
}
