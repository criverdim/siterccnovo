<?php

namespace App\Filament\Pages;

use App\Models\Group;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Schema;

class AdminUserCards extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Gerenciamento';
    protected static ?string $title = 'Usuários - Visualização em Cartões';
    protected static ?int $navigationSort = 1;
    protected static string $view = 'filament.pages.admin-user-cards';

    public static function canAccess(): bool
    {
        $u = auth()->user();
        return (bool) ($u?->can_access_admin || $u?->is_master_admin || ($u?->role === 'admin'));
    }

    protected function getViewData(): array
    {
        $token = '';
        if (auth()->check() && Schema::hasTable('personal_access_tokens') && method_exists(auth()->user(), 'createToken')) {
            try {
                $token = auth()->user()->createToken('admin-user-cards')->plainTextToken;
            } catch (\Throwable $e) {
                $token = '';
            }
        }

        return [
            'apiToken' => $token,
            'groups' => Group::query()->orderBy('name')->get(['id', 'name']),
        ];
    }
}
