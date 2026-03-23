<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required()
                    ->validationAttribute('keresztnév')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                    ]),
                TextInput::make('last_name')
                    ->required()
                    ->validationAttribute('vezetéknév')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                    ]),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->validationAttribute('e-mail cím')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'email' => 'A(z) :attribute mezőnek érvényes e-mail címnek kell lennie.',
                        'unique' => 'A(z) :attribute már foglalt.',
                    ]),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required()
                    ->validationAttribute('jelszó')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                    ]),
                TextInput::make('phone_no')
                    ->tel()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->validationAttribute('telefonszám')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'unique' => 'A(z) :attribute már foglalt.',
                    ]),
                TextInput::make('bank_account_no')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->validationAttribute('bankszámlaszám')
                    ->validationMessages([
                        'required' => 'A(z) :attribute mező kitöltése kötelező.',
                        'unique' => 'A(z) :attribute már foglalt.',
                    ]),
            ]);
    }
}
