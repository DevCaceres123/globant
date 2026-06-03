<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsuarioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rol1       = new Role();
        $rol1->name = 'administrador';
        $rol1->save();

        $usuario = new User();
        $usuario->usuario = 'admin';
        $usuario->password = Hash::make('1234');
        $usuario->ci = '1234567890';
        $usuario->nombres = 'Admin';
        $usuario->apellidos = 'admin admin';
        $usuario->estado = 'activo';
        $usuario->email = 'admin@gmail.com';
        $usuario->save();

        $usuario->syncRoles(['administrador']);


        $usuario1 = new User();
        $usuario1->usuario = 'prueba';
        $usuario1->password = Hash::make('1234');
        $usuario1->ci = '123456789';
        $usuario1->nombres = 'pepe manuel';
        $usuario1->apellidos = 'gonzales quispe';
        $usuario1->estado = 'activo';
        $usuario1->email = 'prueba@gmail.com';
        $usuario1->save();
        $usuario1->syncRoles(['administrador']);
    }
}
