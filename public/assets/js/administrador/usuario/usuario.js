import { mensajeAlerta, toast } from "../../../../funciones_helper/notificaciones/mensajes.js";
import { crud } from "../../../../funciones_helper/operaciones_crud/crud.js";
import {
    vaciar_errores,
    vaciar_formulario,
} from "../../../../funciones_helper/vistas/formulario.js";

let permisosGlobal;
let tabla;

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

// Llamada a la función para recargar la tabla después de una operación
function actualizarTabla() {
    tabla.ajax.reload(null, false); // Recarga los datos sin resetear el paginado
}

/* =========================================================
   MODAL: nuevo usuario / mostrar contraseña / switch estado
   ========================================================= */



// ELIMINAR REGISTRO
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

            crud("admin/usuario", "DELETE", id_registro, null, function (error, response) {

                if (error) {
                    mensajeAlerta("Ocurrió un error al eliminar el registro", "error");
                    return;
                }
                if (response.tipo != "exito") {
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
