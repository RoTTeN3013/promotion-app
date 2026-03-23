<?php

namespace App\Filament\Resources\Promotions\Schemas;

use App\Models\Product;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class PromotionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->minLength(8)
                    ->validationAttribute('promóció neve')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'min' => 'A(z) :attribute hossza legalább :min karakter kell legyen.',
                        'max' => 'A(z) :attribute hossza legfeljebb :max karakter lehet.',
                    ])
                    ->maxLength(128),
                DateTimePicker::make('date_from')
                    ->required()
                    ->validationAttribute('promóció kezdete')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                    ])
                    ->live(),
                DateTimePicker::make('date_to')
                    ->required()
                    ->validationAttribute('promóció vége')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'after_or_equal' => 'A(z) :attribute nem lehet korábbi, mint a promóció kezdete.',
                    ])
                    ->rules(['after_or_equal:date_from']),
                DateTimePicker::make('upload_from')
                    ->required()
                    ->validationAttribute('feltöltés kezdete')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                    ])
                    ->live(),
                DateTimePicker::make('upload_to')
                    ->required()
                    ->validationAttribute('feltöltés vége')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'after_or_equal' => 'A(z) :attribute nem lehet korábbi, mint a feltöltés kezdete.',
                    ])
                    ->rules(['after_or_equal:upload_from']),
                Select::make('product_ids')
                    ->label('Products')
                    ->options(fn () => Product::pluck('name', 'id'))
                    ->multiple()
                    ->required()
                    ->validationAttribute('termékek')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'min' => 'Legalább :min terméket kell kiválasztani.',
                    ])
                    ->minItems(1),
            ]);
    }
}
