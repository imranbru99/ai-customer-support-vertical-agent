<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CsatSurvey extends Model
{
    protected $fillable = [
        'support_ticket_id', 'score', 'comment', 'channel', 'recovery_needed',
        'recovered_at', 'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'integer',
            'recovery_needed' => 'boolean',
            'recovered_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }
}
