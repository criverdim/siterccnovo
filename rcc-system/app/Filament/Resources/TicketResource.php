<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TicketResource\Pages;
use App\Models\Event;
use App\Models\Ticket;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Http;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;
    protected static ?string $navigationIcon = 'heroicon-o-ticket';
    protected static ?string $navigationGroup = 'Eventos';
    protected static ?string $navigationLabel = 'Ingressos';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('ticket_code')->label('Código')->disabled(),
            Forms\Components\Select::make('status')->options([
                'active' => 'Ativo',
                'used' => 'Utilizado',
                'cancelled' => 'Cancelado',
            ]),
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
                        Tables\Columns\TextColumn::make('event.name')
                            ->label('Evento')
                            ->weight('bold')
                            ->size('lg')
                            ->extraAttributes(['class' => 'text-slate-900'])
                            ->wrap(),
                        Tables\Columns\TextColumn::make('user.email')
                            ->label('Usuário')
                            ->icon('heroicon-o-user')
                            ->wrap()
                            ->sortable()
                            ->searchable(),
                        Tables\Columns\TextColumn::make('ticket_code')
                            ->label('Código')
                            ->copyable()
                            ->toggleable(isToggledHiddenByDefault: true)
                            ->extraAttributes(['class' => 'font-mono text-xs text-slate-500']),
                        Tables\Columns\Layout\Split::make([
                            Tables\Columns\BadgeColumn::make('status')
                                ->label('Status')
                                ->colors([
                                    'success' => 'active',
                                    'gray' => 'used',
                                    'danger' => 'cancelled',
                                ])
                                ->icons([
                                    'heroicon-o-check-circle' => 'active',
                                    'heroicon-o-check-badge' => 'used',
                                    'heroicon-o-x-circle' => 'cancelled',
                                ])
                                ->iconPosition('before')
                                ->sortable(),
                            Tables\Columns\TextColumn::make('used_at')
                                ->label('Usado em')
                                ->dateTime('d/m/Y H:i')
                                ->icon('heroicon-o-clock')
                                ->sortable()
                                ->placeholder('—'),
                        ]),
                    ])->space(3),
                ])->collapsible(false)
                    ->extraAttributes([
                        'class' => 'rounded-2xl border border-slate-200 bg-white shadow-sm hover:shadow-md transition-all duration-200 p-4 flex flex-col justify-between gap-4',
                    ]),
            ])
            ->filters([
                SelectFilter::make('event_id')->label('Evento')->options(Event::query()->pluck('name', 'id')->toArray()),
                SelectFilter::make('status')->label('Status')->options([
                    'active' => 'Ativo',
                    'used' => 'Utilizado',
                    'cancelled' => 'Cancelado',
                ]),
            ])
            ->actions([
                Action::make('marcar_utilizado')
                    ->label('Marcar utilizado')
                    ->icon('heroicon-o-check-badge')
                    ->button()
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Ticket $record) {
                    $record->markAsUsed();
                    Log::info('Ticket marcado como utilizado', ['ticket_id' => $record->id]);
                })->visible(fn (Ticket $record) => $record->status !== 'used'),
                Action::make('aprovar_manual')
                    ->label('Aprovar pagamento')
                    ->icon('heroicon-o-check-circle')
                    ->button()
                    ->color('primary')
                    ->requiresConfirmation()
                    ->action(function (Ticket $record) {
                    try {
                        $participation = \App\Models\EventParticipation::where('ticket_uuid', $record->ticket_code)->first();
                        if ($participation) {
                            $participation->payment_status = 'approved';
                            $participation->save();
                            app(\App\Services\TicketService::class)->generateAndSend($participation);
                            Log::info('Pagamento aprovado manualmente e ticket (re)gerado', ['ticket_id' => $record->id, 'participation_id' => $participation->id]);
                        }
                    } catch (\Throwable $e) {
                        Log::warning('Falha na aprovação manual de pagamento', ['ticket_id' => $record->id, 'error' => $e->getMessage()]);
                    }
                }),
                ActionGroup::make([
                    Action::make('mp_refund')
                        ->label('Estornar Mercado Pago')
                        ->icon('heroicon-o-arrow-uturn-left')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->action(function (Ticket $record) {
                            try {
                                $payment = \App\Models\Payment::find($record->payment_id);
                                $mpId = $payment?->mercado_pago_id;
                                $token = (string) config('services.mercadopago.access_token');
                                if (! $payment || ! $mpId || $token === '') {
                                    return;
                                }
                                $resp = Http::withToken($token)->acceptJson()->post('https://api.mercadopago.com/v1/payments/'.urlencode((string) $mpId).'/refunds', []);
                                if ($resp->ok()) {
                                    $payment->update(['status' => 'refunded']);
                                    $participation = \App\Models\EventParticipation::where('ticket_uuid', $record->ticket_code)->first();
                                    if ($participation) {
                                        $participation->payment_status = 'refunded';
                                        $participation->save();
                                    }
                                    Log::info('Pagamento estornado via Mercado Pago', ['ticket_id' => $record->id, 'payment_id' => $payment->id]);
                                } else {
                                    Log::warning('Falha ao estornar via Mercado Pago', ['ticket_id' => $record->id, 'http_status' => $resp->status()]);
                                }
                            } catch (\Throwable $e) {
                                Log::warning('Erro ao estornar via Mercado Pago', ['ticket_id' => $record->id, 'error' => $e->getMessage()]);
                            }
                        }),
                    Action::make('mp_cancel')
                        ->label('Cancelar Mercado Pago')
                        ->icon('heroicon-o-x-circle')
                        ->requiresConfirmation()
                        ->color('warning')
                        ->action(function (Ticket $record) {
                            try {
                                $payment = \App\Models\Payment::find($record->payment_id);
                                $mpId = $payment?->mercado_pago_id;
                                $token = (string) config('services.mercadopago.access_token');
                                if (! $payment || ! $mpId || $token === '') {
                                    return;
                                }
                                $resp = Http::withToken($token)->acceptJson()->put('https://api.mercadopago.com/v1/payments/'.urlencode((string) $mpId), ['status' => 'cancelled']);
                                if ($resp->ok()) {
                                    $payment->update(['status' => 'cancelled']);
                                    $participation = \App\Models\EventParticipation::where('ticket_uuid', $record->ticket_code)->first();
                                    if ($participation) {
                                        $participation->payment_status = 'cancelled';
                                        $participation->save();
                                    }
                                    Log::info('Pagamento cancelado via Mercado Pago', ['ticket_id' => $record->id, 'payment_id' => $payment->id]);
                                } else {
                                    Log::warning('Falha ao cancelar via Mercado Pago', ['ticket_id' => $record->id, 'http_status' => $resp->status()]);
                                }
                            } catch (\Throwable $e) {
                                Log::warning('Erro ao cancelar via Mercado Pago', ['ticket_id' => $record->id, 'error' => $e->getMessage()]);
                            }
                        }),
                    Action::make('reativar')
                        ->label('Reativar')
                        ->icon('heroicon-o-arrow-path')
                        ->requiresConfirmation()
                        ->visible(fn (Ticket $record) => $record->status === 'cancelled' || $record->status === 'used')
                        ->action(function (Ticket $record) {
                            $record->update(['status' => 'active', 'used_at' => null]);
                            Log::info('Ticket reativado', ['ticket_id' => $record->id]);
                        }),
                    Action::make('cancelar')
                        ->label('Cancelar')
                        ->icon('heroicon-o-no-symbol')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->visible(fn (Ticket $record) => $record->status !== 'cancelled')
                        ->action(function (Ticket $record) {
                            $record->update(['status' => 'cancelled']);
                            Log::info('Ticket cancelado', ['ticket_id' => $record->id]);
                        }),
                    Action::make('reenviar_email')
                        ->label('Reenviar e-mail')
                        ->icon('heroicon-o-envelope')
                        ->action(function (Ticket $record) {
                            try {
                                $participation = \App\Models\EventParticipation::where('ticket_uuid', $record->ticket_code)->first();
                                if ($participation && $record->pdf_path) {
                                    Mail::to($participation->user->email)->send(new \App\Mail\TicketMailable($participation, $record->pdf_path));
                                    Log::info('Ticket re-enviado por e-mail', ['ticket_id' => $record->id]);
                                }
                            } catch (\Throwable $e) {
                                Log::warning('Falha ao reenviar e-mail do ticket', ['ticket_id' => $record->id, 'error' => $e->getMessage()]);
                            }
                        }),
                ])->icon('heroicon-o-ellipsis-horizontal')->label('Mais ações'),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('cancelar')->label('Cancelar selecionados')->color('danger')->action(function (\Illuminate\Support\Collection $records) {
                    foreach ($records as $record) {
                        $record->update(['status' => 'cancelled']);
                    }
                }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
        ];
    }
}
