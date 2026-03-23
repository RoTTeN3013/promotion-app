<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Promotion;

class Export extends Model
{
    protected $fillable = [
        'date_from',
        'date_to',
        'exported_by',
        'promotion_id',
        'file_path'
    ];

    protected function casts(): array
    {
        return [
            'date_from' => 'date',
            'date_to'   => 'date',
        ];
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'exported_by');
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }
}
