<?php

namespace App\Filament\Resources\Exports\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ExportForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('exported_by')
                    ->required()
                    ->numeric(),
            ]);
    }
}
