@extends('administrador.dashboard')

@section('title', 'Módulo de roles')

{{-- Estilos propios del módulo de roles --}}
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/administrador/rol.css') }}">
@stop

@section('content_header')
<div class="d-flex align-items-center justify-content-between">
    <div>
        <h1 class="m-0"><i class="fas fa-user-shield txt-gold mr-2"></i>Roles</h1>
        <small class="text-muted">Administración de roles y permisos del sistema</small>
    </div>
    <ol class="breadcrumb bg-transparent p-0 m-0">
        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Roles</li>
    </ol>
</div>
@stop

@section('modulo')

{{-- ===================== TARJETAS DE ROLES ===================== --}}
<div class="card">
    <div class="card-header card-cab">
        <h3 class="card-title mt-1">
            <i class="fas fa-list mr-1"></i> Listado de roles
        </h3>
        <div class="card-tools d-flex align-items-center" style="gap:.5rem">
            <button type="button" class="btn btn-navy btn-sm" id="btn_nuevo_rol" data-toggle="modal"
                data-target="#modal_rol">
                <i class="fas fa-plus mr-1"></i> Nuevo rol
            </button>
        </div>
    </div>

    <div class="card-body">
        {{-- Grid de tarjetas de roles --}}
        <div class="row" id="contenedor_roles">
            @forelse($roles ?? [] as $rol)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                    {{-- El color del rol (si existe) alimenta la franja e ícono vía variables CSS --}}
                    <div class="rol-card" @if(!empty($rol->color))
                    style="--rol-color: {{ $rol->color }}; --rol-color-bg: {{ $rol->color }};" @endif>

                        {{-- Cabecera: ícono + nombre --}}
                        <div class="rol-card-header">
                            <div class="rol-card-icon {{ empty($rol->color) ? 'icon-default' : '' }}">
                                <i class="text-light fas fa-user-shield"></i>
                            </div>
                            <div class="rol-card-titulo">
                                <h5 class="text-capitalize">{{ $rol->name }}</h5>
                                <span class="rol-card-sub">{{ $rol->descripcion ?? 'Sin descripción' }}</span>
                            </div>
                        </div>

                        {{-- Cuerpo: permisos --}}
                        <div class="rol-card-body">
                            <div class="permisos-head">
                                <span class="titulo"><i class="fas fa-lock"></i>Permisos</span>
                                <span class="permisos-count">
                                    {{ $rol->permissions_count ?? 0 }}
                                </span>
                            </div>
                            <div class="permisos-container">
                                @php
                                    // Mostrar solo los primeros N para que todas las cards queden uniformes
                                    $permisosRol = collect($rol->permissions ?? []);
                                    $limitePermisos = 6;
                                    $permisosExtra = max(0, $permisosRol->count() - $limitePermisos);
                                @endphp
                                @forelse($permisosRol->take($limitePermisos) as $permiso)
                                    <span class="permiso-badge active">
                                        <i class="fas fa-check-circle }}"></i>
                                        {{ $permiso->name }}
                                    </span>
                                @empty
                                    <span class="permisos-vacio">Sin permisos asignados</span>
                                @endforelse

                                @if($permisosExtra > 0)
                                    <span class="permiso-badge mas">+{{ $permisosExtra }} más</span>
                                @endif
                            </div>
                        </div>

                        {{-- Footer: fecha + acciones rápidas --}}
                        <div class="rol-card-footer">
                            <span class="fecha">
                                <i
                                    class="far fa-clock"></i>{{ $rol->updated_at ? $rol->updated_at->diffForHumans() : 'Nuevo' }}
                            </span>
                            <div class="acciones-rapidas">
                                <button class="btn-accion editar-rol" data-id="{{ $rol->id }}" title="Editar">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button class="btn-accion ver-rol" data-id="{{ $rol->id }}" title="Visualizar">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn-accion eliminar-rol" data-id="{{ $rol->id }}" title="Eliminar">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="roles-vacio">
                        <div class="ic"><i class="fas fa-user-shield"></i></div>
                        <h5>No hay roles registrados</h5>
                        <p class="mb-0">Haz clic en <strong>"Nuevo rol"</strong> para crear el primero.</p>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>


