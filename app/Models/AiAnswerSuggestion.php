<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiAnswerSuggestion extends Model
{
    protected $fillable = [
        'support_ticket_id', 'knowledge_article_id', 'draft_reply',
        'confidence', 'model_provider', 'status', 'accepted_at',
    ];

    protected function casts(): array
    {
        return [
            'confidence' => 'float',
            'accepted_at' => 'datetime',
        ];
    }

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(SupportTicket::class, 'support_ticket_id');
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(KnowledgeArticle::class, 'knowledge_article_id');
    }
}
