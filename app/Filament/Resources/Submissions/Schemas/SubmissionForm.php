<?php

namespace App\Filament\Resources\Submissions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SubmissionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->relationship('user', 'id')
                    ->required(),
                Select::make('promotion_id')
                    ->relationship('promotion', 'name')
                    ->required(),
                TextInput::make('doc_img_path')
                    ->required(),
                TextInput::make('ap_no')
                    ->required()
                    ->numeric(),
                Textarea::make('items')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('status')
                    ->required()
                    ->default('submitted'),
                DatePicker::make('purchase_date')
                    ->required(),
                DatePicker::make('appeald_at'),
            ]);
    }
}
