<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use App\Models\Group;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (isset($data['groups']) && is_array($data['groups']) && count($data['groups']) > 0) {
            $first = collect($data['groups'])->filter()->values()->first();
            $data['group_id'] = $first ?? null;
        } else {
            $data['group_id'] = $data['group_id'] ?? null;
        }
        return $data;
    }

    protected function afterSave(): void
    {
        $groups = (array) ($this->data['groups'] ?? []);
        $groups = collect($groups)->filter()->unique()->values()->all();
        if (method_exists($this->record, 'groups')) {
            $this->record->groups()->sync($groups);
        }
    }
}
