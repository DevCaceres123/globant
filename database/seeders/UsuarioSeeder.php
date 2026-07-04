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
        // ============================================================
        // 1) PERMISOS — se generan desde config/permisos.php (fuente única).
        //    Se aplana grupo -> modulo -> accion en strings "modulo.accion".
        //    El GRUPO no se guarda: es solo para agrupar en la vista.
        // ============================================================
        $nombresPermisos = [];

        foreach (config('permisos.estructura') as $grupo => $modulos) {
            foreach ($modulos as $modulo => $meta) {
                foreach ($meta['acciones'] as $accion) {
                    $nombre = "{$modulo}.{$accion}";           // usuario.crear, rol.ver...
                    $nombresPermisos[] = $nombre;

                    // firstOrCreate => idempotente: puedes correr el seeder varias veces.
                    Permission::firstOrCreate([
                        'name'       => $nombre,
                        'guard_name' => 'web',
                    ]);
                }
            }
        }

        // (Opcional) Sincronizar 1:1 con el config: borra permisos huérfanos
        // que ya NO están en config/permisos.php. Úsalo SOLO en desarrollo,
        // en producción es peligroso (puede dejar roles sin permisos).
        // Permission::whereNotIn('name', $nombresPermisos)->delete();

        // ============================================================
        // 2) ROLES
        // ============================================================
        // administrador: recibe TODOS los permisos. Al agregar un módulo
        // nuevo en el config, lo hereda solo (no hay que tocar este seeder).
        $administrador = Role::firstOrCreate([
            'name'       => 'administrador',
            'guard_name' => 'web',
            'descripcion' => 'Es para administrarar todos modulos',
            'color' => '#7b2233',
        ]);
        $administrador->syncPermissions(Permission::all());

        // general: rol base sin permisos. Sus permisos se asignan luego
        // desde el módulo de Roles (UI).
        $general = Role::firstOrCreate([
            'name'       => 'general',
            'guard_name' => 'web',
            'descripcion' => 'Es para modulos especificos',
            'color' => '#2f8a5b',
        ]);

        // ============================================================
        // 3) USUARIOS
        // ============================================================
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
