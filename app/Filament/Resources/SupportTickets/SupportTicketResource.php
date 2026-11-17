<?php

namespace App\Filament\Resources\SupportTickets;

use App\Enums\TicketPriority;
use App\Enums\TicketStatus;
use App\Filament\Resources\SupportTickets\Pages\ManageSupportTickets;
use App\Models\IndustryVertical;
use App\Models\SupportTicket;
use App\Models\User;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use UnitEnum;

class SupportTicketResource extends Resource
{
    protected static ?string $model = SupportTicket::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTicket;

    protected static string|UnitEnum|null $navigationGroup = 'Support';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('ticket_triage');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('number')->required()->unique(ignoreRecord: true),
            Select::make('industry_vertical_id')->label('Industry')->options(IndustryVertical::query()->pluck('name', 'id'))->searchable(),
            Select::make('assignee_id')->label('Assignee')->options(User::query()->pluck('name', 'id'))->searchable(),
            TextInput::make('subject')->required()->maxLength(200),
            Textarea::make('body')->columnSpanFull(),
            Select::make('channel')->options([
                'email' => 'Email', 'chat' => 'Chat', 'portal' => 'Portal', 'phone' => 'Phone',
            ])->default('email'),
            Select::make('priority')->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()]))->required(),
            Select::make('status')->options(collect(TicketStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()]))->required(),
            TextInput::make('intent')->maxLength(80),
            TextInput::make('ai_score')->numeric()->step(0.01),
            TextInput::make('customer_name')->maxLength(120),
            TextInput::make('customer_email')->email()->maxLength(150),
            Textarea::make('ai_summary')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->searchable()->sortable(),
                TextColumn::make('subject')->limit(40)->searchable(),
                TextColumn::make('industry.name')->label('Industry')->badge()->toggleable(),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('ai_score')->label('AI')->sortable(),
                TextColumn::make('assignee.name')->toggleable(),
                TextColumn::make('created_at')->since()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(collect(TicketStatus::cases())->mapWithKeys(fn ($s) => [$s->value => $s->label()])),
                SelectFilter::make('priority')->options(collect(TicketPriority::cases())->mapWithKeys(fn ($p) => [$p->value => $p->label()])),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageSupportTickets::route('/')];
    }
}
