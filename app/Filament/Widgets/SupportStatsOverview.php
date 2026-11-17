<?php

namespace App\Filament\Widgets;

use App\Enums\TicketStatus;
use App\Models\CsatSurvey;
use App\Models\KnowledgeArticle;
use App\Models\SupportTicket;
use App\Services\ModuleManager;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SupportStatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $open = SupportTicket::query()
            ->whereNotIn('status', [TicketStatus::Resolved->value, TicketStatus::Closed->value])
            ->count();

        $published = KnowledgeArticle::query()->where('status', 'published')->count();

        $avgCsat = (float) (CsatSurvey::query()->avg('score') ?? 0);

        $hoursSaved = app(ModuleManager::class)->weeklyHoursSaved();

        return [
            Stat::make('Open tickets', $open)
                ->description('Active triage and agent queue')
                ->color('primary'),
            Stat::make('Published articles', $published)
                ->description('Knowledge deflection ready')
                ->color('info'),
            Stat::make('Avg CSAT', number_format($avgCsat, 1))
                ->description('Post-resolution satisfaction')
                ->color('success'),
            Stat::make('Weekly hours saved', $hoursSaved)
                ->description('Enabled modules ROI estimate')
                ->color('warning'),
        ];
    }
}