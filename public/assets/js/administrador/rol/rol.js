import { mensajeAlerta, toast } from "../../../../funciones_helper/notificaciones/mensajes.js";
import { crud } from "../../../../funciones_helper/operaciones_crud/crud.js";
import { mostrarCarga, ocultarCarga } from "../../../../funciones_helper/vistas/preloader.js";
import {
    vaciar_errores,
    vaciar_formulario,
    toggleContrasenia,
} from "../../../../funciones_helper/vistas/formulario.js";


const MODO_CREAR = 'crear';
const MODO_EDITAR = 'editar';
const MODO_VER = 'ver';

let modoFormulario = MODO_CREAR;



/* =========================================================
   FUNCION: marcar/desmarcar TODOS los permisos de UN módulo
   Delegado por clase, así sirve para cualquier módulo (usuario,
   rol, afiliado, reporte...). Se scopea solo a los .permiso-check
   de ese data-modulo y respeta el estado del switch.
   ========================================================= */

$(document).on('change', '.check-todos', function () {
    const modulo = $(this).data('modulo');
    const marcar = $(this).prop('checked');

    $(`.permiso-check[data-modulo="${modulo}"]`).prop('checked', marcar);
    sincronizarModulos();
});


/* =========================================================
   FUNCION: marca en el modal los permisos que ya tiene el rol.
   Se busca por VALUE (no por id, porque el id tiene un punto:
   "permiso_usuario.ver" rompería el selector).
   ========================================================= */

function marcarPermisos(permisos) {
    // 1) Partir de cero: desmarcar todo (por si el modal traía otro rol)
    $('.permiso-check').prop('checked', false);

    // 2) Marcar los que vienen del backend, buscando por value
    (permisos || []).forEach(function (permiso) {
        $(`.permiso-check[value="${permiso.name}"]`).prop('checked', true);
    });

    // 3) Refrescar contadores y switches "Marcar todos" de cada módulo
    sincronizarModulos();
}


/* =========================================================
   FUNCION: recalcula el "0/N" y el switch "Marcar todos" de
   cada módulo según cuántos permisos estén marcados.
   ========================================================= */

function sincronizarModulos() {
    const modulos = new Set();
    $('.permiso-check').each(function () { modulos.add($(this).data('modulo')); });

    modulos.forEach(function (modulo) {
        const total = $(`.permiso-check[data-modulo="${modulo}"]`).length;
        const marcados = $(`.permiso-check[data-modulo="${modulo}"]:checked`).length;

        $(`.pt-count[data-modulo="${modulo}"]`).text(`${marcados}/${total}`);
        $(`#todos_${modulo}`).prop('checked', total > 0 && total === marcados);
    });
}


/* =========================================================
   Al marcar/desmarcar un permiso a mano, refrescar contadores
   y el switch "Marcar todos" (sincronía inversa).
   ========================================================= */

$(document).on('change', '.permiso-check', function () {
    sincronizarModulos();
});



/* =========================================================
   FUNCIONE: para limpiar campos al abrir un modal
   ========================================================= */

$('#btn_nuevo_rol').click(function () {
    modoFormulario = MODO_CREAR;
    vaciar_errores('formulario_rol');
    vaciar_formulario('formulario_rol');

    // Un rol nuevo siempre es editable
    modoSoloLectura(false);
    tituloModal('Nuevo rol', 'fas fa-user-shield');

    // Resetear permisos: desmarcar todo y refrescar contadores/switches
    $('.permiso-check').prop('checked', false);
    sincronizarModulos();

    $('#modal_rol').modal('show');
});


/* =========================================================
   FUNCION: cambia el título e ícono del encabezado del modal.
   ========================================================= */

function tituloModal(texto, icono) {
    $('#modal_rol_titulo').html(`<i class="${icono} mr-2"></i> ${texto}`);
}


/* =========================================================
   FUNCION: activa/desactiva el modo SOLO LECTURA.
   - Deshabilita todos los campos y checkboxes de permisos.
   - Oculta el botón "Guardar" (en modo ver no se guarda nada).
   ========================================================= */

function modoSoloLectura(activo) {
    // Campos del formulario
    $('#nombre_rol, #descripcion_rol, #color_rol').prop('disabled', activo);

    // Switches de permisos (permisos y "marcar todos")
    $('.permiso-check, .check-todos').prop('disabled', activo);

    // El botón de guardar solo tiene sentido al crear o editar
    $('#btn_guardar_rol').toggle(!activo);
}


/* =========================================================
   FUNCION: trae los datos de un rol (mismo endpoint /edit) y
   rellena el modal. La usan tanto EDITAR como VER.
   ========================================================= */

