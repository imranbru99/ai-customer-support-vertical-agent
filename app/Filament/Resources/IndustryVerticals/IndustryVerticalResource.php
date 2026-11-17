<?php

namespace App\Filament\Resources\IndustryVerticals;

use App\Filament\Resources\IndustryVerticals\Pages\ManageIndustryVerticals;
use App\Models\IndustryVertical;
use App\Services\ModuleManager;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
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

class IndustryVerticalResource extends Resource
{
    protected static ?string $model = IndustryVertical::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice;

    protected static string|UnitEnum|null $navigationGroup = 'Vertical Ops';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('industry_profiles');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->required()->maxLength(120),
            TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(140),
            TextInput::make('sector')->maxLength(80),
            TextInput::make('tone')->maxLength(80)->default('professional'),
            TextInput::make('sla_minutes')->numeric()->default(240),
            TextInput::make('deflection_target')->numeric()->step(0.01)->default(0.55),
            TextInput::make('knowledge_pack')->maxLength(120),
            Toggle::make('is_active')->default(true),
            Textarea::make('description')->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('sector')->badge(),
                TextColumn::make('sla_minutes')->label('SLA min'),
                TextColumn::make('deflection_target')->label('Deflection'),
                IconColumn::make('is_active')->boolean(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageIndustryVerticals::route('/')];
    }
}
