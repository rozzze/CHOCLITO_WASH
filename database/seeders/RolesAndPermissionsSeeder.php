<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // <-- Importante para hashear la clave

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Resetea los roles y permisos cacheados
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // --- 1. CREACIÓN DE ROLES ---
        $this->command->info('Creando roles...');
        
        $roleAdmin = Role::create(['name' => 'admin']);
        $roleCliente = Role::create(['name' => 'cliente']);
        $roleOperario = Role::create(['name' => 'operario']);
        $roleRepartidor = Role::create(['name' => 'repartidor']);
        
        $this->command->info('Roles creados.');

        // --- 2. CREACIÓN DE USUARIOS DE PRUEBA Y ASIGNACIÓN ---
        $this->command->info('Creando usuarios de prueba...');
        
        // La clave para todos será: 'password'
        $password = Hash::make('password');

        // Usuario Administrador
        $admin = User::create([
            'name' => 'Administrador Choclito',
            'email' => 'admin@admin.com',
            'password' => $password
        ]);
        $admin->assignRole($roleAdmin);

        // Usuario Cliente
        $cliente = User::create([
            'name' => 'Juan Cliente Fiel',
            'email' => 'cliente@choclito.wash',
            'password' => $password
        ]);
        $cliente->assignRole($roleCliente);

        // Usuario Operario
        $operario = User::create([
            'name' => 'Omar Operario',
            'email' => 'operario@choclito.wash',
            'password' => $password
        ]);
        $operario->assignRole($roleOperario);

        // Usuario Repartidor
        $repartidor = User::create([
            'name' => 'Raul Repartidor',
            'email' => 'repartidor@choclito.wash',
            'password' => $password
        ]);
        $repartidor->assignRole($roleRepartidor);

        $this->command->info('¡Seeder de Roles y Usuarios ejecutado con éxito!');
        $this->command->warn('Todos los usuarios de prueba tienen la contraseña: "password"');
    }
}