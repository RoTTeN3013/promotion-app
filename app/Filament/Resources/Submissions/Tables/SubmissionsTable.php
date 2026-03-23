<?php

namespace App\Filament\Resources\Submissions\Tables;

use App\Helpers\SubmissionStatusHelper;
use App\Models\Submission;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Forms\Components\DatePicker;
use Illuminate\Database\Eloquent\Builder;

class SubmissionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('user_full_name')
                    ->label('Felhasználó')
                    ->getStateUsing(fn (Submission $record): string => $record->user ? ($record->user->first_name . ' ' . $record->user->last_name) : '-')
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('user', function (Builder $userQuery) use ($search): void {
                            $userQuery
                                ->where('first_name', 'like', "%{$search}%")
                                ->orWhere('last_name', 'like', "%{$search}%");
                        });
                    }),
                TextColumn::make('promotion.name')
                    ->searchable(),
                TextColumn::make('status')
                    ->formatStateUsing(fn (?string $state): string => SubmissionStatusHelper::label($state))
                    ->searchable(),
                TextColumn::make('purchase_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('appeald_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('message')
                    ->label('Üzenet')
                    ->limit(50)
                    ->tooltip(fn (Submission $record): ?string => $record->message ? htmlspecialchars($record->message) : null)
                    ->visible(fn () => true),
            ])
            ->filters([
                SelectFilter::make('promotion_id')
                    ->label('Promóció')
                    ->relationship('promotion', 'name')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('status')
                    ->label('Státusz')
                    ->options(SubmissionStatusHelper::statuses()),
                Filter::make('promotion_dates')
                    ->label('Feltöltés dátuma')
                    ->form([
                        DatePicker::make('date_from')->label('Feltöltés dátuma (tól)'),
                        DatePicker::make('date_to')->label('Feltöltés dátuma (ig)'),
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
                            $indicators[] = 'Kezdet: ' . \Carbon\Carbon::parse($data['date_from'])->format('Y.m.d');
                        }
                        if ($data['date_to'] ?? null) {
                            $indicators[] = 'Vége: ' . \Carbon\Carbon::parse($data['date_to'])->format('Y.m.d');
                        }
                        return $indicators;
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
