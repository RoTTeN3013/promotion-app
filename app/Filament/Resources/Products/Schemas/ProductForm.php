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
                    ->validationAttribute('termék neve')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'min' => 'A(z) :attribute hossza legalább :min karakter kell legyen.',
                        'max' => 'A(z) :attribute hossza legfeljebb :max karakter lehet.',
                    ])
                    ->maxLength(75),
                TextInput::make('description')
                ->label('Leírás')
                    ->nullable()
                    ->validationAttribute('leírás')
                    ->validationMessages([
                        'min' => 'A(z) :attribute hossza legalább :min karakter kell legyen.',
                        'max' => 'A(z) :attribute hossza legfeljebb :max karakter lehet.',
                    ])
                    ->rules(['nullable', 'min:25', 'max:125']),
                TextInput::make('price')
                    ->label('Termék ára')
                    ->integer()
                    ->required()
                    ->validationAttribute('termék ára')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'integer' => 'A(z) :attribute mező csak egész szám lehet.',
                        'min' => 'A(z) :attribute értéke legalább :min kell legyen.',
                    ])
                    ->minValue(1),
            ]);
    }
}
