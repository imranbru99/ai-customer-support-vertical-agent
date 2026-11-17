<?php

namespace App\Models;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SupportTicket extends Model
{
    protected $fillable = [
        'number', 'industry_vertical_id', 'requester_id', 'assignee_id',
        'subject', 'body', 'channel', 'priority', 'status', 'intent',
        'ai_score', 'ai_summary', 'tags', 'escalated_at', 'resolved_at',
        'first_response_minutes', 'customer_email', 'customer_name',
    ];

    protected function casts(): array
    {
        return [
            'priority' => TicketPriority::class,
            'status' => TicketStatus::class,
            'ai_score' => 'float',
            'tags' => 'array',
            'escalated_at' => 'datetime',
            'resolved_at' => 'datetime',
            'first_response_minutes' => 'integer',
        ];
    }

    public function industry(): BelongsTo
    {
        return $this->belongsTo(IndustryVertical::class, 'industry_vertical_id');
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignee_id');
    }

    public function csatSurvey(): HasOne
    {
        return $this->hasOne(CsatSurvey::class);
    }

    public function aiSuggestions(): HasMany
    {
        return $this->hasMany(AiAnswerSuggestion::class);
    }
}
