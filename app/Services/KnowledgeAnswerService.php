<?php

namespace App\Services;

use App\Models\AiAnswerSuggestion;
use App\Models\KnowledgeArticle;
use App\Models\SupportTicket;
use Illuminate\Support\Str;

class KnowledgeAnswerService
{
    public function suggest(SupportTicket $ticket): ?AiAnswerSuggestion
    {
        $article = KnowledgeArticle::query()
            ->when($ticket->industry_vertical_id, fn ($q) => $q->where('industry_vertical_id', $ticket->industry_vertical_id))
            ->where('status', 'published')
            ->where(function ($q) use ($ticket) {
                $needle = Str::lower($ticket->intent ?? 'general');
                $q->whereRaw('lower(category) like ?', ["%{$needle}%"])
                    ->orWhereRaw('lower(title) like ?', ["%{$needle}%"]);
            })
            ->orderByDesc('helpful_votes')
            ->first()
            ?? KnowledgeArticle::query()->where('status', 'published')->orderByDesc('view_count')->first();

        if (! $article) {
            return null;
        }

        $draft = "Hi {$ticket->customer_name},\n\nThanks for reaching out about \"{$ticket->subject}\". "
            ."Based on our {$article->title} guidance: ".Str::limit(strip_tags($article->excerpt ?: $article->body), 220)
            ."\n\nPlease let us know if this resolves your request.\n\n— SupportPilot AI";

        return AiAnswerSuggestion::query()->create([
            'support_ticket_id' => $ticket->id,
            'knowledge_article_id' => $article->id,
            'draft_reply' => $draft,
            'confidence' => min(0.97, max(0.55, (float) $ticket->ai_score - 0.05)),
            'model_provider' => 'laravel-ai-hub',
            'status' => 'suggested',
        ]);
    }
}
