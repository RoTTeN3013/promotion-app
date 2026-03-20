<?php

namespace App\Filament\Resources\Products\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Termék neve')
                    ->required()
                    ->minLength(3)
                    ->maxLength(75),
                TextInput::make('description')
                ->label('Leírás')
                    ->nullable()
                    ->rules(['nullable', 'min:25', 'max:125']),
                TextInput::make('price')
                    ->label('Termék ára')
                    ->integer()
                    ->required()
                    ->minValue(1),
            ]);
    }
}
