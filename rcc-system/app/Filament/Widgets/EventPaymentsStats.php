<?php

namespace App\Filament\Widgets;

use App\Models\Payment;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
class EventPaymentsStats extends BaseWidget
{
    protected function getStats(): array
    {
        $approvedTotal = (float) Payment::where('status', 'approved')->sum('amount');
        $approvedCount = (int) Payment::where('status', 'approved')->count();
        $pendingCount = (int) Payment::where('status', 'pending')->count();
        $rejectedCount = (int) Payment::where('status', 'rejected')->count();

        $pixCount = (int) Payment::where('payment_method', 'pix')->count();
        $cardCount = (int) Payment::where('payment_method', 'credit_card')->count();
        $boletoCount = (int) Payment::where('payment_method', 'boleto')->count();

        return [
            Stat::make('Total aprovado (R$)', number_format($approvedTotal, 2, ',', '.'))
                ->description($approvedCount.' pagamentos')
                ->color('success'),
            Stat::make('Em análise / pendentes', (string) $pendingCount)
                ->color('warning'),
            Stat::make('Rejeitados', (string) $rejectedCount)
                ->color('danger'),
            Stat::make('Métodos', "PIX: $pixCount • Cartão: $cardCount • Boleto: $boletoCount")
                ->color('primary'),
        ];
    }
}
