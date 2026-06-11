// Toast reutilizable para avisos rápidos (esquina superior derecha)
export const toast = (icon, mensaje = "") => {
    Swal.mixin({
        toast: !0,
        position: "top-end",
        showConfirmButton: !1,
        timer: 1500,
        timerProgressBar: !0,
        didOpen: (e) => {
            (e.addEventListener("mouseenter", Swal.stopTimer),
                e.addEventListener("mouseleave", Swal.resumeTimer));
        },
    }).fire({
        icon: icon,
        title: mensaje,
    });
};

// Define las funciones de notificación utilizando SweetAlert
const notificaciones = {
    exito: (mensaje = "", titulo = "") => toast("success", mensaje),
    success: (mensaje = "", titulo = "") => toast("success", mensaje),
    info: (mensaje = "", titulo = "") => toast("info", mensaje),
    warning: (mensaje = "", titulo = "") => toast("warning", mensaje),
    error: (mensaje = "", titulo = "") => {
        Swal.fire({
            position: "top-end",
            icon: "error",
            title: titulo,
            text: mensaje,
            showConfirmButton: false,
            timer: 1800,
        });
    },

    error_validacion: (mensaje = "", titulo = "") => {
        Command: toastr["error"](mensaje);
    },
    errores: (obj) => {
        try {
            for (let key in obj) {
                const baseKey = key.includes('.') ? key.split('.')[0] : key;
                const elementId = '_' + baseKey;
                const errorEl = document.getElementById(elementId);
                const inputEl = document.querySelector(`[name="${baseKey}"]`);
                const mensaje = Array.isArray(obj[key]) ? obj[key][0] : obj[key];

                if (inputEl) inputEl.classList.add('is-invalid');

                if (errorEl) {
                    errorEl.innerHTML += `
                        <small class="d-flex align-items-center gap-1 mt-1" style="color:#dc3545;font-size:.78rem">
                            <i class=" fas fa-exclamation-circle"></i> ${mensaje}
                        </small>`;
                } else {
                    console.warn(`[mensajes.js] Sin div de error para: ${key}`);
                }
            }
        } catch (error) {
            console.error('[mensajes.js] Error procesando errores:', error);
        }
    },
    // Puedes agregar más tipos según sea necesario
};

export function mensajeAlerta(mensaje = "", titulo = "") {
    if (notificaciones.hasOwnProperty(titulo)) {
        notificaciones[titulo](mensaje, titulo);
    }
}
