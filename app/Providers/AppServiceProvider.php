<?php

namespace App\Providers;

use App\Models\SupportTicket;
use App\Policies\SupportTicketPolicy;
use App\Services\ModuleManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class);
    }

    public function boot(): void
    {
        Gate::policy(SupportTicket::class, SupportTicketPolicy::class);
    }
}
