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
                    ->maxLength(128),
                DateTimePicker::make('date_from')
                    ->required()
                    ->live(),
                DateTimePicker::make('date_to')
                    ->required()
                    ->rules(['after_or_equal:date_from']),
                DateTimePicker::make('upload_from')
                    ->required()
                    ->live(),
                DateTimePicker::make('upload_to')
                    ->required()
                    ->rules(['after_or_equal:upload_from']),
                Select::make('product_ids')
                    ->label('Products')
                    ->options(fn () => Product::pluck('name', 'id'))
                    ->multiple()
                    ->required()
                    ->minItems(1),
            ]);
    }
}
