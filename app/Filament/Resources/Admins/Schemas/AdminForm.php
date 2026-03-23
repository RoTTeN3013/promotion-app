<?php

namespace App\Filament\Resources\Admins\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class AdminForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('username')
                    ->label('Felhasználónév')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->validationAttribute('felhasználónév')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'unique' => 'A(z) :attribute már foglalt.',
                    ])
                    ->maxLength(255),
                TextInput::make('email')
                    ->label('E-mail cím')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->validationAttribute('e-mail cím')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'email' => 'A(z) :attribute mezőnek érvényes e-mail címnek kell lennie.',
                        'unique' => 'A(z) :attribute már foglalt.',
                    ])
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Jelszó')
                    ->password()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->dehydrateStateUsing(fn (string $state): string => Hash::make($state))
                    ->minLength(5)
                    ->validationAttribute('jelszó')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'min' => 'A(z) :attribute hossza legalább :min karakter kell legyen.',
                    ])
                    ->maxLength(255),
            ]);
    }
}
