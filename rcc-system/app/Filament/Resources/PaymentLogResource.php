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

    protected static ?string $navigationGroup = 'Registros';

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
                Tables\Columns\TextColumn::make('mp_payment_id')->label('ID do Pagamento MP')->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->label('Criado em')->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('payment_status')->label('Status do pagamento')->options([
                    'pending' => 'Pendente',
                    'approved' => 'Aprovado',
                    'rejected' => 'Rejeitado',
                    'cancelled' => 'Cancelado',
                    'refunded' => 'Reembolsado',
                ]),
                Tables\Filters\SelectFilter::make('payment_method')->label('Método de pagamento')->options([
                    'pix' => 'PIX',
                    'card' => 'Cartão',
                    'boleto' => 'Boleto',
                ]),
                Tables\Filters\Filter::make('date_range')->label('Período')
                    ->form([
                        Forms\Components\DatePicker::make('start')->label('Inicial'),
                        Forms\Components\DatePicker::make('end')->label('Final'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when($data['start'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '>=', $d))
                            ->when($data['end'] ?? null, fn ($q, $d) => $q->whereDate('created_at', '<=', $d));
                    }),
            ])
            ->actions([
                Tables\Actions\Action::make('recibo_pdf')
                    ->label('Gerar Recibo PDF')
                    ->icon('heroicon-o-document-text')
                    ->action(function (\App\Models\EventParticipation $record) {
                        $html = view('pdf.receipt', ['p' => $record])->render();
                        $pdf = \PDF::loadHTML($html);

                        return response($pdf->output(), 200, [
                            'Content-Type' => 'application/pdf',
                            'Content-Disposition' => 'attachment; filename="recibo-'.$record->id.'.pdf"',
                        ]);
                    }),
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
