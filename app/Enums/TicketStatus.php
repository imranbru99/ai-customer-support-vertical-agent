<?php

namespace App\Enums;

enum TicketStatus: string
{
    case New = 'new';
    case Triaged = 'triaged';
    case Waiting = 'waiting';
    case Escalated = 'escalated';
    case Resolved = 'resolved';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Triaged => 'Triaged',
            self::Waiting => 'Waiting',
            self::Escalated => 'Escalated',
            self::Resolved => 'Resolved',
            self::Closed => 'Closed',
        };
    }
}