function cargarRol(id) {
    mostrarCarga('.modal-content');
    let verOeditar;
    modoFormulario === 'editar' ? verOeditar = '/edit' : verOeditar = null;

    crud("admin/rol", "GET", id + verOeditar, null, function (error, response) {
        // El rol viene envuelto por el helper success(): { tipo, mensaje, datos }
        if (error) { mensajeAlerta('Ocurrió un error al obtener los datos.', 'error'); return; }
        if (response.tipo !== 'exito') { mensajeAlerta(response.mensaje, response.tipo); return; }

        const rol = response.datos;

        // --- Datos básicos del rol ---
        $("#rol_id").val(rol.id);
        $("#nombre_rol").val(rol.name);
        $("#color_rol").val(rol.color);
        $("#color_texto").val(rol.color);
        $("#descripcion_rol").val(rol.descripcion);

        // --- Permisos: marca los que ya tiene el rol ---
        marcarPermisos(rol.permissions);

        ocultarCarga('.modal-content');
    });
}


/* =========================================================
   FUNCION: EDITAR un rol (campos habilitados, botón visible).
   ========================================================= */

$(document).on("click", ".editar-rol", function () {
    modoFormulario = MODO_EDITAR;
    vaciar_formulario('formulario_rol');
    vaciar_errores('formulario_rol');

    modoSoloLectura(false);
    tituloModal('Editar rol', 'fas fa-pen');

    $("#modal_rol").modal("show");
    cargarRol($(this).data("id"));
});


/* =========================================================
   FUNCION: VER un rol (solo lectura, mismo endpoint que editar).
   ========================================================= */

$(document).on("click", ".ver-rol", function () {
    modoFormulario = MODO_VER;
    vaciar_formulario('formulario_rol');
    vaciar_errores('formulario_rol');

    modoSoloLectura(true);
    tituloModal('Detalle del rol', 'fas fa-eye');

    $("#modal_rol").modal("show");
    cargarRol($(this).data("id"));
});




/* =========================================================
   FUNCION: para crear,editar y ver permisos del rol
   ========================================================= */

$('#formulario_rol').on('submit', function (e) {
    e.preventDefault();

    const btn = $('#btn_guardar_rol');
    const formData = new FormData(this);


    mostrarCarga('.modal-content');
    btn.prop('disabled', true);
    vaciar_errores('formulario_rol');

    if (modoFormulario === 'editar') {

        crud('admin/rol', 'PUT', formData.get('id'), formData, function (error, response) {
            btn.prop('disabled', false);
            ocultarCarga('.modal-content');

            if (error) { mensajeAlerta('Ocurrió un error al actualizar el rol.', 'error'); return; }
            // Se marcara los campos que tienen errores de validacion
            if (response.tipo === 'errores') { mensajeAlerta(response.mensaje, 'errores'); return; }
            if (response.tipo !== 'exito') { mensajeAlerta(response.mensaje, response.tipo); return; }

            $('#modal_rol').modal('hide');
            vaciar_formulario('formulario_rol');
            mensajeAlerta(response.mensaje, response.tipo);

            setTimeout(() => {
                window.location.reload();
            }, 2000);

        });
    }

    if (modoFormulario === 'crear') {

        crud('admin/rol', 'POST', null, formData, function (error, response) {
            btn.prop('disabled', false);
            ocultarCarga('.modal-content');

            if (error) { mensajeAlerta('Ocurrió un error al crear el rol.', 'error'); return; }
            if (response.tipo === 'errores') { mensajeAlerta(response.mensaje, 'errores'); return; }
            if (response.tipo !== 'exito') { mensajeAlerta(response.mensaje, response.tipo); return; }

            $('#modal_rol').modal('hide');
            vaciar_formulario('formulario_rol');
            mensajeAlerta(response.mensaje, response.tipo);
            setTimeout(() => {
                window.location.reload();
            }, 2000);
        });

    }

});






/* =========================================================
   FUNCION: para eliminar rol
   ========================================================= */

$(document).on('click', '.eliminar-rol', function (e) {

    e.preventDefault(); // Evitar que el enlace recargue la página
    let id_registro = $(this).data('id'); // Obtener el id 


    Swal.fire({
        title: "NOTA!",
        text: "¿Está seguro de eliminar el registro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, Estoy seguro",
        cancelButtonText: "Cancelar",
    }).then(async function (result) {
        if (result.isConfirmed) {

            mostrarCarga('.card');
            crud("admin/rol", "DELETE", id_registro, null, function (error, response) {

                if (error) {
                    ocultarCarga('.card');
                    mensajeAlerta("Ocurrió un error al eliminar el registro", "error");
                    return;
                }
                if (response.tipo != "exito") {
                    ocultarCarga('.card');
                    mensajeAlerta(response.mensaje, response.tipo);
                    return;
                }
                mensajeAlerta(response.mensaje, response.tipo);
                ocultarCarga('.card');
                setTimeout(() => {
                    window.location.reload();
                }, 2000);

            })
        } else {
            toast('info', 'Se canceló la operación');
        }
    })
});
