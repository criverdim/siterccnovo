<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
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
        $user->save();
    }
}

