<?php

namespace App\Filament\Resources\Macros\Pages;

use App\Filament\Resources\Macros\MacroResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageMacros extends ManageRecords
{
    protected static string $resource = MacroResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
