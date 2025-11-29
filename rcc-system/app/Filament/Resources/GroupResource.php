<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GroupResource\Pages;
use App\Filament\Resources\GroupResource\RelationManagers;
use App\Models\Group;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $navigationGroup = 'Gerenciamento';

    protected static ?string $navigationLabel = 'Grupos de Oração';
    protected static ?string $modelLabel = 'Grupo de Oração';
    protected static ?string $pluralModelLabel = 'Grupos de Oração';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Grupo')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome do Grupo')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('description')
                            ->label('Descrição')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('responsible')
                            ->label('Responsável')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('responsible_phone')
                            ->label('Telefone do Responsável')
                            ->tel()
                            ->mask('(99) 9999-9999')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('responsible_whatsapp')
                            ->label('WhatsApp do Responsável')
                            ->tel()
                            ->mask('(99) 9 9999-9999')
                            ->maxLength(50),
                        Forms\Components\TextInput::make('responsible_email')
                            ->label('Email do Responsável')
                            ->email()
                            ->maxLength(255),
                        Forms\Components\Select::make('weekday')
                            ->label('Dia da Semana')
                            ->options([
                                'sunday' => 'Domingo',
                                'monday' => 'Segunda-feira',
                                'tuesday' => 'Terça-feira',
                                'wednesday' => 'Quarta-feira',
                                'thursday' => 'Quinta-feira',
                                'friday' => 'Sexta-feira',
                                'saturday' => 'Sábado',
                            ])
                            ->required(),
                        Forms\Components\TimePicker::make('time')
                            ->label('Horário')
                            ->required()
                            ->native(false),
                        Forms\Components\Textarea::make('address')
                            ->label('Endereço Completo')
                            ->required()
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('photos')
                            ->label('Fotos do Grupo')
                            ->multiple()
                            ->image()
                            ->directory('groups')
                            ->maxFiles(10)
                            ->imageEditor()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('cover_photo')
                            ->label('Foto de Capa')
                            ->image()
                            ->directory('groups')
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')
                            ->helperText('Escolha a imagem que será usada como destaque no topo da página do grupo'),
                        Forms\Components\ColorPicker::make('cover_bg_color')
                            ->label('Cor de fundo da capa')
                            ->helperText('Usada para harmonizar o backdrop atrás da imagem'),
                        Forms\Components\Select::make('cover_object_position')
                            ->label('Foco da imagem de capa')
                            ->options([
                                'center' => 'Centro',
                                'top' => 'Topo',
                                'bottom' => 'Base',
                                'left' => 'Esquerda',
                                'right' => 'Direita',
                            ])
                            ->helperText('Ajusta o alinhamento da imagem quando necessário'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome do Grupo')
                    ->searchable(),
                Tables\Columns\TextColumn::make('weekday')
                    ->label('Dia da Semana')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sunday' => 'Domingo',
                        'monday' => 'Segunda-feira',
                        'tuesday' => 'Terça-feira',
                        'wednesday' => 'Quarta-feira',
                        'thursday' => 'Quinta-feira',
                        'friday' => 'Sexta-feira',
                        'saturday' => 'Sábado',
                    }),
                Tables\Columns\TextColumn::make('time')
                    ->label('Horário')
                    ->time(),
                Tables\Columns\TextColumn::make('users_count')
                    ->label('Membros')
                    ->counts('users')
                    ->badge(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('weekday')
                    ->label('Dia da Semana')
                    ->options([
                        'sunday' => 'Domingo',
                        'monday' => 'Segunda-feira',
                        'tuesday' => 'Terça-feira',
                        'wednesday' => 'Quarta-feira',
                        'thursday' => 'Quinta-feira',
                        'friday' => 'Sexta-feira',
                        'saturday' => 'Sábado',
                    ]),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Cadastrar Novo Grupo')
                    ->icon('heroicon-o-plus'),
                Tables\Actions\ExportAction::make()->label('Exportar CSV'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\Action::make('abrir_whatsapp')
                    ->label('Abrir WhatsApp')
                    ->icon('heroicon-o-chat-bubble-bottom-center-text')
                    ->url(fn($record) => $record->responsible_whatsapp ? ('https://wa.me/'.preg_replace('/\D+/', '', $record->responsible_whatsapp)) : null, true)
                    ->openUrlInNewTab()
                    ->visible(fn($record) => filled($record->responsible_whatsapp)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()->label('Exportar Selecionados'),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\UsersRelationManager::class,
            RelationManagers\AttendanceRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }
}
