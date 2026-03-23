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
                    ->validationAttribute('promóció')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                    ])
                    ->required(),
                DatePicker::make('date_from')
                    ->label('Dátumtól')
                    ->validationAttribute('dátumtól')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                    ])
                    ->required(),
                DatePicker::make('date_to')
                    ->label('Dátumig')
                    ->required()
                    ->validationAttribute('dátumig')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'after_or_equal' => 'A(z) :attribute nem lehet korábbi, mint a dátumtól mező.',
                    ])
                    ->afterOrEqual('date_from'),
            ]);
    }
}
