<?php

namespace App\Filament\Resources\ContactMessages\Tables;

use App\Mail\ContactAnswerMail;
use App\Models\Answer;
use App\Models\ContactMessage;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Mail;

class ContactMessagesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('user_full_name')
                    ->label('Felhasználó')
                    ->formatStateUsing(fn (ContactMessage $record): string => $record->user ? ($record->user->first_name . ' ' . $record->user->last_name) : 'Ismeretlen felhasználó')
                    ->searchable(['user.first_name', 'user.last_name']),
                TextColumn::make('user.email')
                    ->label('E-mail')
                    ->searchable(),
                TextColumn::make('message')
                    ->label('Üzenet')
                    ->limit(60)
                    ->wrap(),
                TextColumn::make('status')
                    ->label('Státusz')
                    ->formatStateUsing(fn (?string $state): string => $state === 'answered' ? 'Megválaszolt' : 'Beérkezett')
                    ->badge()
                    ->color(fn (?string $state): string => $state === 'answered' ? 'success' : 'warning'),
                TextColumn::make('created_at')
                    ->label('Beérkezés ideje')
                    ->dateTime('Y-m-d H:i')
                    ->sortable(),
                TextColumn::make('answered_at')
                    ->label('Válasz ideje')
                    ->dateTime('Y-m-d H:i')
                    ->placeholder('-')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Státusz')
                    ->options([
                        'received' => 'Beérkezett',
                        'answered' => 'Megválaszolt',
                    ]),
                Filter::make('created_at_range')
                    ->label('Beérkezés dátuma')
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
            ])
            ->recordActions([
                ViewAction::make()
                    ->modalHeading('Kapcsolati üzenet részletei')
                    ->modalWidth('3xl')
                    ->modalContent(fn (ContactMessage $record) => view('filament.contact-messages.view', [
                        'record' => $record->loadMissing(['user', 'answer']),
                    ])),
                Action::make('answer')
                    ->label('Válasz küldése')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->visible(fn (ContactMessage $record): bool => $record->status !== 'answered' && $record->answer_id === null)
                    ->form([
                        Textarea::make('message')
                            ->label('Válasz üzenet')
                            ->required()
                            ->minLength(10)
                            ->maxLength(3000)
                            ->rows(8),
                    ])
                    ->action(function (array $data, ContactMessage $record): void {
                        $adminId = auth('admin')->id();

                        if (!$adminId) {
                            Notification::make()
                                ->title('Engedély megtagadva.')
                                ->danger()
                                ->send();

                            return;
                        }

                        $answer = Answer::create([
                            'admin_id' => $adminId,
                            'contact_message_id' => $record->id,
                            'message' => $data['message'],
                        ]);

                        $record->update([
                            'answer_id' => $answer->id,
                            'status' => 'answered',
                            'answered_at' => now(),
                        ]);

                        if ($record->user?->email) {
                            Mail::to($record->user->email)->send(new ContactAnswerMail($record->fresh('user'), $answer));
                        }

                        Notification::make()
                            ->title('A válasz mentve és elküldve.')
                            ->success()
                            ->send();
                    }),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
