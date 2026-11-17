<?php

namespace App\Filament\Widgets;

use App\Enums\TicketStatus;
use App\Models\SupportTicket;
use App\Services\ModuleManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class OpenTicketsWidget extends TableWidget
{
    protected static ?string $heading = 'Open tickets';

    protected int|string|array $columnSpan = 'full';

    public static function canView(): bool
    {
        return app(ModuleManager::class)->enabled('ticket_triage');
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                fn (): Builder => SupportTicket::query()
                    ->with(['industry', 'assignee'])
                    ->whereNotIn('status', [TicketStatus::Resolved->value, TicketStatus::Closed->value])
                    ->latest('created_at')
            )
            ->columns([
                TextColumn::make('number')->label('ID')->searchable(),
                TextColumn::make('subject')->limit(40),
                TextColumn::make('industry.name')->label('Industry')->badge()->placeholder('-'),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('assignee.name')->label('Assignee')->placeholder('Unassigned'),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->paginated([5, 10]);
    }
}