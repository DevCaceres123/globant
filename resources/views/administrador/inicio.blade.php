@extends('administrador.dashboard')

@section('title', 'Inicio')

{{-- Estilos propios de la página de inicio (banner + accesos rápidos) --}}
@section('css')
    <link rel="stylesheet" href="{{ asset('assets/css/administrador/inicio.css') }}">
@stop

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="m-0"><i class="fas fa-home txt-gold mr-2"></i>Inicio</h1>
            <small class="text-muted">Panel general del sistema</small>
        </div>
        <ol class="breadcrumb bg-transparent p-0 m-0">
            <li class="breadcrumb-item active">Inicio</li>
        </ol>
    </div>
@stop

@section('modulo')

{{-- ===================== 1. BANNER DE BIENVENIDA ===================== --}}
@php
    $usuario = auth()->user();
    $rol = $usuario?->getRoleNames()->first() ?? 'Sin rol';
    $hora = (int) now()->format('H');
    $saludo = $hora < 12 ? 'Buenos días' : ($hora < 19 ? 'Buenas tardes' : 'Buenas noches');
    $fecha = \Carbon\Carbon::now()->locale('es')->isoFormat('dddd, D [de] MMMM [de] YYYY');
@endphp
<div class="hero-bienvenida mb-4">
    <p class="saludo">
        {{ $saludo }}, <span class="nombre">{{ $usuario->nombres ?? $usuario->usuario }}</span>
        <span class="badge-rol-hero"><i class="fas fa-user-shield mr-1"></i>{{ ucfirst($rol) }}</span>
    </p>
    <p class="sub">Bienvenido al panel de administración. Aquí tienes un resumen del sistema.</p>
    <span class="fecha"><i class="far fa-calendar-alt"></i>{{ $fecha }}</span>
</div>

{{-- ===================== 2. TARJETAS DE RESUMEN (KPIs) ===================== --}}
<div class="row">
    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card t-gold">
            <div class="ic ic-gold"><i class="fas fa-users"></i></div>
            <div class="meta">
                <div class="label">Usuarios del sistema</div>
                <div class="value">{{$totalUsuarios }}</div>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3 mb-3">
        <div class="stat-card">
            <div class="ic ic-navy"><i class="fas fa-user-tag"></i></div>
            <div class="meta">
                <div class="label">Roles</div>
                <div class="value">{{ $totalRoles }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ===================== 3. ACCESOS RÁPIDOS ===================== --}}
<h5 class="titulo-seccion mt-2 mb-3"><i class="fas fa-bolt"></i> Accesos rápidos</h5>
<div class="row">
    <div class="col-12 col-md-6 col-xl-4 mb-3">
        <a href="{{ route('usuario.index') }}" class="acceso-card">
            <div class="ac-ic"><i class="fas fa-users"></i></div>
            <div>
                <div class="ac-titulo">Usuarios</div>
                <div class="ac-desc">Gestiona cuentas, roles y accesos</div>
            </div>
            <i class="fas fa-arrow-right ac-flecha"></i>
        </a>
    </div>
</div>

@stop
