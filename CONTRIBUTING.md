# Guía de contribución — Sistema de Afiliados (Globant SRL)

Esta guía explica **cómo trabajar en este proyecto** para que, al ser varios
desarrolladores, todos sigamos la misma estructura y el código se mantenga
ordenado y predecible.

> 📌 **Regla de oro:** antes de crear algo nuevo, mira cómo está hecho un módulo
> que ya existe (el de **Usuarios** es la referencia) y cópialo. No inventes
> estructuras nuevas.

---

## Tabla de contenido

1. [Convenciones generales](#1-convenciones-generales)
2. [Estructura de carpetas](#2-estructura-de-carpetas)
3. [Cómo crear un módulo nuevo (paso a paso)](#3-cómo-crear-un-módulo-nuevo-paso-a-paso)
   - 3.1 [Controlador](#31-controlador)
   - 3.2 [Rutas](#32-rutas)
   - 3.3 [Vista (Blade)](#33-vista-blade)
   - 3.4 [JavaScript del módulo](#34-javascript-del-módulo)
   - 3.5 [Ítem en el menú lateral](#35-ítem-en-el-menú-lateral)
4. [Helpers de JavaScript reutilizables](#4-helpers-de-javascript-reutilizables)
5. [Permisos y roles (Spatie)](#5-permisos-y-roles-spatie)
6. [Flujo de Git y commits](#6-flujo-de-git-y-commits)
7. [Checklist antes de subir cambios](#7-checklist-antes-de-subir-cambios)

---

## 1. Convenciones generales

- **Idioma:** TODO en **español** — nombres de rutas, métodos, variables,
  comentarios y mensajes de commit. (Las palabras propias de Laravel como
  `index`, `store`, `Request` se quedan en inglés porque son del framework).
- **Login por `usuario`**, no por email. Las respuestas de login/CRUD viajan en
  **JSON** con la forma `{ tipo, mensaje }`.
- **PHP:** seguir el estándar de Laravel. Antes de subir, formatear con Pint:
  ```bash
  ./vendor/bin/pint
  ```
- **Nomenclatura:**
  | Elemento            | Convención            | Ejemplo                          |
  |---------------------|-----------------------|----------------------------------|
  | Controlador         | `PascalCase` + `Controller` | `AfiliadoController`       |
  | Método de listado   | `index`               | `index()`                        |
  | Endpoint DataTable  | `listar` + Plural     | `listarAfiliados()`              |
  | Nombre de ruta      | `camelCase` español   | `->name('afiliados')`            |
  | Vista               | `snake_case`          | `afiliado.blade.php`             |
  | Archivo JS          | igual que la vista    | `afiliado.js`                    |
  | Tabla HTML (id)     | `tabla_` + plural     | `id="tabla_afiliados"`           |
  | Formulario (id)     | `formulario_` + nombre| `id="formulario_afiliado"`       |
  | Modal (id)          | `modal_` + nombre     | `id="modal_afiliado"`            |

---

## 2. Estructura de carpetas

Los controladores se agrupan **por módulo** (carpeta por área del sistema):

```
app/Http/Controllers/
├── Auth/
│   └── LoginController.php
├── Resumen/
│   └── InicioController.php
└── Administrador/
    └── Usuario/
        └── UsuarioController.php      ← namespace = ruta de la carpeta
```

Las vistas siguen la misma idea, bajo `administrador/`:

```
resources/views/
├── auth/
│   └── login.blade.php
└── administrador/
    ├── dashboard.blade.php            ← layout base (NO tocar al crear módulos)
    ├── inicio.blade.php
    ├── plantilla_admin/               ← parciales compartidos (style, script, salir)
    └── administrador/
        └── usuario.blade.php          ← vista del módulo
```

El JavaScript de cada vista va en `public/assets/js/<modulo>/<vista>/`:

```
public/assets/js/
├── auth/login.js
└── administrador/usuario/usuario.js
```

Y los **helpers reutilizables** (compartidos por todos los módulos) viven en:

```
public/funciones_helper/
├── notificaciones/mensajes.js          → mensajeAlerta()
├── operaciones_crud/crud.js            → crud()
├── vistas/formulario.js                → vaciar_formulario(), vaciar_errores()
├── vistas/tabla.js
├── operaciones/funciones.js
└── validar_formulario/validar_formulario.js
```

---

## 3. Cómo crear un módulo nuevo (paso a paso)

Como ejemplo crearemos un módulo de **Afiliados**. Reemplaza `Afiliado/afiliado`
por el nombre de tu módulo en todos los pasos.

### 3.1 Controlador

Crea el archivo en `app/Http/Controllers/Administrador/Afiliado/AfiliadoController.php`.
El patrón es: **`index()` devuelve la vista** y **`listarAfiliados()` devuelve el
JSON que consume el DataTable** (server-side).

```php
<?php

namespace App\Http\Controllers\Administrador\Afiliado;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// Librerías del controlador
use Exception;
use App\Models\Afiliado;

class AfiliadoController extends Controller
{
    /**
     * Muestra la vista principal del módulo.
     */
    public function index()
    {
        return view('administrador.afiliado.afiliado');
    }

    /**
     * Devuelve los datos para el DataTable (server-side).
     */
    public function listarAfiliados(Request $request)
    {
        $query = Afiliado::select('id', 'nombres', 'apellidos', 'ci', 'estado')
            ->orderBy('id', 'desc');

        // Búsqueda global del DataTable
        if (!empty($request->search['value'])) {
            $busqueda = $request->search['value'];
            $query->where(function ($q) use ($busqueda) {
                $q->where('nombres', 'like', "%{$busqueda}%")
                  ->orWhere('apellidos', 'like', "%{$busqueda}%")
                  ->orWhere('ci', 'like', "%{$busqueda}%");
            });
        }

        $recordsTotal = $query->count();
        $datos = $query->skip($request->start)->take($request->length)->get();

        return response()->json([
            'draw'            => $request->draw,
            'recordsTotal'    => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data'            => $datos,
            'permisos'        => [
                'editar'   => auth()->user()->can('afiliado.editar'),
                'eliminar' => auth()->user()->can('afiliado.eliminar'),
                'estado'   => auth()->user()->can('afiliado.desactivar'),
            ],
        ]);
    }

    /**
     * Guarda un nuevo registro. Responde JSON { tipo, mensaje }.
     */
    public function store(Request $request)
    {
        try {
            $datos = $request->validate([
                'nombres'   => ['required', 'string', 'max:100'],
                'apellidos' => ['required', 'string', 'max:100'],
                'ci'        => ['required', 'string', 'unique:afiliados,ci'],
            ]);

            Afiliado::create($datos);

            return response()->json([
                'tipo'    => 'success',
                'mensaje' => 'Afiliado registrado correctamente.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // 422 → el front muestra los errores campo por campo
            return response()->json(['errors' => $e->errors()], 422);
        } catch (Exception $e) {
            return response()->json(['tipo' => 'error', 'mensaje' => $e->getMessage()]);
        }
    }

    public function update(Request $request, string $id)
    {
        // Misma idea que store(), pero con Afiliado::findOrFail($id)->update(...)
    }

    public function destroy(string $id)
    {
        // Afiliado::findOrFail($id)->delete();  → responder { tipo, mensaje }
    }
}
```

> 💡 La validación que falla devuelve **HTTP 422** con `{ errors: {...} }`. El
> helper `crud()` está preparado para no romperse con el 422 y dejar que el front
> pinte los errores (ver helpers más abajo).

### 3.2 Rutas

En `routes/web.php`, agrega las rutas **dentro del grupo `/admin` con middleware
`auth`**, usando `Route::controller(...)->group(...)`:

```php
use App\Http\Controllers\Administrador\Afiliado\AfiliadoController;

// ... dentro de Route::prefix('/admin')->middleware('auth')->group(...)

// RUTAS PARA EL MÓDULO DE AFILIADOS
Route::controller(AfiliadoController::class)->group(function () {
    Route::get('afiliados', 'index')->name('afiliados');
    Route::get('listarAfiliados', 'listarAfiliados')->name('listarAfiliados');
    Route::post('afiliados', 'store')->name('afiliados.store');
    Route::put('afiliados/{id}', 'update')->name('afiliados.update');
    Route::delete('afiliados/{id}', 'destroy')->name('afiliados.destroy');
});
```

### 3.3 Vista (Blade)

Crea `resources/views/administrador/afiliado/afiliado.blade.php`. **Siempre**
extiende `administrador.dashboard` y usa estas secciones:

```blade
@extends('administrador.dashboard')

@section('title', 'Módulo de afiliados')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0"><i class="fas fa-id-card txt-gold mr-2"></i>Afiliados</h1>
            <small class="text-muted">Gestión de afiliados del sistema</small>
        </div>
        <ol class="breadcrumb bg-transparent p-0 m-0">
            <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
            <li class="breadcrumb-item active">Afiliados</li>
        </ol>
    </div>
@stop

@section('modulo')

    {{-- ===== TABLA PRINCIPAL ===== --}}
    <div class="card">
        <div class="card-header card-cab">
            <h3 class="card-title mt-1"><i class="fas fa-list mr-1"></i> Listado de afiliados</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-navy btn-sm" id="btn_nuevo_afiliado"
                    data-toggle="modal" data-target="#modal_afiliado">
                    <i class="fas fa-plus mr-1"></i> Nuevo afiliado
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="tabla_afiliados" class="table table-hover w-100">
                    <thead>
                        <tr>
                            <th style="width: 40px">#</th>
                            <th>Nombre completo</th>
                            <th>CI</th>
                            <th class="text-center" style="width: 100px">Estado</th>
                            <th class="text-center" style="width: 110px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== MODAL CREAR / EDITAR ===== --}}
    <div class="modal fade" id="modal_afiliado" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formulario_afiliado" autocomplete="off">
                    @csrf
                    <input type="hidden" name="id" id="afiliado_id">

                    <div class="modal-header modal-cab">
                        <h5 class="modal-title" id="modal_titulo">
                            <i class="fas fa-user-plus mr-2"></i> Nuevo afiliado
                        </h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nombres">Nombres</label>
                            <input type="text" class="form-control" name="nombres" id="nombres">
                            {{-- Contenedor de error: id = "_" + nombre del campo --}}
                            <div id="_nombres"></div>
                        </div>
                        <div class="form-group">
                            <label for="apellidos">Apellidos</label>
                            <input type="text" class="form-control" name="apellidos" id="apellidos">
                            <div id="_apellidos"></div>
                        </div>
                        <div class="form-group mb-0">
                            <label for="ci">CI</label>
                            <input type="text" class="form-control" name="ci" id="ci">
                            <div id="_ci"></div>
                        </div>
                    </div>

                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            <i class="fas fa-times mr-1"></i> Cancelar
                        </button>
                        <button type="submit" class="btn btn-navy" id="btn_guardar_afiliado">
                            <i class="fas fa-save mr-1"></i> Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="{{ asset('assets/js/administrador/afiliado/afiliado.js') }}" type="module"></script>
@stop
```

> ⚠️ **Importante sobre los errores:** el contenedor donde se muestra el error de
> un campo debe tener `id="_<nombre_del_campo>"` (ej: `_nombres`). Así lo espera
> el helper `mensajeAlerta(errores, 'errores')`.

> 🎨 Las clases de diseño (`stat-card`, `badge-soft`, `badge-rol`, `btn-navy`,
> `card-cab`, etc.) ya están definidas en
> `resources/views/administrador/plantilla_admin/style.blade.php`. Reutilízalas;
> no inventes estilos nuevos salvo que sea necesario.

### 3.4 JavaScript del módulo

Crea `public/assets/js/administrador/afiliado/afiliado.js`. Se carga como
**`type="module"`**, por lo que puede importar los helpers compartidos:

```js
import { mensajeAlerta } from "../../../../funciones_helper/notificaciones/mensajes.js";
import { crud } from "../../../../funciones_helper/operaciones_crud/crud.js";
import { vaciar_errores, vaciar_formulario } from "../../../../funciones_helper/vistas/formulario.js";

let permisosGlobal;
let tabla;

$(document).ready(function () {
    listar_datos();
});

// 1) Cargar la tabla (server-side)
function listar_datos() {
    tabla = $("#tabla_afiliados").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "listarAfiliados",
            type: "GET",
            dataSrc: function (json) {
                permisosGlobal = json.permisos;
                return json.data;
            },
        },
        columns: [
            { data: null, className: "table-td", render: (d, t, r, meta) => meta.row + 1 },
            { data: null, render: (d, t, r) => `${r.nombres} ${r.apellidos}` },
            { data: "ci" },
            { data: "estado", className: "text-center" },
            {
                data: null,
                className: "text-center",
                render: (d, t, r) => `
                    <a class="btn btn-sm btn-outline-warning editar_afiliado" data-id="${r.id}">
                        <i class="fas fa-pencil-alt"></i>
                    </a>
                    <a class="btn btn-sm btn-outline-danger eliminar_afiliado" data-id="${r.id}">
                        <i class="fas fa-trash"></i>
                    </a>`,
            },
        ],
    });
}

function actualizarTabla() {
    tabla.ajax.reload(null, false);
}

// 2) Botón "Nuevo": limpia el formulario
document.getElementById("btn_nuevo_afiliado").addEventListener("click", function () {
    vaciar_formulario("formulario_afiliado");
    vaciar_errores("formulario_afiliado");
    document.getElementById("afiliado_id").value = "";
    document.getElementById("modal_titulo").innerHTML =
        '<i class="fas fa-user-plus mr-2"></i> Nuevo afiliado';
});

// 3) Guardar (crear o editar) usando el helper crud()
document.getElementById("formulario_afiliado").addEventListener("submit", function (e) {
    e.preventDefault();
    vaciar_errores("formulario_afiliado");

    const id = document.getElementById("afiliado_id").value;
    const datos = Object.fromEntries(new FormData(this).entries());

    // crud(url, metodo, idRegistro, datos, callback)
    const metodo = id ? "PUT" : "POST";
    crud("afiliados", metodo, id || null, datos, function (error, respuesta) {
        if (error) {
            mensajeAlerta("Ocurrió un error", "error");
            return;
        }
        // Errores de validación (HTTP 422)
        if (respuesta.errors) {
            mensajeAlerta(respuesta.errors, "errores");
            return;
        }
        $("#modal_afiliado").modal("hide");
        mensajeAlerta(respuesta.mensaje, respuesta.tipo); // "success"
        actualizarTabla();
    });
});
```

### 3.5 Ítem en el menú lateral

Agrega el enlace en el menú de AdminLTE, en `config/adminlte.php` dentro del
array `'menu'`:

```php
['header' => 'GESTIÓN'],
[
    'text'  => 'Afiliados',
    'route' => 'afiliados',   // nombre de la ruta (no la URL)
    'icon'  => 'fas fa-id-card',
    // 'can' => 'afiliado.ver', // (opcional) ocultarlo según permiso
],
```

---

## 4. Helpers de JavaScript reutilizables

Viven en `public/funciones_helper/`. Úsalos en vez de duplicar lógica:

| Helper | Importar desde | Para qué sirve |
|--------|----------------|----------------|
| `crud(url, metodo, id, datos, callback)` | `operaciones_crud/crud.js` | Hace `fetch` con CSRF a `/{url}` o `/{url}/{id}`. Soporta `GET`, `POST`, `PUT`. No revienta con el 422. |
| `mensajeAlerta(mensaje, tipo)` | `notificaciones/mensajes.js` | Notificaciones con SweetAlert. `tipo`: `success`, `error`, `info`, `warning`, o `errores` (pinta errores de validación en los `div#_campo`). |
| `vaciar_formulario(idForm)` | `vistas/formulario.js` | Limpia todos los campos de un formulario. |
| `vaciar_errores(idForm)` | `vistas/formulario.js` | Borra los mensajes de error mostrados. |

**Firma de `crud()`** (importante):

```js
crud(url, metodo, idRegistro = null, datos = null, callback)
//   "afiliados", "POST",  null/id,        {obj},        (err, resp) => {}
```

- `POST` sin id → **crear**.
- `PUT` con id → **actualizar** (pega a `/afiliados/{id}`).
- `GET` con id → **traer un registro**.
- `GET` sin id ni datos → **listar**.

> El CSRF lo lee solo desde `<meta name="csrf-token">`, que ya está en el layout.

---

## 5. Permisos y roles (Spatie)

- Se usa **spatie/laravel-permission**. El `User` usa el trait `HasRoles`.
- En el controlador, verifica permisos con `auth()->user()->can('modulo.accion')`
  y manda esos permisos al front dentro del JSON del DataTable (campo `permisos`),
  como hace `UsuarioController::listarUsuarios`.
- Nomenclatura sugerida de permisos: `<modulo>.<accion>` →
  `afiliado.ver`, `afiliado.editar`, `afiliado.eliminar`, `afiliado.desactivar`.
- Los permisos/roles se siembran en los **seeders** (`database/seeders/`). El
  admin por defecto es `usuario: admin` / `password: 1234`.

> 🧹 **Pendiente conocido:** en `UsuarioController` aún hay permisos con prefijo
> `sede.*` heredados de otro proyecto. Cuando trabajes ese módulo, renómbralos a
> `usuario.*`.

---

## 6. Flujo de Git y commits

### Ramas
- `main` → rama estable. **No se trabaja directo sobre `main`.**
- Crea una rama por tarea:
  ```bash
  git checkout -b feat/modulo-afiliados
  git checkout -b fix/login-validacion
  ```
- Al terminar, abre un **Pull Request** hacia `main` y pide revisión a otro
  compañero antes de fusionar.

### Mensajes de commit (Conventional Commits, en español)

Formato: `<tipo>: <descripción corta en minúscula>`

| Tipo | Cuándo usarlo |
|------|---------------|
| `feat`   | Una funcionalidad nueva |
| `fix`    | Corrección de un error |
| `style`  | Cambios de diseño/CSS o formato (sin lógica) |
| `refactor` | Reorganizar código sin cambiar comportamiento |
| `docs`   | Documentación (README, esta guía, comentarios) |
| `chore`  | Tareas varias (config, dependencias) |

Ejemplos:
```
feat: módulo de afiliados con listado y registro
fix: corrige validación de CI duplicado en afiliados
docs: agrega guía de contribución para el equipo
```

> Mantén el **tipo en minúscula** y un solo cambio lógico por commit. Evita
> commits gigantes con muchas cosas mezcladas.

---

## 7. Checklist antes de subir cambios

- [ ] El controlador sigue el patrón `index()` + `listarX()` + (`store/update/destroy`).
- [ ] Las rutas están dentro del grupo `/admin` con middleware `auth` y tienen `->name()`.
- [ ] La vista extiende `administrador.dashboard` y usa `@section('modulo')` y `@section('js')`.
- [ ] El JS está en `public/assets/js/<modulo>/<vista>/` y carga con `type="module"`.
- [ ] Reutilicé los helpers (`crud`, `mensajeAlerta`, `vaciar_*`) en vez de duplicar.
- [ ] Agregué el ítem al menú en `config/adminlte.php`.
- [ ] Formateé el PHP: `./vendor/bin/pint`.
- [ ] Probé el flujo completo (listar, crear, editar, eliminar).
- [ ] El commit usa el formato `tipo: descripción` y estoy en una rama (no en `main`).

---

¿Dudas con la estructura? Mira el **módulo de Usuarios** (`UsuarioController`,
`usuario.blade.php`, `usuario.js`) — es el ejemplo vivo de todo lo descrito aquí.
