<?php

namespace App\Filament\Resources\CsatSurveys\Pages;

use App\Filament\Resources\CsatSurveys\CsatSurveyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageCsatSurveys extends ManageRecords
{
    protected static string $resource = CsatSurveyResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
