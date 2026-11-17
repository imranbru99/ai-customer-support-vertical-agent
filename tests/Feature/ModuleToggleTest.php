<?php

namespace Tests\Feature;

use App\Services\ModuleManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ModuleToggleTest extends TestCase
{
    use RefreshDatabase;

    public function test_core_modules_enabled_after_seed(): void
    {
        $this->seed();

        $manager = app(ModuleManager::class);

        $this->assertTrue($manager->enabled('ticket_triage'));
        $this->assertTrue($manager->enabled('knowledge_base'));
        $this->assertFalse($manager->enabled('catalog_scrape'));
    }

    public function test_module_toggle_persists_in_database(): void
    {
        $this->seed();

        $manager = app(ModuleManager::class);

        $manager->set('ai_answers', false);
        $this->assertFalse($manager->enabled('ai_answers'));

        $manager->set('ai_answers', true);
        $this->assertTrue($manager->enabled('ai_answers'));
    }

    public function test_weekly_hours_saved_reflects_enabled_modules(): void
    {
        $this->seed();

        $hours = app(ModuleManager::class)->weeklyHoursSaved();

        $this->assertGreaterThan(50, $hours);
    }
}