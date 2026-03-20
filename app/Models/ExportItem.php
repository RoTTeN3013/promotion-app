<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExportItem extends Model
{
    protected $fillable = [
        'export_id',
    ];

    public function export(): BelongsTo
    {
        return $this->belongsTo(Export::class);
    }
}
