<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms;
use Filament\Pages\Page;

class DuplicatesTool extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationGroup = 'Gerenciamento';

    protected static ?string $title = 'Fusão de Duplicados';

    protected static string $view = 'filament.pages.duplicates-tool';

    public ?array $results = [];

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('query')->label('Buscar por email/cpf/phone/whatsapp'),
        ];
    }

    public function search(string $query): void
    {
        $this->results = User::query()
            ->where('email', 'like', "%$query%")
            ->orWhere('cpf', 'like', "%$query%")
            ->orWhere('phone', 'like', "%$query%")
            ->orWhere('whatsapp', 'like', "%$query%")
            ->limit(50)->get()->toArray();
    }

    public function merge(int $sourceId, int $targetId): void
    {
        $source = User::findOrFail($sourceId);
        $target = User::findOrFail($targetId);

        \App\Models\EventParticipation::where('user_id', $sourceId)->update(['user_id' => $targetId]);
        \App\Models\GroupAttendance::where('user_id', $sourceId)->update(['user_id' => $targetId]);
        \App\Models\GroupDraw::where('user_id', $sourceId)->update(['user_id' => $targetId]);
        \App\Models\WaMessage::where('user_id', $sourceId)->update(['user_id' => $targetId]);

        $source->delete();
    }
}
