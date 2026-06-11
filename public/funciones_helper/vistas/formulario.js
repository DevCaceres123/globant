export function mensajeInputs(mensaje, color, campo) {

    $(`#error_${campo} spam b`).val = "";
    $(`#error_${campo} spam b`).html(mensaje).css("color", color);
}


export function vaciar_errores(nombre_formulario) {
    try {
        const form = document.getElementById(nombre_formulario);
        if (!form) {
            throw new Error(`Formulario con ID '${nombre_formulario}' no encontrado`);
        }

        const elements = form.querySelectorAll("input:not([type=hidden]), textarea, select");
        const fieldNames = Array.from(elements).map(element => element.name);

        fieldNames.forEach(name => {
            const cleanName = name.replace(/\[\]$/, '');

            const errorElement = document.getElementById('_' + cleanName);
            if (errorElement) errorElement.innerHTML = '';

            const inputElement = form.querySelector(`[name="${name}"]`);
            if (inputElement) inputElement.classList.remove('is-invalid');
        });
    } catch (error) {
        console.error("Error en el procesamiento del formulario:", error);
    }
}


//para vaciar los input, textrarea y select
export function vaciar_formulario(formulario) {
    document.getElementById(formulario).reset();
}

/**
 * Alterna la visibilidad de un campo de contraseña.
 *
 * @param {string} inputId - ID del input de tipo password
 * @param {string} iconoId - ID del elemento <i> con el ícono de ojo (FontAwesome)
 *
 * Ejemplo en HTML:
 *   <input type="password" id="password">
 *   <i id="icono_password" class="fas fa-eye-slash"></i>
 *
 * Ejemplo de uso:
 *   import { toggleContrasenia } from '...formulario.js';
 *   document.getElementById('icono_password').addEventListener('click', () => {
 *       toggleContrasenia('password', 'icono_password');
 *   });
 */
export function toggleContrasenia(inputId, iconoId) {
    const input = document.getElementById(inputId);
    const icono = document.getElementById(iconoId);

    if (!input || !icono) return;

    const visible = input.type === 'text';
    input.type = visible ? 'password' : 'text';
    icono.classList.toggle('fa-eye',      visible);
    icono.classList.toggle('fa-eye-slash', !visible);
}