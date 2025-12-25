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

use App\Filament\Exports\EventParticipationExporter;
use Filament\Actions\Exports\Enums\ExportFormat;

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
            ->heading(null)
            ->contentGrid([
                'sm' => 1,
                'md' => 2,
                'xl' => 3,
            ])
            ->columns([
                Tables\Columns\Layout\Panel::make([
                    Tables\Columns\Layout\Stack::make([
                        Tables\Columns\TextColumn::make('name')
                            ->label('Nome do Evento')
                            ->weight('bold')
                            ->size('lg')
                            ->extraAttributes(['class' => 'text-slate-800']),
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\TextColumn::make('start_date')
                                ->label('Data')
                                ->dateTime('d/m/Y H:i')
                                ->icon('heroicon-o-calendar'),
                            Tables\Columns\TextColumn::make('participations_count')
                                ->label('Inscritos')
                                ->counts('participations')
                                ->badge()
                                ->extraAttributes(['class' => 'font-semibold']),
                        ]),
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\TextColumn::make('location')
                                ->label('Local')
                                ->icon('heroicon-o-map-pin')
                                ->wrap(),
                            Tables\Columns\TextColumn::make('price')
                                ->label('Valor')
                                ->money('BRL')
                                ->visible(fn ($record) => $record && $record->is_paid)
                                ->icon('heroicon-o-banknotes'),
                        ]),
                        Tables\Columns\TextColumn::make('detalhes')
                            ->label('')
                            ->state(fn (Event $record) => '<div class="uc-actions"><a class="btn-details" href="'.Pages\EventSingleDashboard::getUrl(['record' => $record]).'">Ver detalhes</a></div>')
                            ->html(),
                    ])
                        ->extraAttributes(function (Event $record) {
                            $palette = [
                                '#1D4ED8', // azul
                                '#16A34A', // verde
                                '#F97316', // laranja
                                '#F59E0B', // ouro
                                '#DC2626', // vermelho
                                '#7C3E0C', // marrom
                            ];
                            $color = $palette[$record->id % count($palette)];
                            return [
                                'class' => 'rounded-2xl border shadow-md p-4 relative pb-14',
                                'style' => 'background: linear-gradient(180deg, '. $color .'22 0%, #ffffff 70%);',
                            ];
                        }),
                ])
                    ->collapsible(false),
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
            ->recordUrl(fn (Event $record): string => Pages\EventSingleDashboard::getUrl(['record' => $record]))
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->label('Novo Evento'),
                Tables\Actions\ExportAction::make()
                    ->exporter(EventParticipationExporter::class)
                    ->label('Exportar CSV')
                    ->formats([ExportFormat::Csv, ExportFormat::Xlsx]),
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
                Tables\Actions\Action::make('dashboard')
                    ->label('Dashboard')
                    ->icon('heroicon-o-chart-bar')
                    ->url(fn (Event $record): string => Pages\EventSingleDashboard::getUrl(['record' => $record])),
                Tables\Actions\DeleteAction::make()->color('danger'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ExportBulkAction::make()
                        ->exporter(EventParticipationExporter::class)
                        ->label('Exportar Selecionados')
                        ->formats([ExportFormat::Csv, ExportFormat::Xlsx]),
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
            'index' => Pages\EventCardsList::route('/'),
            'create' => Pages\CreateEvent::route('/create'),
            'edit' => Pages\EditEvent::route('/{record}/edit'),
            'dashboard' => Pages\EventSingleDashboard::route('/{record}/dashboard'),
        ];
    }
}
