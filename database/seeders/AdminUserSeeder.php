<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Administrateur
        $admin = User::firstOrCreate(
            ['email' => 'admin@dsi.local'],
            [
                'name'       => 'Administrateur DSI',
                'password'   => Hash::make('Admin@1234'),
                'department' => 'DSI',
                'is_active'  => true,
            ]
        );
        $admin->assignRole('administrateur');

        // Responsable
        $responsable = User::firstOrCreate(
            ['email' => 'responsable@dsi.local'],
            [
                'name'       => 'Responsable DSI',
                'password'   => Hash::make('Resp@1234'),
                'department' => 'DSI',
                'is_active'  => true,
            ]
        );
        $responsable->assignRole('responsable');

        // Utilisateur
        $user = User::firstOrCreate(
            ['email' => 'user@dsi.local'],
            [
                'name'       => 'Utilisateur Test',
                'password'   => Hash::make('User@1234'),
                'department' => 'RH',
                'is_active'  => true,
            ]
        );
        $user->assignRole('utilisateur');
    }
}
