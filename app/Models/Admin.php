<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username',
        'email',
        'permissions',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'password' => 'hashed',
        ];
    }

    public function promotions(): HasMany
    {
        return $this->hasMany(Promotion::class, 'created_by');
    }

    public function exports(): HasMany
    {
        return $this->hasMany(Export::class, 'exported_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class, 'admin_id');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    //For filament formating, not stored in DB
    protected $appends = ['name'];

    public function getNameAttribute(): string
    {
        return $this->username;
    }
}
