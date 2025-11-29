<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class GroupAttendanceRelationManager extends RelationManager
{
    protected static string $relationship = 'groupAttendance';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('group_id')
                    ->relationship('group', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\DatePicker::make('date')->required()->native(false),
                Forms\Components\TextInput::make('source')->maxLength(50),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('group.name')->label('Grupo')->searchable(),
                Tables\Columns\TextColumn::make('date')->date('d/m/Y')->sortable(),
                Tables\Columns\TextColumn::make('source')->badge(),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
