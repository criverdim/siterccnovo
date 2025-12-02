<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VisitaResource extends Resource
{
    protected static ?string $model = \App\Models\Visit::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationGroup = 'Gerenciamento';

    protected static ?string $navigationLabel = 'Visitas';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('target_user_id')
                ->label('Visitado')
                ->relationship('targetUser', 'name')
                ->searchable()->preload()->required(),
            Forms\Components\Select::make('group_id')
                ->label('Grupo')
                ->relationship('group', 'name')
                ->searchable()->preload(),
            Forms\Components\DateTimePicker::make('scheduled_at')
                ->label('Agendada em')->native(false)->required(),
            Forms\Components\Repeater::make('team')
                ->label('Equipe')
                ->schema([
                    Forms\Components\Select::make('user_id')
                        ->label('Servo')
                        ->relationship('creator', 'name')
                        ->searchable()->preload(),
                ])->columns(1),
            Forms\Components\Textarea::make('report')->label('Relatório')->columnSpanFull(),
            Forms\Components\Select::make('status')->label('Status')->options([
                'scheduled' => 'Agendada',
                'done' => 'Concluída',
                'cancelled' => 'Cancelada',
            ])->default('scheduled'),
        ])->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('targetUser.name')->label('Visitado')->searchable(),
            Tables\Columns\TextColumn::make('group.name')->label('Grupo')->sortable(),
            Tables\Columns\TextColumn::make('scheduled_at')->label('Agendada em')->dateTime()->sortable(),
            Tables\Columns\TextColumn::make('status')->label('Status')->badge()->colors([
                'warning' => 'scheduled',
                'success' => 'done',
                'danger' => 'cancelled',
            ]),
        ])->actions([
            Tables\Actions\EditAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\VisitaResource\Pages\ListVisitas::route('/'),
        ];
    }
}
