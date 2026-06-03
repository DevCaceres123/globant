<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <title>Iniciar sesión — Globant SRL</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Íconos (Font Awesome publicado localmente con AdminLTE) --}}
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">
    {{-- Tipografía --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Estilos del login --}}
    <link rel="stylesheet" href="{{ asset('assets/css/auth/login.css') }}">
</head>

<body>
    <div class="auth-wrap">

        {{-- ============ Panel de marca ============ --}}
        <aside class="auth-brand">
            <div class="brand-top">
                <div class="brand-logo">
                    <img src="{{ asset('assets/img-dasboard/logo.png') }}" alt="Logo Globant SRL"
                        onerror="this.style.display='none';this.parentNode.innerHTML='<i class=\'fas fa-shield-halved\'></i>'">
                </div>
                <div class="org">Globant SRL<small>Sistema de Afiliados</small></div>
            </div>

            <div class="brand-mid">
                <h1>Bienvenido de<br><span>vuelta.</span></h1>
                <p>Gestiona afiliados, usuarios y reportes desde un solo panel, de forma segura y centralizada.</p>
            </div>

            <div class="brand-feats">
                <div class="feat"><i class="fas fa-lock"></i> Acceso seguro y controlado por roles</div>
                <div class="feat"><i class="fas fa-bolt"></i> Información centralizada y al instante</div>
                <div class="feat"><i class="fas fa-headset"></i> Soporte para todo el equipo</div>
            </div>
        </aside>

        {{-- ============ Formulario ============ --}}
        <section class="auth-form">
            <h2 class="titulo">Iniciar sesión</h2>
            <p class="subtitulo">Ingresa tus credenciales para acceder al sistema.</p>

            <div id="mensaje_error"></div>

            <form id="formulario_login" autocomplete="off" data-url="{{ route('ingresar') }}">
                @csrf

                <div class="campo">
                    <label for="usuario">Usuario</label>
                    <div class="input-box">
                        <i class="fas fa-user lead"></i>
                        <input type="text" id="usuario" name="usuario" placeholder="Ingrese su usuario">
                    </div>
                </div>

                <div class="campo">
                    <label for="password">Contraseña</label>
                    <div class="input-box">
                        <i class="fas fa-lock lead"></i>
                        <input type="password" id="password" name="password" placeholder="Ingrese su contraseña">
                        <button type="button" class="btn-ojo" id="btn_vista" onclick="togglePassword()" title="Mostrar/ocultar">
                            <i class="fas fa-eye-slash" id="icono_password"></i>
                        </button>
                    </div>
                </div>

                <button class="btn-ingresar" type="submit" id="btn_ingresar_usuario">
                    <span>INGRESAR</span> <i class="fas fa-arrow-right-to-bracket"></i>
                </button>
            </form>

            <div class="auth-foot">
                <i class="fas fa-shield-halved"></i> Conexión segura — &copy; {{ date('Y') }} Globant SRL
            </div>
        </section>
    </div>

    {{-- Script del login --}}
    <script src="{{ asset('assets/js/auth/login.js') }}"></script>
</body>

</html>