{{-- ===================== MODAL PARA ROL ===================== --}}
<div class="modal fade" id="modal_rol" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header modal-cab">
                <h5 class="modal-title" id="modal_rol_titulo">
                    <i class="fas fa-user-shield mr-2"></i> Nuevo rol
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="formulario_rol">
                
                <input type="hidden" name="id" id="rol_id" value="">

                <div class="modal-body">
                    {{-- Sección: Datos del rol --}}
                    <div class="seccion-form mb-3">
                        <div class="seccion-head">
                            <div class="seccion-ic"><i class="fas fa-tag"></i></div>
                            <div>
                                <span class="seccion-titulo">Información del rol</span>
                                <span class="seccion-sub">Datos básicos del rol</span>
                            </div>
                        </div>
                        <div class="seccion-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nombre_rol"
                                            class="font-weight-bold small text-uppercase text-muted">
                                            <i class="fas fa-user-tag mr-1"></i>Nombre del rol
                                        </label>
                                        <input type="text" class="form-control" id="nombre_rol" name="name"
                                            placeholder="Ej: Administrador" required>
                                    </div>
                                    <div id="_name"></div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="color_rol" class="font-weight-bold small text-uppercase text-muted">
                                            <i class="fas fa-palette mr-1"></i>Color identificador
                                        </label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text" style="padding: 0 4px;">
                                                    <input type="color" class="form-control p-1" id="color_rol"
                                                        name="color" value="#7b2233"
                                                        style="width: 38px; height: 38px; border: none; padding: 2px;">
                                                </span>
                                            </div>
                                            <input type="text" class="form-control color-preview" id="color_texto"
                                                value="#7b2233" readonly>
                                        </div>
                                    </div>
                                    <div id="_color"></div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="descripcion_rol"
                                            class="font-weight-bold small text-uppercase text-muted">
                                            <i class="fas fa-align-left mr-1"></i>Descripción
                                        </label>
                                        <textarea class="form-control" id="descripcion_rol" name="descripcion" rows="2"
                                            placeholder="Breve descripción del rol"></textarea>
                                    </div>
                                    <div id="_descripcion"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sección: Permisos --}}
                    <div class="seccion-form">
                        <div class="seccion-head">
                            <div class="seccion-ic"><i class="fas fa-lock"></i></div>
                            <div>
                                <span class="seccion-titulo">Permisos del rol</span>
                                <span class="seccion-sub">Selecciona los permisos que tendrá este rol</span>
                            </div>
                        </div>
                        <div class="seccion-body">
                        

                            <div class="permisos-layout">
                                {{-- ===== Columna izquierda: navegación por grupo/módulo =====
                                     Se lee todo de config/permisos.php (fuente única). --}}
                                <div class="permisos-nav">
                                    <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                                        @php $primerTab = true; @endphp
                                        @foreach (config('permisos.estructura') as $grupo => $modulos)
                                            <span class="permisos-cat">
                                                <i class="{{ config('permisos.grupos')[$grupo] ?? 'fas fa-folder' }} mr-1"></i>{{ $grupo }}
                                            </span>
                                            @foreach($modulos as $clave => $modulo)
                                                <a class="nav-link permiso-tab {{ $primerTab ? 'active' : '' }}"
                                                    id="tab_link_{{ $clave }}" data-toggle="pill"
                                                    href="#tab_{{ $clave }}" role="tab">
                                                    <span class="pt-ic"><i class="{{ $modulo['icono'] }}"></i></span>
                                                    <span class="pt-nombre">{{ $modulo['etiqueta'] }}</span>
                                                    <span class="pt-count"
                                                        data-modulo="{{ $clave }}">0/{{ count($modulo['acciones']) }}</span>
                                                </a>
                                                @php $primerTab = false; @endphp
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>

                                {{-- ===== Columna derecha: acciones del módulo seleccionado ===== --}}
                                <div class="permisos-panel tab-content">
                                    @php $primerPane = true; @endphp
                                    @foreach(config('permisos.estructura') as $grupo => $modulos)
                                        @foreach($modulos as $clave => $modulo)
                                            <div class="tab-pane fade {{ $primerPane ? 'show active' : '' }}"
                                                id="tab_{{ $clave }}" role="tabpanel">
                                                <div class="panel-head">
                                                    <div class="ph-info">
                                                        <span class="ph-titulo">
                                                            <i class="{{ $modulo['icono'] }} mr-1"></i>{{ $modulo['etiqueta'] }}
                                                        </span>
                                                        <small class="ph-sub">{{ $grupo }}</small>
                                                    </div>
                                                    {{-- Marcar/desmarcar todos los permisos de este módulo (se conecta en
                                                    rol.js) --}}
                                                    <div class="custom-control custom-switch marcar-todos">
                                                        <input type="checkbox" class="custom-control-input check-todos"
                                                            id="todos_{{ $clave }}"
                                                            data-modulo="{{ $clave }}">
                                                        <label class="custom-control-label"
                                                            for="todos_{{ $clave }}">Marcar todos</label>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    @foreach($modulo['acciones'] as $accion)
                                                        @php $permisoId = $clave . '.' . $accion; @endphp
                                                        <div class="col-md-6 mb-2">
                                                            <div class="custom-control custom-switch custom-switch-lg">
                                                                <input type="checkbox" class="custom-control-input permiso-check"
                                                                    data-modulo="{{ $clave }}"
                                                                    id="permiso_{{ $permisoId }}" name="permisos[]"
                                                                    value="{{ $permisoId }}">
                                                                <label class="custom-control-label" for="permiso_{{ $permisoId }}">
                                                                    {{ config('permisos.acciones')[$accion] ?? ucfirst($accion) }}
                                                                </label>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @php $primerPane = false; @endphp
                                        @endforeach
                                    @endforeach

                                    <div id="_permisos"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer" style="background: #faf7f5; border-top: 1px solid #ece4e3;">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                        <i class="fas fa-times mr-1"></i>Cancelar
                    </button>
                    <button type="submit" class="btn btn-navy" id="btn_guardar_rol">
                        <i class="fas fa-save mr-1"></i> Guardar rol
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


@stop

@section('js')
<script src="{{ asset('assets/js/administrador/rol/rol.js') }}" type="module"></script>
@stop