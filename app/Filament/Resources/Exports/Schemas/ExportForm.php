<?php

namespace App\Filament\Resources\Exports\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class ExportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('promotion_id')
                    ->label('Promóció')
                    ->relationship('promotion', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                DatePicker::make('date_from')
                    ->label('Dátumtól')
                    ->required(),
                DatePicker::make('date_to')
                    ->label('Dátumig')
                    ->required()
                    ->afterOrEqual('date_from'),
            ]);
    }
}
