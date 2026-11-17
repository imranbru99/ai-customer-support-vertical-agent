<?php

namespace App\Filament\Resources\KnowledgeArticles;

use App\Filament\Resources\KnowledgeArticles\Pages\ManageKnowledgeArticles;
use App\Models\IndustryVertical;
use App\Models\KnowledgeArticle;
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

class KnowledgeArticleResource extends Resource
{
    protected static ?string $model = KnowledgeArticle::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static string|UnitEnum|null $navigationGroup = 'Support Desk';

    protected static ?int $navigationSort = 2;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('knowledge_base');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('industry_vertical_id')->label('Industry')->options(IndustryVertical::query()->pluck('name', 'id'))->searchable(),
            TextInput::make('title')->required()->maxLength(200),
            TextInput::make('slug')->required()->unique(ignoreRecord: true),
            TextInput::make('category')->maxLength(80),
            TextInput::make('locale')->default('en')->maxLength(10),
            Textarea::make('excerpt'),
            Textarea::make('body')->columnSpanFull(),
            Select::make('status')->options(['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'])->default('draft'),
            Toggle::make('is_public')->default(true),
            TextInput::make('view_count')->numeric()->default(0),
            TextInput::make('helpful_votes')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->limit(40),
                TextColumn::make('category')->badge(),
                TextColumn::make('industry.name')->label('Industry')->toggleable(),
                TextColumn::make('status')->badge(),
                TextColumn::make('helpful_votes')->label('Helpful'),
                IconColumn::make('is_public')->boolean(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageKnowledgeArticles::route('/')];
    }
}
