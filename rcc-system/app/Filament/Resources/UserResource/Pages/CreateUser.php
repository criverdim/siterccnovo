<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        if (isset($data['groups']) && is_array($data['groups']) && count($data['groups']) > 0) {
            $first = collect($data['groups'])->filter()->values()->first();
            $data['group_id'] = $first ?? null;
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        $groups = (array) ($this->data['groups'] ?? []);
        $groups = collect($groups)->filter()->unique()->values()->all();
        if (method_exists($this->record, 'groups')) {
            $this->record->groups()->sync($groups);
        }
    }
}
