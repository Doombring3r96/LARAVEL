<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Cliente;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Hashear la contraseña una sola vez
        $hashedPassword = Hash::make('password123');

        // Insertar administradores
        User::create([
            'email' => 'admin1@studio137.com',
            'password' => $hashedPassword,
            'tipo' => 'administrador',
            'estado' => 'activo',
        ]);
        User::create([
            'email' => 'admin2@studio137.com',
            'password' => $hashedPassword,
            'tipo' => 'administrador',
            'estado' => 'activo',
        ]);
        User::create([
            'email' => 'admin3@studio137.com',
            'password' => $hashedPassword,
            'tipo' => 'administrador',
            'estado' => 'activo',
        ]);

        // Insertar trabajadores
        User::create([
            'email' => 'trabajador1@studio137.com',
            'password' => $hashedPassword,
            'tipo' => 'trabajador',
            'estado' => 'activo',
        ]);
        User::create([
            'email' => 'trabajador2@studio137.com',
            'password' => $hashedPassword,
            'tipo' => 'trabajador',
            'estado' => 'activo',
        ]);

        // Insertar cliente
        $clienteUser = User::create([
            'email' => 'cliente1@studio137.com',
            'password' => $hashedPassword,
            'tipo' => 'cliente',
            'estado' => 'activo',
        ]);

        // Relación en tabla clientes
        Cliente::create([
            'usuario_id' => $clienteUser->id,
            'nombre_empresa' => 'Tech Corp',
            'persona_contacto' => 'Carlos Pérez',
            'telefono_contacto' => '70000001',
            'deuda' => 150.00,
        ]);
    }
}
