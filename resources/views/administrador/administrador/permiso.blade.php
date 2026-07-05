@extends('administrador.dashboard')

@section('title', 'Módulo de permiso')

{{-- Reutilizamos los estilos del módulo de roles (nav de módulos, badges, etc.) --}}
@section('css')
<link rel="stylesheet" href="{{ asset('assets/css/administrador/rol.css') }}">
<style>
    /* En esta pantalla (no en el modal) damos más altura al nav y al panel.
       Se acota con .permisos-page para no afectar el modal de rol. */
    .permisos-page .permisos-nav,
    .permisos-page .permisos-panel {
        max-height: 65vh;
    }
</style>
@stop

@section('content_header')
<div class="d-flex align-items-center justify-content-between">
    <div>
        <h1 class="m-0"><i class="fas fa-key txt-gold mr-2"></i>Permisos</h1>
        <small class="text-muted">Consulta de permisos del sistema y los roles que los usan</small>
    </div>
    <ol class="breadcrumb bg-transparent p-0 m-0">
        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Permisos</li>
    </ol>
</div>
@stop

@section('modulo')

{{-- ===================== RESUMEN ===================== --}}
<div class="row mb-3">
    <div class="col-md-4 mb-2">
        <div class="info-box shadow-sm mb-0">
            <span class="info-box-icon bg-navy"><i class="fas fa-key"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Total de permisos</span>
                <span class="info-box-number">{{ $resumen['total'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="info-box shadow-sm mb-0">
            <span class="info-box-icon bg-info"><i class="fas fa-layer-group"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">Módulos</span>
                <span class="info-box-number">{{ $resumen['modulos'] }}</span>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-2">
        <div class="info-box shadow-sm mb-0">
            <span class="info-box-icon {{ $resumen['sinSembrar'] > 0 ? 'bg-warning' : 'bg-success' }}">
                <i class="fas {{ $resumen['sinSembrar'] > 0 ? 'fa-exclamation-triangle' : 'fa-check' }}"></i>
            </span>
            <div class="info-box-content">
                <span class="info-box-text">Sin sembrar</span>
                <span class="info-box-number">{{ $resumen['sinSembrar'] }}</span>
            </div>
        </div>
    </div>
</div>

{{-- ===================== LISTADO DE PERMISOS (SOLO LECTURA) =====================
     La estructura (grupo -> módulo -> acciones) se lee de config/permisos.php,
     la fuente única. El menú de la izquierda son los módulos; a la derecha se ve
     el detalle del módulo seleccionado (una pestaña a la vez → no se apila todo).
================================================================================ --}}
<div class="card">
    <div class="card-header card-cab">
        <h3 class="card-title mt-1">
            <i class="fas fa-list mr-1"></i> Listado de permisos
        </h3>
        <div class="card-tools">
            <span class="text-light small">
                <i class="fas fa-info-circle mr-1"></i>
                Solo lectura
            </span>
        </div>
    </div>

    <div class="card-body">
        <div class="permisos-layout permisos-page">
            {{-- ===== Columna izquierda: módulos agrupados por categoría ===== --}}
            <div class="permisos-nav">
                <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
                    @php $primerTab = true; @endphp
                    @foreach(config('permisos.estructura') as $grupo => $modulos)
                        <span class="permisos-cat">
                            <i class="{{ config('permisos.grupos')[$grupo] ?? 'fas fa-folder' }} mr-1"></i>{{ $grupo }}
                        </span>
                        @foreach($modulos as $clave => $modulo)
                            <a class="nav-link permiso-tab {{ $primerTab ? 'active' : '' }}"
                                id="tab_link_{{ $clave }}" data-toggle="pill"
                                href="#panel_{{ $clave }}" role="tab">
                                <span class="pt-ic"><i class="{{ $modulo['icono'] }}"></i></span>
                                <span class="pt-nombre">{{ $modulo['etiqueta'] }}</span>
                                <span class="pt-count">{{ count($modulo['acciones']) }}</span>
                            </a>
                            @php $primerTab = false; @endphp
                        @endforeach
                    @endforeach
                </div>
            </div>

            {{-- ===== Columna derecha: detalle del módulo seleccionado ===== --}}
            <div class="permisos-panel tab-content">
                @php $primerPane = true; @endphp
                @foreach(config('permisos.estructura') as $grupo => $modulos)
                    @foreach($modulos as $clave => $modulo)
                        <div class="tab-pane fade {{ $primerPane ? 'show active' : '' }}"
                            id="panel_{{ $clave }}" role="tabpanel">

                            <div class="panel-head">
                                <div class="ph-info">
                                    <span class="ph-titulo">
                                        <i class="{{ $modulo['icono'] }} mr-1"></i>{{ $modulo['etiqueta'] }}
                                    </span>
                                    <small class="ph-sub">{{ $grupo }}</small>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm mb-0">
                                    <thead>
                                        <tr>
                                            <th style="width: 22%">Acción</th>
                                            <th style="width: 30%">Permiso</th>
                                            <th>Roles con acceso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($modulo['acciones'] as $accion)
                                            @php
                                                $clavePermiso = $clave . '.' . $accion;
                                                $permiso = $permisos[$clavePermiso] ?? null;
                                            @endphp
                                            <tr>
                                                <td>{{ config('permisos.acciones')[$accion] ?? ucfirst($accion) }}</td>
                                                <td><code>{{ $clavePermiso }}</code></td>
                                                <td>
                                                    @if(!$permiso)
                                                        {{-- En el config pero aún no sembrado en BD --}}
                                                        <span class="badge badge-warning">
                                                            <i class="fas fa-exclamation-triangle mr-1"></i>Sin sembrar
                                                        </span>
                                                    @elseif($permiso->roles->isEmpty())
                                                        <span class="permisos-vacio">Ningún rol</span>
                                                    @else
                                                        <span class="badge badge-light border mr-1" title="Roles con acceso">
                                                            <i class="fas fa-user-shield mr-1"></i>{{ $permiso->roles_count }}
                                                        </span>
                                                        @foreach($permiso->roles as $rol)
                                                            <span class="badge text-capitalize mr-1 mb-1"
                                                                style="background: {{ $rol->color ?: '#6c757d' }}; color:#fff;">
                                                                {{ $rol->name }}
                                                            </span>
                                                        @endforeach
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @php $primerPane = false; @endphp
                    @endforeach
                @endforeach
            </div>
        </div>
    </div>
</div>

@stop
