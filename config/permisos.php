<?php

/*
|--------------------------------------------------------------------------
| Estructura de permisos del sistema (FUENTE ÚNICA)
|--------------------------------------------------------------------------
|
| Este archivo es la única fuente de verdad de los permisos. Lo consumen:
|
|   - El seeder (PermisoSeeder / UsuarioSeeder): aplana la estructura y crea
|     los permisos "modulo.accion" en la tabla `permissions` de Spatie.
|
|   - La vista del modal de roles (rol.blade.php): usa los 3 niveles
|     (grupo -> modulo -> acciones) para dibujar las pestañas agrupadas.
|
| Reglas de nombres:
|   - Un permiso se llama "recurso.accion" en minúscula singular.
|     Ej: usuario.ver, usuario.crear, rol.editar, afiliado.eliminar.
|   - Acciones estándar: ver, crear, editar, eliminar.
|       * "ver"     = abre el módulo y ve su listado (además oculta/muestra
|                     el ítem del menú con @can('modulo.ver')).
|       * "crear"   = registra nuevos.
|       * "editar"  = modifica y cambia estado.
|       * "eliminar"= elimina (soft delete).
|   - El GRUPO (Administrador, Gestión, Reportes) NO es un permiso: es solo
|     una etiqueta para agrupar visualmente en el modal. No se guarda en BD.
|
| Al agregar un módulo nuevo, agrégalo aquí una sola vez: queda sembrado y
| aparece en el modal de roles automáticamente.
|
*/

return [

    /*
    |--------------------------------------------------------------------------
    | Acciones estándar disponibles
    |--------------------------------------------------------------------------
    | Referencia del set de acciones permitidas. La vista puede usarlo para
    | pintar los checkboxes en un orden fijo.
    */
    'acciones' => [
        'ver'        => 'Ver',
        'crear'      => 'Crear',
        'editar'     => 'Editar',
        'eliminar'   => 'Eliminar',
        'desactivar' => 'Activar / Desactivar',
    ],

    /*
    |--------------------------------------------------------------------------
    | Íconos de cada grupo (para el encabezado de categoría en el modal)
    |--------------------------------------------------------------------------
    | Clase completa de Font Awesome por nombre de grupo. Solo lo usa la vista;
    | el seeder lo ignora.
    */
    'grupos' => [
        'Resumen'        => 'fas fa-home',
        'Administrador' => 'fas fa-user-shield',
        // 'Gestión'       => 'fas fa-briefcase',
        // 'Reportes'      => 'fas fa-chart-line',
    ],

    /*
    |--------------------------------------------------------------------------
    | Estructura: grupo => [ modulo => ['acciones...'] ]
    |--------------------------------------------------------------------------
    | Metadatos de cada módulo para la UI (etiqueta + icono de Font Awesome)
    | y sus acciones habilitadas.
    */
    'estructura' => [
        'Resumen' => [
            'inicio' => [
                'etiqueta' => 'Inicio',
                'icono'    => 'fas fa-home',
                'acciones' => [],
            ],
        ],

        'Administrador' => [
            'usuario' => [
                'etiqueta' => 'Usuarios',
                'icono'    => 'fas fa-users',
                'acciones' => ['ver', 'crear', 'editar', 'eliminar', 'desactivar'],
            ],
            'rol' => [
                'etiqueta' => 'Roles',
                'icono'    => 'fas fa-user-tag',
                'acciones' => ['ver', 'crear', 'editar', 'eliminar'],
            ],
            'permiso' => [
                'etiqueta' => 'Permisos',
                'icono'    => 'fas fa-key',
                'acciones' => ['ver'],
            ],
        ],

        // 'Gestión' => [
        //     'afiliado' => [
        //         'etiqueta' => 'Afiliados',
        //         'icono'    => 'fas fa-id-card-alt',
        //         'acciones' => ['ver', 'crear', 'editar', 'eliminar'],
        //     ],
        // ],

        // 'Reportes' => [
        //     'reporte' => [
        //         'etiqueta' => 'Reportes',
        //         'icono'    => 'fas fa-chart-line',
        //         'acciones' => ['ver'],
        //     ],
        // ],

    ],

];
