<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Models\Event;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class EventDetails extends Page implements Tables\Contracts\HasTable
{
    use InteractsWithRecord;
    use Tables\Concerns\InteractsWithTable;

    protected static string $resource = EventResource::class;
    
    protected static string $view = 'filament.resources.event-resource.pages.event-details';
    
    protected static ?string $title = 'Detalhes do Evento';

    public ?Event $event = null;

    public function mount(int | string $record): void
    {
        $this->record = $this->resolveRecord($record);
        $this->event = $this->record;
    }

    public function getEventStats()
    {
        if (!$this->event) {
            return [];
        }

        $totalParticipations = $this->event->participations()->count();
        $paidParticipations = $this->event->participations()->where('payment_status', 'approved')->count();
        $pendingParticipations = $this->event->participations()->where('payment_status', 'pending')->count();
        $totalRevenue = $this->event->payments()->where('status', 'approved')->sum('amount');
        $totalTickets = $this->event->tickets()->count();
        $usedTickets = $this->event->tickets()->where('status', 'used')->count();

        return [
            'total_participations' => $totalParticipations,
            'paid_participations' => $paidParticipations,
            'pending_participations' => $pendingParticipations,
            'total_revenue' => $totalRevenue,
            'total_tickets' => $totalTickets,
            'used_tickets' => $usedTickets,
            'attendance_rate' => $totalTickets > 0 ? round(($usedTickets / $totalTickets) * 100, 1) : 0,
            'occupancy_rate' => $this->event->capacity > 0 ? round(($paidParticipations / $this->event->capacity) * 100, 1) : 0,
        ];
    }

    public function getPaymentDistribution()
    {
        if (!$this->event) {
            return [];
        }

        return $this->event->payments()
            ->where('status', 'approved')
            ->selectRaw('payment_method, COUNT(*) as count, SUM(amount) as total')
            ->groupBy('payment_method')
            ->get()
            ->map(function ($item) {
                return [
                    'method' => ucfirst($item->payment_method ?? 'Não especificado'),
                    'count' => $item->count,
                    'total' => $item->total,
                ];
            });
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                $this->event->participations()->with(['user', 'payment'])
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('Nome')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(),

                BadgeColumn::make('payment_status')
                    ->label('Status Pagamento')
                    ->colors([
                        'success' => 'approved',
                        'warning' => 'pending',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'approved' => 'Aprovado',
                        'pending' => 'Pendente',
                        'failed' => 'Falhou',
                        default => ucfirst($state),
                    }),

                TextColumn::make('payment.payment_method')
                    ->label('Método Pagamento')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'pix' => 'PIX',
                        'credit_card' => 'Cartão Crédito',
                        'debit_card' => 'Cartão Débito',
                        'boleto' => 'Boleto',
                        default => ucfirst(str_replace('_', ' ', $state)),
                    })
                    ->toggleable(),

                TextColumn::make('payment.amount')
                    ->label('Valor Pago')
                    ->money('BRL')
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Data Inscrição')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                TextColumn::make('ticket_uuid')
                    ->label('Ingresso')
                    ->formatStateUsing(fn (string $state): string => $state ? '✓' : '-')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('payment_status')
                    ->label('Status Pagamento')
                    ->options([
                        'approved' => 'Aprovado',
                        'pending' => 'Pendente',
                        'failed' => 'Falhou',
                    ]),
                SelectFilter::make('has_ticket')
                    ->label('Com Ingresso')
                    ->options([
                        'yes' => 'Sim',
                        'no' => 'Não',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'] === 'yes', fn ($q) => $q->whereNotNull('ticket_uuid'))
                                    ->when($data['value'] === 'no', fn ($q) => $q->whereNull('ticket_uuid'));
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->url(fn ($record) => route('filament.admin.resources.users.view', $record->user)),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50]);
    }
}