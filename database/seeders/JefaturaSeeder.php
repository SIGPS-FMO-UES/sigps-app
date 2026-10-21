<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class JefaturaSeeder extends Seeder
{
    public function run(): void
    {
        $jefatura = User::updateOrCreate(
            ['nombre_usuario' => 'jefatura'],
            [
                'nombre_completo' => 'Jefatura UPS de prueba',
                'email' => 'jefatura@ues.edu.sv',
                'password' => '12345',
                'activo' => true,
            ]
        );

        $jefatura->syncRoles(['jefe_ups']);
    }
}
