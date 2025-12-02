<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'criverdim@hotmail.com';
        $user = User::firstOrNew(['email' => $email]);
        $user->name = $user->name ?: 'Administrador RCC';
        $user->password = Hash::make('Verdi123@');
        $user->status = 'active';
        $user->is_servo = true;
        $user->role = 'admin';
        $user->can_access_admin = true;
        $user->is_master_admin = true;
        $user->phone = $user->phone ?: '+55 11 90000-0000';
        $user->whatsapp = $user->whatsapp ?: '+55 11 90000-0000';
        $user->save();
    }
}
