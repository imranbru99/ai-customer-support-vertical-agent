<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MarketingPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_marketing_routes_respond(): void
    {
        $this->seed();

        foreach (['/', '/features', '/modules', '/knowledge', '/triage', '/pricing', '/demo'] as $uri) {
            $this->get($uri)->assertOk();
        }
    }
}