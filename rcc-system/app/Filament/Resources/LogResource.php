<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LogResource\Pages;
use App\Models\WaMessage;
use App\Models\EventParticipation;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class LogResource extends Resource
{
    protected static ?string $model = WaMessage::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Logs';
    protected static ?string $navigationLabel = 'WhatsApp & Pagamentos';

    public static function table(Table $table): Table
    {
        return $table
            ->query(WaMessage::query())
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Usuário')->searchable(),
                Tables\Columns\TextColumn::make('message')->wrap(),
                Tables\Columns\TextColumn::make('status')->badge()->colors([
                    'warning' => 'pending',
                    'success' => 'delivered',
                    'danger' => 'failed',
                ]),
                Tables\Columns\TextColumn::make('delivered_at')->dateTime()->label('Entregue em'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')->options([
                    'pending' => 'Pendente',
                    'sent' => 'Enviado',
                    'delivered' => 'Entregue',
                    'failed' => 'Falhou',
                ]),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start'),
                        Forms\Components\DatePicker::make('end'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start'] ?? null, fn($q,$d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['end'] ?? null, fn($q,$d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ])
            ->actions([
                // Somente leitura
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLogs::route('/'),
        ];
    }
}

