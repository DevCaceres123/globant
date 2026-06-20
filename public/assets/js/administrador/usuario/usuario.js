import { mensajeAlerta, toast } from "../../../../funciones_helper/notificaciones/mensajes.js";
import { crud } from "../../../../funciones_helper/operaciones_crud/crud.js";
import { mostrarCarga, ocultarCarga } from "../../../../funciones_helper/vistas/preloader.js";
import {
    vaciar_errores,
    vaciar_formulario,
    toggleContrasenia,
} from "../../../../funciones_helper/vistas/formulario.js";

let permisosGlobal;
let tabla;
const MODO_CREAR = 'crear';
const MODO_EDITAR = 'editar';

let modoFormulario = MODO_CREAR;

$(document).ready(function () {
    listar_datos();
});

function listar_datos() {
    tabla = $("#tabla_usuarios").DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: {
            url: "listarUsuarios", // Ruta que recibe la solicitud en el servidor
            type: "GET", // Método de la solicitud (GET o POST)
            dataSrc: function (json) {
                permisosGlobal = json.permisos;
                // console.log(permisosGlobal); // Guardar los permisos para usarlos en las columnas
                return json.data; // Data que se pasará al DataTable
            },
        },
        columns: [
            {
                data: null,
                className: "table-td",
                render: function (data, type, row, meta) {
                    let start = $("#tabla_usuarios")
                        .DataTable()
                        .page.info().start;
                    return start + meta.row + 1;
                },
            },
            {
                data: null,
                className: "table-td",
                render: function (data, type, row) {
                    return `                         
                         <div class="celda-usuario">
                                
                                <div>
                                    <div class="nombre text-capitalize">${row.nombres} ${row.apellidos}</div>
                                    <div class="correo">${row.email}</div> 
                                </div>
                        </div>                          
                        
                    `;
                },
            },
            {
                data: "ci",
                className: "table-td",
                render: function (data) {
                    return `                            
                        ${data}
                    `;
                },
            },

            {
                data: null,
                className: "table-td",
                render: function (data, type, row) {
                    return `                  
                        <span class="user-tag">${row.usuario}</span>          
                        
                    `;
                },
            },

            {
                data: null,
                className: "table-td",
                render: function (data, type, row) {
                    // En Spatie 'roles' es un array (muchos-a-muchos)
                    if (!row.roles || row.roles.length === 0) {
                        return `<span class="text-muted">Sin rol</span>`;
                    }
                    return row.roles
                        .map((r) => `<span class="badge-rol">${r.name}</span>`)
                        .join(" ");
                },
            },

            {
                data: null,
                className: "table-td",
                render: function (data, type, row) {
                    let estadoChecked =
                        row.estado === "activo" ? "checked" : "";

                    // Aquí verificamos el permiso para cambiar el estado
                    let desactivarContent =
                        permisosGlobal["eliminar"] == true
                            ? `
                            <div class="custom-control custom-switch custom-switch-lg cambiar_estado_usuario d-inline-block"
                                 data-id="${row.id}" data-estado="${row.estado}" style="cursor:pointer">
                                <input type="checkbox" class="custom-control-input" id="estado_${row.id}" ${estadoChecked}>
                                <label class="custom-control-label" for="estado_${row.id}"></label>
                            </div>`
                            : `<span class="text-muted">No permitido</span>`;

                    return `
                            <div class="text-center">
                                ${desactivarContent}
                            </div>`;
                },
            },
            {
                data: null,
                className: "table-td",
                render: function (data, type, row) {
                    let editar = permisosGlobal.eliminar
                        ? `<a class="btn btn-sm btn-outline-warning px-2 d-inline-flex align-items-center editar_usuario me-1" data-id="${row.id}" title="Editar Usuario">
                            <i class="fas fa-pencil-alt fs-16"></i>
                        </a>`
                        : ``;

                    let eliminar = permisosGlobal.eliminar
                        ? `  <a class="btn btn-sm btn-outline-danger px-2 d-inline-flex align-items-center eliminar_usuario ml-1" data-id="${row.id}" title="Eliminar carrera">
                            <i class="fas fa-window-close fs-16"></i>
                        </a>`
                        : ``;

                    return `<div class="d-flex justify-content-center">${editar}${eliminar}</div>`;
                },
            },
        ],
    });
}

function actualizarTabla(callback = null) {
    tabla.ajax.reload(function () {
        ocultarCarga('.card');
        if (callback) callback();
    }, false);
}

/* =========================================================
   MODAL: nuevo usuario / mostrar contraseña / switch estado
   ========================================================= */

document.getElementById('toggle_password').addEventListener('click', () => {
    toggleContrasenia('password', 'icono_password');
});




