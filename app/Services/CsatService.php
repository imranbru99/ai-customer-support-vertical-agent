<?php

namespace App\Services;

use App\Models\CsatSurvey;
use App\Models\SupportTicket;

class CsatService
{
    public function capture(SupportTicket $ticket, int $score, ?string $comment = null, string $channel = 'email'): CsatSurvey
    {
        return CsatSurvey::query()->updateOrCreate(
            ['support_ticket_id' => $ticket->id],
            [
                'score' => max(1, min(5, $score)),
                'comment' => $comment,
                'channel' => $channel,
                'recovery_needed' => $score <= 3,
                'submitted_at' => now(),
            ]
        );
    }

    public function averageScore(): float
    {
        return round((float) CsatSurvey::query()->avg('score'), 2);
    }
}
