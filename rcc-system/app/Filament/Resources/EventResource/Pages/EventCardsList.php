<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Resources\Pages\Page;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;
use App\Models\Event;
use Filament\Actions;
use Filament\Notifications\Notification;

class EventCardsList extends Page
{
    protected static string $resource = EventResource::class;
    
    protected static string $view = 'filament.resources.event-resource.pages.event-cards-list';
    
    protected static ?string $title = 'Eventos';
    
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    
    protected static ?string $navigationGroup = 'Eventos';
    
    protected static ?string $navigationLabel = 'Eventos';
    
    protected static ?int $navigationSort = 1;

    public function mount(): void
    {
        // Carrega todos os eventos
    }

    public function getEvents()
    {
        return Event::withCount('participations')
            ->with('participations')
            ->orderBy('start_date', 'desc')
            ->get();
    }

    public function getEventColors()
    {
        return [
            '#10B981', // Verde principal
            '#3B82F6', // Azul
            '#F59E0B', // Laranja
            '#EF4444', // Vermelho
            '#8B5CF6', // Roxo
            '#06B6D4', // Ciano
            '#84CC16', // Verde limão
            '#F97316', // Laranja vibrante
        ];
    }

    public function getEventColor($index)
    {
        $colors = $this->getEventColors();
        return $colors[$index % count($colors)];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('Novo Evento')
                ->icon('heroicon-o-plus')
                ->url(route('filament.admin.resources.events.create')),
        ];
    }

    public function deleteEvent($eventId)
    {
        try {
            $event = Event::find($eventId);
            
            if (!$event) {
                Notification::make()
                    ->title('Evento não encontrado')
                    ->danger()
                    ->send();
                return;
            }

            // Verifica se há participações
            if ($event->participations()->exists()) {
                Notification::make()
                    ->title('Não é possível excluir evento com inscrições')
                    ->warning()
                    ->send();
                return;
            }

            $event->delete();

            Notification::make()
                ->title('Evento excluído com sucesso')
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('Erro ao excluir evento')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }
}