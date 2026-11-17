<?php

namespace App\Filament\Resources\KnowledgeArticles\Pages;

use App\Filament\Resources\KnowledgeArticles\KnowledgeArticleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageKnowledgeArticles extends ManageRecords
{
    protected static string $resource = KnowledgeArticleResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
