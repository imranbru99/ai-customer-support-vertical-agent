<?php

return [
    'brand' => 'SupportPilot AI',
    'accent' => '#2563EB',
    'demo' => [
        'email' => env('DEMO_EMAIL', 'demo@supportpilot.test'),
        'password' => env('DEMO_PASSWORD', 'password'),
    ],
    'default_sla_minutes' => (int) env('SUPPORT_DEFAULT_SLA_MINUTES', 240),
    'ai_deflection_target' => (float) env('SUPPORT_AI_DEFLECTION_TARGET', 0.55),
];
