<?php

namespace App\Filament\Resources\AiAnswerSuggestions\Pages;

use App\Filament\Resources\AiAnswerSuggestions\AiAnswerSuggestionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageAiAnswerSuggestions extends ManageRecords
{
    protected static string $resource = AiAnswerSuggestionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
