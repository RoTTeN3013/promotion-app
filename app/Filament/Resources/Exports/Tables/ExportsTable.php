<?php

namespace App\Filament\Resources\Exports\Tables;

use App\Models\Export;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ExportsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('admin.username')
                    ->label('Létrehozta')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('date_from')
                    ->label('Dátumtól')
                    ->date()
                    ->sortable(),
                TextColumn::make('date_to')
                    ->label('Dátumig')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Exportálva ekkor')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                Action::make('download')
                    ->label('Letöltés')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Export $record): string => route('exports.download', $record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
