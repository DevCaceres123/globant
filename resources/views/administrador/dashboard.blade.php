{{--
    Layout base para todos los módulos del panel de administración.
    Extiende el layout de AdminLTE y carga UNA sola vez los elementos
    comunes (formulario de logout + scripts globales: handler de salir,
    idioma de DataTables, etc.).

    Uso en cada módulo:
        @extends('administrador.plantilla_admin.master')
        @section('title', '...')
        @section('content_header') ... @stop
        @section('modulo')   <-- aquí va el contenido propio del módulo
            ...
        @stop
--}}
@extends('adminlte::page')

@push('css')
    {{-- Estilos globales: sistema de diseño (paleta, tarjetas, badges, etc.) --}}
    @include('administrador.plantilla_admin.style')
@endpush

{{-- Preloader personalizado (spinner de doble anillo) que se muestra al cargar cada página --}}
@section('preloader')
    <div class="preloader-globant">
        <div class="pl-spinner">
            <span class="pl-anillo pl-anillo-1"></span>
            <span class="pl-anillo pl-anillo-2"></span>
        </div>
        <div class="pl-titulo">Sistema de Afiliados</div>
        <div class="pl-barra"><span></span></div>
    </div>
@stop

@section('content')
    {{-- Contenido propio de cada módulo --}}
    @yield('modulo')

    {{-- Formulario oculto compartido para cerrar sesión --}}
    @include('administrador.plantilla_admin.salir')
@stop

@push('js')
    {{-- Scripts globales: handler de logout + config de DataTables en español --}}
    @include('administrador.plantilla_admin.script')
@endpush
