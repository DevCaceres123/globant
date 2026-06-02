<style>
    /* =========================================================
       SISTEMA DE DISEÑO — Panel Globant (Bordeaux / Vino)
       Base reutilizable para todos los módulos del administrador.
       ========================================================= */
    :root {
        --wine:       #7b2233;
        --wine-600:   #6a1d2c;
        --wine-700:   #581824;
        --wine-soft:  #a8324a;
        --txt:        #2b2024;
        --muted:      #9a8c90;
        --bg-app:     #faf7f5;   /* blanco cálido */
        --line:       #ece4e3;
        --green:      #2f8a5b;
        --red:        #c0392b;
        --gold:       #b8893b;
    }

    /* ---- Lienzo general ---- */
    .content-wrapper { background: var(--bg-app); }

    /* ---- Menú lateral (sidebar) en tonos vino ---- */
    .main-sidebar {
        background: linear-gradient(180deg, #4a1622 0%, #2f0e16 100%) !important;
    }
    /* Marca / logo */
    .brand-link {
        background: rgba(0,0,0,.18) !important;
        border-bottom: 1px solid rgba(255,255,255,.08);
    }
    .brand-link .brand-text { color: #fff !important; font-weight: 600; letter-spacing: .3px; }

    /* Enlaces del menú */
    .main-sidebar .nav-sidebar .nav-link {
        color: rgba(255,255,255,.78);
        border-radius: .45rem;
        margin: .12rem .55rem;
        transition: all .15s ease;
    }
    .main-sidebar .nav-sidebar .nav-link i { color: rgba(255,255,255,.6); }
    .main-sidebar .nav-sidebar .nav-link:hover {
        background: rgba(255,255,255,.08);
        color: #fff;
    }
    .main-sidebar .nav-sidebar .nav-link:hover i { color: var(--gold); }

    /* Enlace activo (resaltado en dorado) */
    .main-sidebar .nav-sidebar .nav-link.active {
        background: var(--gold) !important;
        color: #2b2024 !important;
        box-shadow: 0 3px 10px rgba(0,0,0,.25);
        font-weight: 600;
    }
    .main-sidebar .nav-sidebar .nav-link.active i { color: #2b2024 !important; }

    /* Submenú activo */
    .main-sidebar .nav-treeview .nav-link.active {
        background: rgba(184,137,59,.22) !important;
        color: #fff !important;
    }
    .main-sidebar .nav-treeview .nav-link.active i { color: var(--gold) !important; }

    /* Encabezados de grupo del menú */
    .main-sidebar .nav-header {
        color: var(--gold);
        text-transform: uppercase;
        font-size: .72rem;
        letter-spacing: .6px;
        font-weight: 700;
    }

    .txt-navy { color: var(--wine) !important; }
    .txt-gold { color: var(--wine) !important; }

    /* ---- Encabezado de página ---- */
    .content-header h1 { font-weight: 700; color: var(--txt); letter-spacing: -.3px; }
    .breadcrumb-item a { color: var(--wine); }
    .breadcrumb-item.active { color: var(--muted); }

    /* ---- Tarjetas ---- */
    .card {
        border: 1px solid var(--line);
        border-radius: .65rem;
        box-shadow: 0 2px 12px rgba(123,34,51,.06);
    }
    .card-cab {
        background: linear-gradient(90deg, var(--wine), var(--wine-soft));
        border-bottom: 3px solid var(--gold);
        border-top-left-radius: .65rem;
        border-top-right-radius: .65rem;
    }
    .card-cab .card-title { color: #fff; font-weight: 600; }
    .card-cab .card-title i { color: rgba(255,255,255,.85); }

    /* ---- Tarjetas de resumen (stat cards) ---- */
    .stat-card {
        position: relative;
        display: flex;
        align-items: center;
        gap: 1rem;
        background: #fff;
        border: 1px solid var(--line);
        border-radius: .65rem;
        padding: 1rem 1.15rem;
        box-shadow: 0 2px 12px rgba(123,34,51,.05);
        overflow: hidden;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(123,34,51,.12); }
    .stat-card::before {
        content: ""; position: absolute; left: 0; top: 0; bottom: 0;
        width: 4px; background: var(--wine);
    }
    .stat-card.t-gold::before  { background: var(--gold); }
    .stat-card.t-green::before { background: var(--green); }
    .stat-card.t-red::before   { background: var(--red); }

    .stat-card .ic {
        width: 52px; height: 52px; border-radius: .6rem;
        display: grid; place-items: center; font-size: 1.25rem; flex-shrink: 0;
    }
    .ic-navy  { background: rgba(123,34,51,.10);  color: var(--wine); }
    .ic-gold  { background: rgba(184,137,59,.14); color: var(--gold); }
    .ic-green { background: rgba(47,138,91,.12);  color: var(--green); }
    .ic-red   { background: rgba(192,57,43,.12);  color: var(--red); }

    .stat-card .label { font-size: .72rem; text-transform: uppercase; letter-spacing: .4px; color: var(--muted); font-weight: 600; }
    .stat-card .value { font-size: 1.6rem; font-weight: 700; color: var(--txt); line-height: 1; }

    /* ---- Botones ---- */
    .btn-navy { background: var(--wine); border-color: var(--wine); color: #fff; }
    .btn-navy:hover  { background: var(--wine-700); border-color: var(--wine-700); color: #fff; }
    .btn-navy:focus  { box-shadow: 0 0 0 .2rem rgba(123,34,51,.30); color: #fff; }
    .btn-navy i { color: rgba(255,255,255,.85); }

    .btn-gold { background: var(--gold); border-color: var(--gold); color: #fff; font-weight: 600; }
    .btn-gold:hover { background: #9c7330; border-color: #9c7330; color: #fff; }

    /* Botones de acción cuadrados de la tabla */
    .btn-accion {
        width: 33px; height: 33px; padding: 0;
        display: inline-grid; place-items: center;
        border-radius: .45rem; border: 1px solid var(--line);
        background: #fff; color: var(--muted);
        transition: all .15s ease;
    }
    .btn-accion:hover { transform: translateY(-1px); }
    .btn-accion.editar:hover   { background: rgba(123,34,51,.10); color: var(--wine); border-color: transparent; }
    .btn-accion.eliminar:hover { background: rgba(192,57,43,.12); color: var(--red);  border-color: transparent; }

    /* ---- Badges tipo pill ---- */
    .badge-soft {
        display: inline-flex; align-items: center; gap: .4rem;
        padding: .35em .75em; border-radius: 999px;
        font-size: .74rem; font-weight: 600;
    }
    .badge-soft::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
    .badge-soft.activo   { background: rgba(47,138,91,.12); color: var(--green); }
    .badge-soft.inactivo { background: rgba(192,57,43,.12); color: var(--red); }

    .badge-rol {
        display: inline-block; padding: .32em .75em; border-radius: 999px;
        font-size: .74rem; font-weight: 600;
        background: rgba(123,34,51,.10); color: var(--wine);
    }
    .badge-rol.sec { background: rgba(154,140,144,.18); color: #6f6266; }

    /* ---- Tabla ---- */
    .table thead th {
        border-top: 0; border-bottom: 2px solid var(--line);
        font-size: .74rem; text-transform: uppercase; letter-spacing: .4px;
        color: var(--muted); font-weight: 700; background: #fdfbfb;
    }
    .table td { vertical-align: middle; border-top: 1px solid #f5efef; }
    .table-hover tbody tr:hover { background: rgba(123,34,51,.04); }

    .user-tag {
        font-family: monospace; font-size: .82rem;
        background: rgba(123,34,51,.07); color: var(--wine);
        padding: .15rem .45rem; border-radius: .35rem;
    }

    /* ---- Celda de usuario (avatar + datos) ---- */
    .avatar-ini {
        width: 40px; height: 40px; border-radius: 50%;
        display: grid; place-items: center;
        font-weight: 600; font-size: .82rem; color: #fff;
        background: var(--wine); flex-shrink: 0;
        box-shadow: 0 0 0 2px #fff, 0 0 0 3px rgba(184,137,59,.40);
    }
    .celda-usuario { display: flex; align-items: center; gap: .75rem; }
    .celda-usuario .nombre { font-weight: 600; line-height: 1.15; color: var(--txt); }
    .celda-usuario .correo { font-size: .8rem; color: var(--muted); }

    /* ---- Modal ---- */
    .modal-content { border: 0; border-radius: .65rem; overflow: hidden; }
    .modal-cab {
        background: linear-gradient(90deg, var(--wine), var(--wine-soft));
        border-bottom: 3px solid var(--gold);
    }
    .modal-cab .modal-title { color: #fff; font-weight: 600; }
    .modal-cab .modal-title i { color: rgba(255,255,255,.85); }
    .modal-cab .close { color: #fff; text-shadow: none; opacity: .9; }

    .titulo-seccion { color: var(--wine); font-weight: 700; font-size: .95rem; display: flex; align-items: center; gap: .45rem; }
    .titulo-seccion i { color: var(--gold); }

    /* ---- Secciones del modal (bloques diferenciados) ---- */
    .seccion-form {
        border: 1px solid var(--line);
        border-radius: .6rem;
        background: #fff;
        overflow: hidden;
    }
    .seccion-head {
        display: flex; align-items: center; gap: .7rem;
        padding: .7rem 1rem;
        background: rgba(123,34,51,.05);
        border-bottom: 1px solid var(--line);
        border-left: 3px solid var(--wine);
    }
    .seccion-ic {
        width: 36px; height: 36px; border-radius: .5rem; flex-shrink: 0;
        display: grid; place-items: center;
        background: var(--wine); color: #fff; font-size: .9rem;
    }
    .seccion-titulo { display: block; font-weight: 700; color: var(--wine); line-height: 1.1; }
    .seccion-sub    { display: block; font-size: .76rem; color: var(--muted); }
    .seccion-body   { padding: 1rem 1rem .35rem; }

    /* ---- Formularios ---- */
    .input-group-text { background: #faf7f5; border-color: var(--line); color: var(--wine); }
    .form-control:focus,
    .custom-select:focus { border-color: var(--wine); box-shadow: 0 0 0 .15rem rgba(123,34,51,.15); }

    /* ---- Switch (custom-switch) en vino ---- */
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--wine); border-color: var(--wine);
    }
    .custom-control-input:focus ~ .custom-control-label::before {
        box-shadow: 0 0 0 .15rem rgba(123,34,51,.20);
    }
    /* Variante grande */
    .custom-switch-lg { padding-left: 2.85rem; }
    .custom-switch-lg .custom-control-label { line-height: 1.6rem; padding-left: .4rem; }
    .custom-switch-lg .custom-control-label::before {
        left: -2.85rem; top: .15rem;
        height: 1.5rem; width: 2.6rem; border-radius: 1rem;
    }
    .custom-switch-lg .custom-control-label::after {
        top: calc(.15rem + 3px); left: calc(-2.85rem + 3px);
        height: calc(1.5rem - 6px); width: calc(1.5rem - 6px); border-radius: 50%;
    }
    .custom-switch-lg .custom-control-input:checked ~ .custom-control-label::after {
        transform: translateX(1.1rem);
    }

    /* ---- Banner de bienvenida (página de inicio) ---- */
    .hero-bienvenida {
        position: relative;
        background: linear-gradient(120deg, var(--wine) 0%, var(--wine-700) 55%, #3d1019 100%);
        border-radius: .75rem;
        border-bottom: 3px solid var(--gold);
        color: #fff;
        padding: 1.6rem 1.8rem;
        overflow: hidden;
        box-shadow: 0 8px 24px rgba(123,34,51,.18);
    }
    /* Círculo decorativo dorado de fondo */
    .hero-bienvenida::after {
        content: ""; position: absolute; right: -40px; top: -40px;
        width: 180px; height: 180px; border-radius: 50%;
        background: radial-gradient(circle, rgba(184,137,59,.30), transparent 70%);
    }
    .hero-bienvenida .saludo { font-size: 1.55rem; font-weight: 700; line-height: 1.1; margin: 0; }
    .hero-bienvenida .saludo .nombre { color: var(--gold); }
    .hero-bienvenida .sub { color: rgba(255,255,255,.78); margin: .35rem 0 0; font-size: .92rem; }
    .hero-bienvenida .fecha {
        display: inline-flex; align-items: center; gap: .5rem;
        margin-top: .9rem; padding: .35rem .85rem;
        background: rgba(255,255,255,.12); border-radius: 999px;
        font-size: .82rem; text-transform: capitalize;
    }
    .hero-bienvenida .fecha i { color: var(--gold); }
    .hero-bienvenida .badge-rol-hero {
        display: inline-block; padding: .3em .8em; border-radius: 999px;
        background: rgba(255,255,255,.16); color: #fff;
        font-size: .76rem; font-weight: 600; margin-left: .5rem;
    }

    /* ---- Tarjetas de acceso rápido ---- */
    .acceso-card {
        display: flex; align-items: center; gap: 1rem;
        background: #fff; border: 1px solid var(--line);
        border-radius: .65rem; padding: 1.1rem 1.2rem;
        text-decoration: none; color: inherit;
        position: relative; overflow: hidden;
        transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease;
        height: 100%;
    }
    .acceso-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 24px rgba(123,34,51,.14);
        border-color: rgba(123,34,51,.25);
        color: inherit; text-decoration: none;
    }
    .acceso-card .ac-ic {
        width: 50px; height: 50px; border-radius: .65rem; flex-shrink: 0;
        display: grid; place-items: center; font-size: 1.3rem;
        background: rgba(123,34,51,.10); color: var(--wine);
    }
    .acceso-card .ac-titulo { font-weight: 600; color: var(--txt); line-height: 1.15; }
    .acceso-card .ac-desc   { font-size: .8rem; color: var(--muted); }
    .acceso-card .ac-flecha { margin-left: auto; color: var(--muted); transition: transform .15s ease, color .15s ease; }
    .acceso-card:hover .ac-flecha { transform: translateX(3px); color: var(--wine); }

    /* Variante deshabilitada (módulo próximamente) */
    .acceso-card.proximamente { opacity: .65; cursor: default; }
    .acceso-card.proximamente:hover { transform: none; box-shadow: none; border-color: var(--line); }
    .acceso-card .badge-pronto {
        font-size: .66rem; font-weight: 700; text-transform: uppercase; letter-spacing: .4px;
        background: rgba(184,137,59,.16); color: var(--gold);
        padding: .15rem .5rem; border-radius: 999px; margin-left: .4rem;
    }
</style>

<meta name="csrf-token" content="{{ csrf_token() }}">
