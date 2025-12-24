<?php

namespace App\Filament\Exports;

use App\Models\EventParticipation;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class EventParticipationExporter extends Exporter
{
    protected static ?string $model = EventParticipation::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('event.name')->label('Evento'),
            ExportColumn::make('user.name')->label('Participante'),
            ExportColumn::make('user.email')->label('Email'),
            ExportColumn::make('ticket_uuid')->label('Ticket UUID'),
            ExportColumn::make('ticket_code')->label('Código do Ingresso'),
            ExportColumn::make('payment_status')->label('Status Pagamento'),
            ExportColumn::make('payment_method')->label('Método Pagamento'),
            ExportColumn::make('created_at')->label('Data Inscrição'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your event participation export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
