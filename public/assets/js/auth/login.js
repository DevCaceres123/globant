// =========================================================
//  LOGIN — Sistema de Afiliados
//  Maneja el inicio de sesión por AJAX (respuesta JSON {tipo, mensaje}).
// =========================================================

// Seleccionar elementos del DOM
let loginBtn = document.getElementById('btn_ingresar_usuario');
let formularioLogin = document.getElementById('formulario_login');
let mensajeError = document.getElementById('mensaje_error');

// URL de login: se inyecta desde el Blade con data-url en el <form>
let urlIngresar = formularioLogin.dataset.url;

// Mostrar alertas (éxito / error) con el estilo de la vista
function mostrarAlerta(tipo, mensaje) {
    let esExito = tipo === 'success';
    let claseCaja = esExito ? 'ok' : 'fail';
    let icono = esExito ? 'fa-circle-check' : 'fa-circle-exclamation';
    mensajeError.innerHTML = `
        <div class="alerta ${claseCaja}" role="alert">
            <i class="fas ${icono}"></i>
            <span>${mensaje}</span>
        </div>`;
    setTimeout(() => { mensajeError.innerHTML = ''; }, 4000);
}

// Habilitar / deshabilitar el botón con texto dinámico
function validarBoton(estaDeshabilitado, mensaje) {
    loginBtn.innerHTML = mensaje;
    loginBtn.disabled = estaDeshabilitado;
}

// Enviar el formulario (login AJAX -> JSON {tipo, mensaje})
formularioLogin.addEventListener('submit', async (e) => {
    e.preventDefault();
    let datos = Object.fromEntries(new FormData(formularioLogin).entries());
    validarBoton(true, '<i class="fas fa-spinner fa-spin"></i> Verificando...');
    try {
        let respuesta = await fetch(urlIngresar, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos)
        });
        if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        let data = await respuesta.json();
        mostrarAlerta(data.tipo, data.mensaje);
        if (data.tipo === 'success') {
            validarBoton(true, '<i class="fas fa-check"></i> Acceso correcto');
            setTimeout(() => window.location.reload(), 1500);
        } else {
            validarBoton(false, '<span>INGRESAR</span> <i class="fas fa-arrow-right-to-bracket"></i>');
        }
    } catch (error) {
        console.error('Error:', error);
        mostrarAlerta('error', 'Ocurrió un error al procesar la solicitud');
        validarBoton(false, '<span>INGRESAR</span> <i class="fas fa-arrow-right-to-bracket"></i>');
    }
});

// Mostrar / ocultar contraseña
function togglePassword() {
    let passwordInput = document.getElementById("password");
    let iconPassword = document.getElementById("icono_password");
    let esTexto = passwordInput.type === "text";
    passwordInput.type = esTexto ? "password" : "text";
    iconPassword.classList.toggle("fa-eye-slash", esTexto);
    iconPassword.classList.toggle("fa-eye", !esTexto);
}
