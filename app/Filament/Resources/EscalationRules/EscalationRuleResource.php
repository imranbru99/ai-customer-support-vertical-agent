<?php

namespace App\Filament\Resources\EscalationRules;

use App\Filament\Resources\EscalationRules\Pages\ManageEscalationRules;
use App\Models\EscalationRule;
use App\Models\IndustryVertical;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class EscalationRuleResource extends Resource
{
    protected static ?string $model = EscalationRule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUpRight;

    protected static string|UnitEnum|null $navigationGroup = 'Support Desk';

    protected static ?int $navigationSort = 3;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('escalation_routing');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('industry_vertical_id')->label('Industry')->options(IndustryVertical::query()->pluck('name', 'id'))->searchable(),
            TextInput::make('name')->required(),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            TextInput::make('trigger_intent')->maxLength(80),
            Select::make('min_priority')->options(['low' => 'Low', 'medium' => 'Medium', 'high' => 'High', 'urgent' => 'Urgent'])->default('high'),
            TextInput::make('ai_score_threshold')->numeric()->step(0.01)->default(0.75),
            TextInput::make('target_queue')->default('tier-2'),
            TextInput::make('notify_role')->default('manager'),
            Toggle::make('is_active')->default(true),
            Toggle::make('auto_assign')->default(true),
            Textarea::make('instructions')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('trigger_intent')->badge(),
                TextColumn::make('min_priority')->badge(),
                TextColumn::make('target_queue'),
                TextColumn::make('ai_score_threshold')->label('AI min'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageEscalationRules::route('/')];
    }
}
