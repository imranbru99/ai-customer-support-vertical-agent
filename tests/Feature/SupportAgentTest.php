<?php

namespace Tests\Feature;

use App\Models\SupportTicket;
use App\Services\ModuleManager;
use App\Services\TicketTriageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SupportAgentTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_renders(): void
    {
        $this->seed();

        $this->get('/')->assertOk();
    }

    public function test_modules_are_toggleable(): void
    {
        $this->seed();

        $manager = app(ModuleManager::class);
        $this->assertTrue($manager->enabled('ticket_triage'));
        $manager->set('catalog_scrape', true);
        $this->assertTrue($manager->enabled('catalog_scrape'));
    }

    public function test_ticket_triage_sets_intent(): void
    {
        $this->seed();

        $ticket = SupportTicket::query()->where('number', 'TK-1042')->firstOrFail();
        $this->assertNotNull($ticket->intent);
        $this->assertGreaterThan(0.5, (float) $ticket->ai_score);
    }
}