/* =========================================================
   FUNCION: para eliminar usuario
   ========================================================= */

$('#tabla_usuarios').on('click', '.eliminar_usuario', function (e) {

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
            crud("admin/usuario", "DELETE", id_registro, null, function (error, response) {

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
                actualizarTabla();
            })
        } else {
            toast('info', 'Se canceló la operación');
        }
    })
});


/* =========================================================
   FUNCION: para desactivar/activar usuario
   ========================================================= */

$('#tabla_usuarios').on('click', '.cambiar_estado_usuario', function (e) {

    const contenedor = $(this).closest('.cambiar_estado_usuario');
    let id_registro = contenedor.data('id');
    let estadoActual = contenedor.data('estado');

    let nuevoEstado = estadoActual === 'activo' ? 'inactivo' : 'activo';

    mostrarCarga('.card');
    crud("admin/actualizarEstado", "PATCH", id_registro, { estado: nuevoEstado }, function (error, response) {

        if (error) {
            ocultarCarga('.card');
            mensajeAlerta("Ocurrió un error al cambiar el estado del usuario", "error");
            return;
        }
        if (response.tipo != "exito") {
            ocultarCarga('.card');
            mensajeAlerta(response.mensaje, response.tipo);
            return;
        }
        mensajeAlerta(response.mensaje, response.tipo);
        actualizarTabla();
    });
});



/* =========================================================
   FUNCIONE: para limpiar campos al abrir un modal
   ========================================================= */

$('#btn_nuevo_usuario').click(function () {
    modoFormulario = 'crear';
    vaciar_errores('formulario_usuario');
    vaciar_formulario('formulario_usuario');

    $('#modal_usuario').modal('show');
});


/* =========================================================
   FUNCION: para obtener los datos para editar 
   ========================================================= */

$(document).on("click", ".editar_usuario", function () {
    $("#modal_usuario").modal("show");
    vaciar_formulario('formulario_usuario');
    vaciar_errores('formulario_usuario');
    mostrarCarga('.modal-content');    
    let id_campo = $(this).data("id"); // Obtener el id del campo
    modoFormulario = 'editar';
    crud("admin/usuario","GET",id_campo + "/edit",null,function (error, response) {
            // console.log(response);
            if (error) { mensajeAlerta('Ocurrió un error al obtener los datos.', 'error'); return; }
            
            $("#usuario_id").val(response.datos.id);
            $("#nombres").val(response.datos.nombres);
            $("#apellidos").val(response.datos.apellidos);
            $("#ci").val(response.datos.ci);
            $("#email").val(response.datos.email);
            $("#usuario").val(response.datos.usuario);
            $("#estado").val(response.datos.estado).trigger('change');
            $("#rol").val(response.datos.roles[0].name).trigger('change');
            

            ocultarCarga('.modal-content');
            // si todo esta correcto muestra el mensaje de correcto
        }
    );
});

/* =========================================================
   FUNCION: para crear y editar Usuario
   ========================================================= */

$('#formulario_usuario').on('submit', function (e) {
    e.preventDefault();

    const btn = $('#btn_guardar_usuario');
    const formData = new FormData(this);

    mostrarCarga('.modal-content');
    btn.prop('disabled', true);
    vaciar_errores('formulario_usuario');

    if (modoFormulario === 'editar') {
        crud('admin/usuario', 'PUT', formData.get('id'), formData, function (error, response) {
            btn.prop('disabled', false);
            ocultarCarga('.modal-content');

            if (error) { mensajeAlerta('Ocurrió un error al actualizar el usuario.', 'error'); return; }
              // Se marcara los campos que tienen errores de validacion
            if (response.tipo === 'errores') { mensajeAlerta(response.mensaje, 'errores'); return; }
            if (response.tipo !== 'exito') { mensajeAlerta(response.mensaje, response.tipo); return; }

            $('#modal_usuario').modal('hide');
            vaciar_formulario('formulario_usuario');
            mensajeAlerta(response.mensaje, response.tipo);
            actualizarTabla();
        });
    } else {
        crud('admin/usuario', 'POST', null, formData, function (error, response) {
            btn.prop('disabled', false);
            ocultarCarga('.modal-content');

            if (error) { mensajeAlerta('Ocurrió un error al crear el usuario.', 'error'); return; }
            if (response.tipo === 'errores') { mensajeAlerta(response.mensaje, 'errores'); return; }
            if (response.tipo !== 'exito') { mensajeAlerta(response.mensaje, response.tipo); return; }

            $('#modal_usuario').modal('hide');
            vaciar_formulario('formulario_usuario');
            mensajeAlerta(response.mensaje, response.tipo);
            actualizarTabla();
        });
    }
});