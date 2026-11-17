<?php

namespace App\Filament\Resources\ModuleSettings;

use App\Filament\Resources\ModuleSettings\Pages\ManageModuleSettings;
use App\Models\ModuleSetting;
use BackedEnum;
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

class ModuleSettingResource extends Resource
{
    protected static ?string $model = ModuleSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPuzzlePiece;

    protected static string|UnitEnum|null $navigationGroup = 'Platform';

    protected static ?int $navigationSort = 80;

    protected static ?string $navigationLabel = 'Modules';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('key')->required()->unique(ignoreRecord: true),
            TextInput::make('name')->required(),
            TextInput::make('category')->maxLength(80),
            Textarea::make('description')->columnSpanFull(),
            Toggle::make('enabled')->default(true),
            Toggle::make('is_core')->default(false),
            TextInput::make('hours_saved_weekly')->numeric()->default(0),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('key')->badge(),
                TextColumn::make('category')->toggleable(),
                IconColumn::make('enabled')->boolean(),
                IconColumn::make('is_core')->boolean()->label('Core'),
                TextColumn::make('hours_saved_weekly')->label('Hrs/wk'),
            ])
            ->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageModuleSettings::route('/')];
    }
}
