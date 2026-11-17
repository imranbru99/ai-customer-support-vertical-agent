<?php

namespace App\Services;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Models\SupportTicket;
use Illuminate\Support\Str;

class TicketTriageService
{
    public function triage(SupportTicket $ticket): SupportTicket
    {
        $text = Str::lower(($ticket->subject ?? '').' '.($ticket->body ?? ''));
        $intent = $this->detectIntent($text);
        $priority = $this->detectPriority($text, $intent);
        $score = $this->score($text, $intent, $priority);

        $ticket->fill([
            'intent' => $intent,
            'priority' => $priority,
            'ai_score' => $score,
            'ai_summary' => "Triaged as {$intent} with {$priority->value} priority (confidence ".number_format($score, 2).').',
            'status' => TicketStatus::Triaged,
            'tags' => array_values(array_unique(array_filter([
                $intent,
                $priority->value,
                $ticket->channel,
            ]))),
        ])->save();

        return $ticket->fresh();
    }

    protected function detectIntent(string $text): string
    {
        return match (true) {
            str_contains($text, 'refund') || str_contains($text, 'charge') => 'billing',
            str_contains($text, 'ship') || str_contains($text, 'delivery') => 'shipping',
            str_contains($text, 'login') || str_contains($text, 'password') => 'access',
            str_contains($text, 'bug') || str_contains($text, 'error') => 'technical',
            str_contains($text, 'cancel') || str_contains($text, 'unsubscribe') => 'retention',
            default => 'general',
        };
    }

    protected function detectPriority(string $text, string $intent): TicketPriority
    {
        if (str_contains($text, 'urgent') || str_contains($text, 'asap') || $intent === 'access') {
            return TicketPriority::Urgent;
        }

        if (in_array($intent, ['billing', 'technical'], true)) {
            return TicketPriority::High;
        }

        if ($intent === 'shipping') {
            return TicketPriority::Medium;
        }

        return TicketPriority::Low;
    }

    protected function score(string $text, string $intent, TicketPriority $priority): float
    {
        $base = match ($intent) {
            'billing' => 0.88,
            'access' => 0.91,
            'technical' => 0.84,
            'shipping' => 0.76,
            'retention' => 0.79,
            default => 0.62,
        };

        $boost = match ($priority) {
            TicketPriority::Urgent => 0.06,
            TicketPriority::High => 0.04,
            TicketPriority::Medium => 0.02,
            TicketPriority::Low => 0.0,
        };

        return min(0.99, round($base + $boost + (strlen($text) > 120 ? 0.02 : 0), 2));
    }
}
