<?php

namespace App\Filament\Resources\Submissions\Schemas;

use App\Helpers\SubmissionStatusHelper;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_full_name')
                    ->label('Felhasználó')
                    ->formatStateUsing(fn ($record) => $record->user->first_name . ' ' . $record->user->last_name)
                    ->disabled(),
                Select::make('promotion_id')
                    ->relationship('promotion', 'name')
                    ->disabled(),
                Placeholder::make('doc_img_path')
                    ->label('Dokumentum')
                    ->content(function ($record) {
                        if (!$record || !$record->doc_img_path) {
                            return 'Nincs fájl feltöltve';
                        }
                        $url = asset('storage/' . $record->doc_img_path);
                        return new \Illuminate\Support\HtmlString(
                            '<a href="' . htmlspecialchars($url) . '" target="_blank" class="underline text-blue-600">Fájl megtekintése</a>'
                        );
                    }),
                TextInput::make('ap_no')
                    ->numeric()
                    ->disabled(),
                Placeholder::make('items')
                    ->label('Termékek')
                    ->content(function ($record) {
                        $items = $record?->items ?? [];
                        if (empty($items)) {
                            return 'Nincs termék';
                        }
                        $lines = collect($items)->map(function ($item) {
                            if (is_array($item)) {
                                $name  = $item['name']  ?? ('#' . ($item['id'] ?? '?'));
                                $price = isset($item['price']) ? ' – ' . $item['price'] . ' Ft' : '';
                                return $name . $price;
                            }
                            return '#' . $item;
                        })->implode(', ');
                        return new \Illuminate\Support\HtmlString(htmlspecialchars($lines));
                    })
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(SubmissionStatusHelper::statuses())
                    ->required()
                    ->default('submitted'),
                DatePicker::make('purchase_date')
                    ->disabled(),
                DatePicker::make('appeald_at')
                    ->disabled(),

            ]);
    }
}
