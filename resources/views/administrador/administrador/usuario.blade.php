@extends('administrador.dashboard')

@section('title', 'Modulo de usuarios')


@section('content_header')
<div class="d-flex align-items-center justify-content-between">
    <div>
        <h1 class="m-0"><i class="fas fa-users txt-gold mr-2"></i>Usuarios</h1>
        <small class="text-muted">Administración de cuentas y accesos del sistema</small>
    </div>
    <ol class="breadcrumb bg-transparent p-0 m-0">
        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Usuarios</li>
    </ol>
</div>
@stop

@section('modulo')

{{-- ===================== TARJETAS DE RESUMEN ===================== --}}
<div class="row">
    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card">
            <div class="ic ic-navy"><i class="fas fa-users"></i></div>
            <div class="meta">
                <div class="label">Total usuarios</div>
                <div class="value">{{ $totalUsuarios }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card t-green">
            <div class="ic ic-green"><i class="fas fa-user-check"></i></div>
            <div class="meta">
                <div class="label">Activos</div>
                <div class="value">{{ $totalUsuariosActivos }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card t-red">
            <div class="ic ic-red"><i class="fas fa-user-slash"></i></div>
            <div class="meta">
                <div class="label">Inactivos</div>
                <div class="value">{{ $totalUsuariosInactivos }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card t-gold">
            <div class="ic ic-gold"><i class="fas fa-user-tag"></i></div>
            <div class="meta">
                <div class="label">Roles</div>
                <div class="value">{{ $totalRoles }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== TABLA PRINCIPAL ===================== --}}
<div class="card">
    <div class="card-header card-cab">
        <h3 class="card-title mt-1">
            <i class="fas fa-list mr-1"></i> Listado de usuarios
        </h3>
        <div class="card-tools d-flex align-items-center" style="gap:.5rem">
            <select id="filtro_estado" name="filtro_estado" class="form-control form-control-sm" style="width:auto">
                <option value="">Todos los estados</option>
                <option value="activo">Activos</option>
                <option value="inactivo">Inactivos</option>
            </select>
            @can('usuario.crear')
                <button type="button" class="btn btn-navy btn-sm" id="btn_nuevo_usuario" data-toggle="modal"
                    data-target="#modal_usuario">
                    <i class="fas fa-plus mr-1"></i> Nuevo usuario
                </button>
            @endcan
        </div>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="tabla_usuarios" class="table table-hover w-100 table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="width: 40px">#</th>
                        <th>Nombre Completo</th>
                        <th>CI</th>
                        <th>Acceso</th>
                        <th>Rol</th>
                        <th class="text-center" style="width: 100px">Estado</th>
                        <th class="text-center" style="width: 110px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Filas de ejemplo para visualizar el diseño. Reemplazar por @foreach($usuarios as $usuario) --}}
                    {{-- <tr>
                        <td>1</td>
                        <td>
                            <div class="celda-usuario">
                                <span class="avatar-ini">AA</span>
                                <div>
                                    <div class="nombre">Admin admin admin</div>
                                    <div class="correo">admin@gmail.com</div>
                                </div>
                            </div>
                        </td>
                        <td>1234567890</td>
                        <td><span class="user-tag">admin</span></td>
                        <td><span class="badge-rol">administrador</span></td>
                        <td class="text-center"><span class="badge-soft activo">Activo</span></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn-accion editar btn-editar" title="Editar" data-toggle="modal"
                                    data-target="#modal_usuario">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-accion eliminar btn-eliminar ml-1" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>
                            <div class="celda-usuario">
                                <span class="avatar-ini">JP</span>
                                <div>
                                    <div class="nombre">Juan Pérez Gómez</div>
                                    <div class="correo">jperez@gmail.com</div>
                                </div>
                            </div>
                        </td>
                        <td>9876543210</td>
                        <td><span class="user-tag">jperez</span></td>
                        <td><span class="badge-rol sec">operador</span></td>
                        <td class="text-center"><span class="badge-soft inactivo">Inactivo</span></td>
                        <td class="text-center">
                            <div class="btn-group">
                                <button class="btn-accion editar btn-editar" title="Editar" data-toggle="modal"
                                    data-target="#modal_usuario">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn-accion eliminar btn-eliminar ml-1" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr> --}}
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ===================== MODAL CREAR / EDITAR USUARIO ===================== --}}
<div class="modal fade" id="modal_usuario" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <form id="formulario_usuario" autocomplete="off">

                <input type="hidden" name="id" id="usuario_id">

                <div class="modal-header modal-cab">

                    <h5 class="modal-title" id="modal_titulo">
                        <i class="fas fa-user-plus mr-2"></i> Nuevo usuario
                    </h5>
                    <span class="text-light">Campos obligatorios <strong class="">(*)</strong></span>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    {{-- ===== Sección: datos personales ===== --}}
                    <div class="seccion-form mb-4">
                        <div class="seccion-head">
                            <span class="seccion-ic"><i class="fas fa-id-card"></i></span>
                            <div>
                                <span class="seccion-titulo">Datos personales</span>
                                <small class="seccion-sub">Información de identidad del usuario</small>
                            </div>
                        </div>
                        <div class="seccion-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="nombres">Nombres <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-user"></i></span></div>
                                        <input type="text" class="form-control" name="nombres" id="nombres"
                                            placeholder="Ingrese los nombres">
                                    </div>
                                    <div id="_nombres"></div>


                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="apellidos">Apellidos <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-user"></i></span></div>
                                        <input type="text" class="form-control" name="apellidos" id="apellidos"
                                            placeholder="Ingrese los apellidos">
                                    </div>
                                    <div id="_apellidos"></div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="ci">Carnet de identidad (CI) <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-address-card"></i></span></div>
                                        <input type="text" class="form-control" name="ci" id="ci"
                                            placeholder="Ingrese el CI">
                                    </div>
                                    <div id="_ci"></div>
                                </div>
                                <div class="col-md-6 form-group mb-0">
                                    <label for="email">Correo electrónico <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-envelope"></i></span></div>
                                        <input type="email" class="form-control" name="email" id="email"
                                            placeholder="correo@ejemplo.com">
                                    </div>
                                    <div id="_email"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ===== Sección: datos de acceso ===== --}}
                    <div class="seccion-form">
                        <div class="seccion-head">
                            <span class="seccion-ic"><i class="fas fa-lock"></i></span>
                            <div>
                                <span class="seccion-titulo">Datos de acceso</span>
                                <small class="seccion-sub">Credenciales y permisos del sistema</small>
                            </div>
                        </div>
                        <div class="seccion-body">
                            <div class="row">
                                <div class="col-md-6 form-group">
                                    <label for="usuario">Usuario <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-at"></i></span></div>
                                        <input type="text" class="form-control" name="usuario" id="usuario"
                                            placeholder="Nombre para iniciar sesión">
                                    </div>
                                    <div id="_usuario"></div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="password">Contraseña <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-key"></i></span></div>
                                        <input type="password" class="form-control" name="password" id="password"
                                            placeholder="Ingrese la contraseña">
                                        <div class="input-group-append" id="toggle_password" style="cursor:pointer">
                                            <span class="input-group-text"><i class="fas fa-eye-slash"
                                                    id="icono_password"></i></span>
                                        </div>
                                    </div>
                                    <div id="_password"></div>
                                </div>
                                <div class="col-md-6 form-group">
                                    <label for="rol">Rol <span class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-user-tag"></i></span></div>
                                        <select class="form-control" name="rol" id="rol">
                                            <option disabled selected>Seleccione un rol...</option>
                                            @foreach($rol as $r)
                                                <option value="{{ $r->name }}">{{ ucfirst($r->name) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div id="_rol"></div>
                                </div>
                                <div class="col-md-6 form-group mb-0">
                                    <label for="estado">Estado</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i
                                                    class="fas fa-toggle-on"></i></span></div>
                                        <select class="form-control" name="estado" id="estado">
                                            <option value="activo">Activo</option>
                                            <option value="inactivo">Inactivo</option>
                                        </select>
                                    </div>
                                    <div id="_estado"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i> Cancelar
                    </button>
                    <button type="submit" class="btn btn-navy" id="btn_guardar_usuario">
                        <i class="fas fa-save mr-1"></i> Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="{{ asset('assets/js/administrador/usuario/usuario.js') }}" type="module"></script>
@stop