<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrador SafeCity',
            'email' => 'admin@safecity.bo',
            'password' => Hash::make('password'),
            'rol' => 'administrador',
            'is_active' => 1,
        ]);

        User::create([
            'name' => 'Supervisor SafeCity',
            'email' => 'supervisor@safecity.bo',
            'password' => Hash::make('password'),
            'rol' => 'supervisor',
            'is_active' => 1,
        ]);

        User::create([
            'name' => 'Ciudadano Saúl',
            'email' => 'ciudadano@safecity.bo',
            'password' => Hash::make('password'),
            'rol' => 'ciudadano',
            'is_active' => 1,
        ]);
    }
}