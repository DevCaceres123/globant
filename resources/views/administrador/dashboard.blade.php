@extends('adminlte::page')

@section('title', 'Panel de control')
@push('css')
    @include('administrador.plantilla_admin.style')
@endpush

@section('content_header')
<h1 class="text-center">PANEL DE ADMINISTRACION GLOBANT SRL</h1>
@stop

@section('content')
<div class="small-box bg-info">
    <div class="inner">
        <h3>150</h3>
        <p>Usuarios</p>
    </div>
    <div class="icon">
        <i class="fas fa-users"></i>
    </div>
</div>


@include('administrador.plantilla_admin.salir')

@stop
@push('js')
    @include('administrador.plantilla_admin.script')
@endpush