<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use Filament\Resources\Pages\Page;

class EventSingleDashboard extends Page
{
    use InteractsWithRecord;

    protected static string $resource = EventResource::class;

    protected static string $view = 'filament.resources.event-resource.pages.event-single-dashboard';

    public function mount(int|string $record): void
    {
        $this->record = $this->resolveRecord($record);
    }

    public function getHeading(): string
    {
        return '';
    }

    protected function getHeaderWidgets(): array
    {
        return [];
    }

    public function getDashboardWidgets(): array
    {
        return [
            EventResource\Widgets\EventSingleStats::make(['record' => $this->record]),
            EventResource\Widgets\EventSingleSalesChart::make(['record' => $this->record]),
        ];
    }

    protected function getViewData(): array
    {
        $pix = $this->record->payments()->where('payment_method', 'pix')->count();
        $card = $this->record->payments()->where('payment_method', 'credit_card')->count();
        $cash = $this->record->payments()->where('payment_method', 'cash')->count();

        return [
            'paymentsSummary' => [
                'pix' => $pix,
                'card' => $card,
                'cash' => $cash,
                'total' => $pix + $card + $cash,
            ],
        ];
    }

    public function getStatusColor(?string $status): string
    {
        return match($status) {
            'approved' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
            'pending' => 'bg-amber-100 text-amber-700 border-amber-200',
            'failed' => 'bg-red-100 text-red-700 border-red-200',
            default => 'bg-gray-100 text-gray-700 border-gray-200'
        };
    }

    public function getStatusIcon(?string $status): string
    {
        return match($status) {
            'approved' => 'heroicon-m-check-circle',
            'pending' => 'heroicon-m-clock',
            'failed' => 'heroicon-m-x-circle',
            default => 'heroicon-m-question-mark-circle'
        };
    }
}
