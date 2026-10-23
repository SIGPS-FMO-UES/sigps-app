<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $usuario = User::updateOrCreate(
            ['nombre_usuario' => 'admin'],
            [
                'nombre_completo' => 'Administrador UPS',
                'email' => 'admin@ues.edu.sv',
                'password' => '12345',
                'activo' => true,
            ]
        );

        $usuario->syncRoles(['jefe_ups']);
    }
}
