<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Macro extends Model
{
    protected $fillable = [
        'industry_vertical_id', 'name', 'slug', 'shortcut', 'subject',
        'body', 'variables', 'usage_count', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'variables' => 'array',
            'usage_count' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(IndustryVertical::class, 'industry_vertical_id');
    }
}
