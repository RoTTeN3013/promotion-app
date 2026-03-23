<?php

namespace App\Filament\Resources\Exports;

use App\Filament\Resources\Exports\Pages\CreateExport;
use App\Filament\Resources\Exports\Pages\ListExports;
use App\Filament\Resources\Exports\Schemas\ExportForm;
use App\Filament\Resources\Exports\Tables\ExportsTable;
use App\Models\Export;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExportResource extends Resource
{
    protected static ?string $model = Export::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Exportok';

    protected static ?string $modelLabel = 'Export';

    protected static ?string $pluralModelLabel = 'Exportok';

    public static function form(Schema $schema): Schema
    {
        return ExportForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExportsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExports::route('/'),
            'create' => CreateExport::route('/create'),
        ];
    }
}
