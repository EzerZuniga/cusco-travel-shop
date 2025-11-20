<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use Illuminate\Support\Facades\Hash;

class UsuarioSeeder extends Seeder
{
    /**
     * Ejecutar los seeders de la base de datos
     */
    public function run(): void
    {
        $usuarios = [
            [
                'nombre' => 'Admin Usuario',
                'email' => 'admin@cusco-travel.com',
                'password' => Hash::make('password'),
                'telefono' => '+51 999 999 999',
            ],
            [
                'nombre' => 'Juan Pérez',
                'email' => 'juan@example.com',
                'password' => Hash::make('password'),
                'telefono' => '+51 987 654 321',
            ],
            [
                'nombre' => 'María García',
                'email' => 'maria@example.com',
                'password' => Hash::make('password'),
                'telefono' => '+51 987 654 322',
            ],
        ];

        foreach ($usuarios as $usuario) {
            Usuario::create($usuario);
        }
    }
}
