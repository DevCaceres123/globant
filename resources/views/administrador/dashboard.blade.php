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
