<?php

namespace App\Services;

use App\Models\Macro;

class MacroService
{
    public function render(Macro $macro, array $vars = []): string
    {
        $body = $macro->body;
        foreach ($vars as $key => $value) {
            $body = str_replace('{{'.$key.'}}', (string) $value, $body);
        }

        $macro->increment('usage_count');

        return $body;
    }
}
