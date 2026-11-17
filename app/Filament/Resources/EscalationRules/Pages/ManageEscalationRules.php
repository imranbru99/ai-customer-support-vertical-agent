<?php

namespace App\Filament\Resources\EscalationRules\Pages;

use App\Filament\Resources\EscalationRules\EscalationRuleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageEscalationRules extends ManageRecords
{
    protected static string $resource = EscalationRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
