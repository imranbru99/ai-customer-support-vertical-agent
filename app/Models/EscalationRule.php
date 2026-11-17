<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EscalationRule extends Model
{
    protected $fillable = [
        'industry_vertical_id', 'name', 'slug', 'trigger_intent', 'min_priority',
        'ai_score_threshold', 'target_queue', 'notify_role', 'is_active',
        'auto_assign', 'instructions',
    ];

    protected function casts(): array
    {
        return [
            'ai_score_threshold' => 'float',
            'is_active' => 'boolean',
            'auto_assign' => 'boolean',
        ];
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(IndustryVertical::class, 'industry_vertical_id');
    }
}
