<?php

namespace App\Filament\Resources\AiAnswerSuggestions;

use App\Filament\Resources\AiAnswerSuggestions\Pages\ManageAiAnswerSuggestions;
use App\Models\AiAnswerSuggestion;
use App\Models\KnowledgeArticle;
use App\Models\SupportTicket;
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
use Filament\Tables\Table;
use UnitEnum;

class AiAnswerSuggestionResource extends Resource
{
    protected static ?string $model = AiAnswerSuggestion::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSparkles;

    protected static string|UnitEnum|null $navigationGroup = 'AI Assist';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'AI Answers';

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('ai_answers');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('support_ticket_id')->label('Ticket')->options(SupportTicket::query()->pluck('number', 'id'))->required()->searchable(),
            Select::make('knowledge_article_id')->label('Article')->options(KnowledgeArticle::query()->pluck('title', 'id'))->searchable(),
            Textarea::make('draft_reply')->required()->columnSpanFull(),
            TextInput::make('confidence')->numeric()->step(0.01),
            TextInput::make('model_provider')->default('laravel-ai-hub'),
            Select::make('status')->options(['suggested' => 'Suggested', 'accepted' => 'Accepted', 'rejected' => 'Rejected'])->default('suggested'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket.number')->label('Ticket'),
                TextColumn::make('article.title')->label('Article')->limit(30)->toggleable(),
                TextColumn::make('confidence')->sortable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('model_provider')->toggleable(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageAiAnswerSuggestions::route('/')];
    }
}
