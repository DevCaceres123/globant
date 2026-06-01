import { mensajeAlerta } from "../../../../funciones_helper/notificaciones/mensajes.js";
import { crud } from "../../../../funciones_helper/operaciones_crud/crud.js";
import {
    vaciar_errores,
    vaciar_formulario,
} from "../../../../funciones_helper/vistas/formulario.js";

let tablaUsuarios;

$(function () {
    // Inicializar DataTable (idioma español ya configurado globalmente en script.blade.php)
    tablaUsuarios = $("#tabla_usuarios").DataTable({
        responsive: true,
        autoWidth: false,
        columnDefs: [{ orderable: false, targets: [6] }], // columna de acciones sin orden
    });

    // Filtro por estado (busca sobre la columna Estado, índice 5)
    $("#filtro_estado").on("change", function () {
        tablaUsuarios.column(5).search(this.value).draw();
    });
});

// Al abrir el modal con "Nuevo usuario" -> limpiar formulario
document
    .getElementById("btn_nuevo_usuario")
    .addEventListener("click", function () {
        document.getElementById("formulario_usuario").reset();
        document.getElementById("usuario_id").value = "";
        document.getElementById("modal_titulo").innerHTML =
            '<i class="fas fa-user-plus mr-2"></i> Nuevo usuario';
    });

// Mostrar/ocultar contraseña en el modal
function togglePassword() {
    let input = document.getElementById("password");
    let icono = document.getElementById("icono_password");
    if (input.type === "password") {
        input.type = "text";
        icono.classList.replace("fa-eye-slash", "fa-eye");
    } else {
        input.type = "password";
        icono.classList.replace("fa-eye", "fa-eye-slash");
    }
}

// Confirmación de eliminación (solo diseño, sin petición todavía)
document.querySelectorAll(".btn-eliminar").forEach(function (btn) {
    btn.addEventListener("click", function () {
        Swal.fire({
            title: "NOTA!",
            text: "¿Está seguro de eliminar este usuario?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, eliminar",
            cancelButtonText: "Cancelar",
        }).then(function (result) {
            if (result.isConfirmed) {
                // Aquí irá la petición fetch para eliminar
            }
        });
    });
});
