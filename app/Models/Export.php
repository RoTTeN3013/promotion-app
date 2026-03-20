<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Export extends Model
{
    protected $fillable = [
        'exported_by',
    ];

    public function admin(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'exported_by');
    }

    public function exportItems(): HasMany
    {
        return $this->hasMany(ExportItem::class);
    }
}
