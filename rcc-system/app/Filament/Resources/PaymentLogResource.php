<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PaymentLogResource\Pages;
use App\Models\EventParticipation;
use Filament\Forms;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PaymentLogResource extends Resource
{
    protected static ?string $model = EventParticipation::class;
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    protected static ?string $navigationGroup = 'Logs';
    protected static ?string $navigationLabel = 'Pagamentos (Mercado Pago)';

    public static function table(Table $table): Table
    {
        return $table
            ->query(EventParticipation::query())
            ->columns([
                Tables\Columns\TextColumn::make('user.name')->label('Usuário')->searchable(),
                Tables\Columns\TextColumn::make('event.name')->label('Evento')->searchable(),
                Tables\Columns\TextColumn::make('payment_status')->label('Status')->badge()->colors([
                    'warning' => 'pending',
                    'success' => 'approved',
                    'danger' => 'rejected',
                ]),
                Tables\Columns\TextColumn::make('payment_method')->label('Método')->badge(),
                Tables\Columns\TextColumn::make('mp_payment_id')->label('MP Payment ID')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Criado em')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')->options([
                    'pending' => 'Pendente',
                    'approved' => 'Aprovado',
                    'rejected' => 'Rejeitado',
                    'cancelled' => 'Cancelado',
                    'refunded' => 'Reembolsado',
                ]),
                Tables\Filters\SelectFilter::make('payment_method')->options([
                    'pix' => 'PIX',
                    'card' => 'Cartão',
                    'boleto' => 'Boleto',
                ]),
                Tables\Filters\Filter::make('date_range')
                    ->form([
                        Forms\Components\DatePicker::make('start')->label('Inicial'),
                        Forms\Components\DatePicker::make('end')->label('Final'),
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
            'index' => Pages\ListPaymentLogs::route('/'),
        ];
    }
}

