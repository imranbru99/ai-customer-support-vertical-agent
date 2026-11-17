<?php

namespace App\Services;

use App\Enums\TicketStatus;
use App\Models\EscalationRule;
use App\Models\SupportTicket;
use App\Models\User;
use App\Enums\UserRole;

class EscalationRouter
{
    public function route(SupportTicket $ticket): ?EscalationRule
    {
        $query = EscalationRule::query()->where('is_active', true);

        if ($ticket->industry_vertical_id) {
            $query->where(function ($q) use ($ticket) {
                $q->whereNull('industry_vertical_id')
                    ->orWhere('industry_vertical_id', $ticket->industry_vertical_id);
            });
        }

        $rule = $query
            ->where(function ($q) use ($ticket) {
                $q->whereNull('trigger_intent')
                    ->orWhere('trigger_intent', $ticket->intent);
            })
            ->orderByDesc('ai_score_threshold')
            ->get()
            ->first(function (EscalationRule $rule) use ($ticket) {
                $priorityRank = ['low' => 1, 'medium' => 2, 'high' => 3, 'urgent' => 4];
                $ticketRank = $priorityRank[$ticket->priority?->value ?? 'low'] ?? 1;
                $minRank = $priorityRank[$rule->min_priority ?? 'medium'] ?? 2;

                return $ticketRank >= $minRank
                    && (float) $ticket->ai_score >= (float) $rule->ai_score_threshold;
            });

        if (! $rule) {
            return null;
        }

        $ticket->status = TicketStatus::Escalated;
        $ticket->escalated_at = now();

        if ($rule->auto_assign && ! $ticket->assignee_id) {
            $assignee = User::query()
                ->where('is_active', true)
                ->whereIn('role', [UserRole::Manager, UserRole::Admin, UserRole::Staff])
                ->orderBy('id')
                ->first();
            if ($assignee) {
                $ticket->assignee_id = $assignee->id;
            }
        }

        $ticket->save();

        return $rule;
    }
}
