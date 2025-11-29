<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ministry;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminEmail = 'criverdim@hotmail.com';

        User::updateOrCreate(
            ['email' => $adminEmail],
            [
                'name' => 'Administrador RCC',
                'password' => Hash::make('secret123'),
                'status' => 'active',
                'is_servo' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'servo.teste@example.com'],
            [
                'name' => 'Servo Teste',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'is_servo' => true,
                'phone' => '+55 11 99999-9999',
                'whatsapp' => '+55 11 99999-9999',
            ]
        );

        User::updateOrCreate(
            ['email' => 'membro.teste@example.com'],
            [
                'name' => 'Membro Teste',
                'password' => Hash::make('password123'),
                'status' => 'active',
                'is_servo' => false,
                'phone' => '+55 11 98888-8888',
                'whatsapp' => '+55 11 98888-8888',
            ]
        );

        $ministries = [
            'Ministério de Música e Artes',
            'Ministério de Comunicação Social',
            'Ministério para as Crianças',
            'Ministério de Oração por Cura e Libertação',
            'Ministério para as Famílias',
            'Ministério Fé e Política',
            'Ministério de Formação',
            'Ministério de Intercessão',
            'Ministério Jovem',
            'Ministério de Pregação',
            'Ministério de Promoção Humana',
            'Ministério para as Religiosas e Consagradas',
            'Ministério Cristo Sacerdote',
            'Ministério para os Seminaristas',
            'Ministério Universidades Renovadas',
        ];

        foreach ($ministries as $name) {
            Ministry::updateOrCreate(
                ['name' => $name],
                [
                    'is_active' => true,
                ]
            );
        }
    }
}
