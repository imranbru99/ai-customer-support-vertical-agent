<?php

namespace App\Filament\Resources\Macros;

use App\Filament\Resources\Macros\Pages\ManageMacros;
use App\Models\IndustryVertical;
use App\Models\Macro;
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

class MacroResource extends Resource
{
    protected static ?string $model = Macro::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCommandLine;

    protected static string|UnitEnum|null $navigationGroup = 'Productivity';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('macros');
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
            TextInput::make('shortcut')->maxLength(40),
            TextInput::make('subject')->maxLength(160),
            Textarea::make('body')->required()->columnSpanFull(),
            Toggle::make('is_active')->default(true),
            TextInput::make('usage_count')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable(),
                TextColumn::make('shortcut')->badge(),
                TextColumn::make('industry.name')->toggleable(),
                TextColumn::make('usage_count')->label('Uses'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageMacros::route('/')];
    }
}
