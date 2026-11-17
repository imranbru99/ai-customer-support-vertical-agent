<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->role?->canAccessAdmin() ?? false;
    }

    public function view(User $user, SupportTicket $ticket): bool
    {
        if ($user->role === UserRole::Client) {
            return $ticket->requester_id === $user->id;
        }

        return $user->role?->canAccessAdmin() ?? false;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [
            UserRole::SuperAdmin,
            UserRole::Admin,
            UserRole::Manager,
            UserRole::Staff,
            UserRole::Demo,
        ], true);
    }

    public function update(User $user, SupportTicket $ticket): bool
    {
        return $this->create($user);
    }

    public function delete(User $user, SupportTicket $ticket): bool
    {
        return in_array($user->role, [UserRole::SuperAdmin, UserRole::Admin], true);
    }
}
