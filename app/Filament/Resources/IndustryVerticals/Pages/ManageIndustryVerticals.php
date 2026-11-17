<?php

namespace App\Filament\Resources\IndustryVerticals\Pages;

use App\Filament\Resources\IndustryVerticals\IndustryVerticalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageIndustryVerticals extends ManageRecords
{
    protected static string $resource = IndustryVerticalResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
