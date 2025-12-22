<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EventResource\Pages;
use App\Filament\Resources\EventResource\RelationManagers;
use App\Models\Event;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $navigationGroup = 'Eventos';

    protected static ?string $navigationLabel = 'Eventos';

    protected static ?string $modelLabel = 'Evento';

    protected static ?string $pluralModelLabel = 'Eventos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Informações do Evento')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('Nome do Evento')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Select::make('category')
                            ->label('Categoria')
                            ->options([
                                'retiro' => 'Retiro',
                                'encontro' => 'Encontro',
                                'culto' => 'Culto Especial',
                                'congresso' => 'Congresso',
                                'vigilia' => 'Vigília',
                            ])
                            ->native(false),
                        Forms\Components\RichEditor::make('description')
                            ->label('Descrição')
                            ->required()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('featured_image')
                            ->label('Imagem de capa')
                            ->helperText('Usada no banner da página do evento e em compartilhamento (OG). Recomenda proporção 16:9.')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('16:9')
                            ->imageEditorAspectRatios(['16:9', '4:3', '1:1', '9:16'])
                            ->disk('public')
                            ->visibility('public')
                            ->directory('events')
                            ->maxSize(4096)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                        Forms\Components\FileUpload::make('folder_image')
                            ->label('Imagem de folder')
                            ->helperText('Usada nos cards (lista de eventos e página de detalhes). Recomenda proporção 4:3.')
                            ->image()
                            ->imageEditor()
                            ->imageCropAspectRatio('4:3')
                            ->imageEditorAspectRatios(['16:9', '4:3', '1:1', '9:16'])
                            ->disk('public')
                            ->visibility('public')
                            ->directory('events/folder')
                            ->maxSize(4096)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp']),
                        Forms\Components\FileUpload::make('gallery_images')
                            ->label('Galeria de fotos')
                            ->multiple()
                            ->image()
                            ->imageEditor()
                            ->imageEditorAspectRatios(['16:9', '4:3', '1:1', '9:16'])
                            ->disk('public')
                            ->visibility('public')
                            ->directory('events/gallery')
                            ->maxFiles(10)
                            ->maxSize(4096)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->columnSpanFull(),
                        Forms\Components\TextInput::make('location')
                            ->label('Local do Evento')
                            ->required()
                            ->maxLength(255),
                        Forms\Components\Textarea::make('arrival_info')->label('Chegada/Estacionamento')->columnSpanFull(),
                        Forms\Components\TextInput::make('map_embed_url')
                            ->label('Mapa (embed URL)')
                            ->url()
                            ->dehydrateStateUsing(fn ($state) => is_string($state) ? rtrim(trim($state), ',') : $state)
                            ->columnSpanFull(),
                        Forms\Components\DateTimePicker::make('start_date')
                            ->label('Data e horário de início')
                            ->required()
                            ->native(false),
                        Forms\Components\DateTimePicker::make('end_date')
                            ->label('Data e horário de término')
                            ->native(false),
                        Forms\Components\TimePicker::make('start_time')
                            ->label('Horário de Início')
                            ->seconds(false)
                            ->native(false),
                        Forms\Components\TimePicker::make('end_time')
                            ->label('Horário de Término')
                            ->seconds(false)
                            ->native(false),
                        Forms\Components\TextInput::make('days_count')->label('Qtd. de dias')->numeric(),
                        Forms\Components\TextInput::make('min_age')->label('Idade mínima')->numeric(),
                    ])
                    ->columns(2),

                Forms\Components\Section::make('Configurações do Evento')
                    ->schema([
                        Forms\Components\Toggle::make('is_paid')
                            ->label('Evento Pago?')
                            ->inline(false)
                            ->reactive(),
                        Forms\Components\TextInput::make('price')
                            ->label('Valor')
                            ->numeric()
                            ->prefix('R$')
                            ->visible(fn (Forms\Get $get): bool => $get('is_paid')),
                        Forms\Components\Toggle::make('parceling_enabled')->label('Parcelamento')->inline(false),
                        Forms\Components\TextInput::make('parceling_max')->label('Máx. parcelas')->numeric()->visible(fn (Forms\Get $get) => (bool) $get('parceling_enabled')),
                        Forms\Components\Toggle::make('coupons_enabled')->label('Cupons de desconto')->inline(false),
                        Forms\Components\TextInput::make('capacity')
                            ->label('Capacidade Máxima')
                            ->numeric()
                            ->minValue(1),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Evento Ativo?')
                            ->inline(false)
                            ->default(true),
                        Forms\Components\Repeater::make('extra_services')
                            ->label('Serviços adicionais')
                            ->schema([
                                Forms\Components\TextInput::make('title')->label('Título')->required(),
                                Forms\Components\Textarea::make('desc')->label('Descrição'),
                                Forms\Components\TextInput::make('price')->label('Preço adicional')->numeric(),
                            ])
                            ->collapsible()
                            ->default([])
                            ->columnSpanFull(),
                        Forms\Components\RichEditor::make('terms')->label('Termos e condições')->columnSpanFull(),
                        Forms\Components\RichEditor::make('rules')->label('Regras de participação')->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nome do Evento')
                    ->searchable(),
                Tables\Columns\TextColumn::make('category')
                    ->label('Categoria')
                    ->badge(),
                Tables\Columns\TextColumn::make('start_date')
                    ->label('Data')
                    ->date('d/m/Y')
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_time')
                    ->label('Horário')
                    ->time(),
                Tables\Columns\TextColumn::make('location')
                    ->label('Local')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_paid')
                    ->label('Pago?')
                    ->boolean(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Valor')
                    ->money('BRL')
                    ->visible(fn ($record) => $record && $record->is_paid),
                Tables\Columns\TextColumn::make('capacity')
                    ->label('Capacidade'),
                Tables\Columns\TextColumn::make('participations_count')
                    ->label('Inscritos')
                    ->counts('participations')
                    ->badge(),
                Tables\Columns\IconColumn::make('show_on_homepage')
                    ->label('Home?')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Ativo?')
                    ->boolean(),
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
                Tables\Filters\TernaryFilter::make('is_paid')
                    ->label('Evento Pago?'),
                Tables\Filters\TernaryFilter::make('show_on_homepage')
                    ->label('Mostrar na Home?'),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Evento Ativo?'),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start_date')
                            ->label('Data Inicial'),
                        Forms\Components\DatePicker::make('end_date')
                            ->label('Data Final'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['start_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('start_date', '>=', $date),
                            )
                            ->when(
                                $data['end_date'],
                                fn (Builder $query, $date): Builder => $query->whereDate('end_date', '<=', $date),
                            );
                    }),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()->label('Exportar CSV'),
                Tables\Actions\Action::make('reenviar_ingressos')
                    ->label('Reenviar ingressos')
                    ->action(function () {
                        $parts = \App\Models\EventParticipation::whereNotNull('ticket_uuid')->get();
                        foreach ($parts as $p) {
                            app(\App\Services\TicketService::class)->generateAndSendTicket($p);
                        }
                        \Filament\Notifications\Notification::make()->title('Ingressos reenviados')->success()->send();
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
            RelationManagers\ParticipationsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEvents::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}
