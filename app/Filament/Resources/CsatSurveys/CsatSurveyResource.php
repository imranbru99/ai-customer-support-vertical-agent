<?php

namespace App\Filament\Resources\CsatSurveys;

use App\Filament\Resources\CsatSurveys\Pages\ManageCsatSurveys;
use App\Models\CsatSurvey;
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
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use UnitEnum;

class CsatSurveyResource extends Resource
{
    protected static ?string $model = CsatSurvey::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedFaceSmile;

    protected static string|UnitEnum|null $navigationGroup = 'Quality';

    protected static ?int $navigationSort = 1;

    public static function shouldRegisterNavigation(): bool
    {
        return app(ModuleManager::class)->enabled('csat_surveys');
    }

    public static function canAccess(): bool
    {
        return static::shouldRegisterNavigation();
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Select::make('support_ticket_id')->label('Ticket')->options(SupportTicket::query()->pluck('number', 'id'))->required()->searchable(),
            TextInput::make('score')->numeric()->minValue(1)->maxValue(5)->required(),
            Textarea::make('comment'),
            Select::make('channel')->options(['email' => 'Email', 'chat' => 'Chat', 'portal' => 'Portal', 'sms' => 'SMS'])->default('email'),
            Toggle::make('recovery_needed')->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ticket.number')->label('Ticket')->searchable(),
                TextColumn::make('score')->badge()->sortable(),
                TextColumn::make('channel'),
                IconColumn::make('recovery_needed')->boolean()->label('Recover'),
                TextColumn::make('submitted_at')->since(),
            ])
            ->recordActions([EditAction::make(), DeleteAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])]);
    }

    public static function getPages(): array
    {
        return ['index' => ManageCsatSurveys::route('/')];
    }
}
