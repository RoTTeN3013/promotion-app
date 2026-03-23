<?php

namespace App\Filament\Resources\Answers\Tables;

use App\Models\Answer;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AnswersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('admin.username')
                    ->label('Admin')
                    ->searchable(),
                TextColumn::make('contactMessage.id')
                    ->label('Kapcsolati üzenet ID')
                    ->sortable(),
                TextColumn::make('message')
                    ->label('Válasz')
                    ->limit(80)
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label('Létrehozva')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
            ])
            ->filters([
                Filter::make('created_at_range')
                    ->label('Dátum')
                    ->form([
                        DatePicker::make('date_from')->label('Dátum tól'),
                        DatePicker::make('date_to')->label('Dátum ig'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['date_from'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '>=', $date)
                            )
                            ->when(
                                $data['date_to'] ?? null,
                                fn (Builder $q, $date) => $q->whereDate('created_at', '<=', $date)
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];

                        if ($data['date_from'] ?? null) {
                            $indicators[] = 'Tól: ' . \Carbon\Carbon::parse($data['date_from'])->format('Y.m.d');
                        }

                        if ($data['date_to'] ?? null) {
                            $indicators[] = 'Ig: ' . \Carbon\Carbon::parse($data['date_to'])->format('Y.m.d');
                        }

                        return $indicators;
                    }),
                SelectFilter::make('admin_id')
                    ->label('Admin')
                    ->relationship('admin', 'username')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('Válasz részletei')
                    ->modalWidth('3xl')
                    ->modalContent(fn (Answer $record) => view('filament.answers.view', [
                        'record' => $record->loadMissing(['admin', 'contactMessage']),
                    ])),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
