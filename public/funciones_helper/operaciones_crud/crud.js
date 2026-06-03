/**
 * Función central para comunicación asíncrona con el servidor.
 *
 * @param {string}   url        - Ruta sin slash inicial (ej: "admin/usuario")
 * @param {string}   metodo     - Método HTTP: GET | POST | PUT | PATCH | DELETE
 * @param {*}        idRegistro - ID del recurso (null para colecciones)
 * @param {Object|FormData|null} datos - Cuerpo de la petición (null para GET/DELETE)
 * @param {Function} callback   - function(error, response) — siempre verificar error primero
 *
 * Casos de uso:
 *   crud("admin/usuario", "GET",    null, null,   cb)  → GET    /admin/usuario
 *   crud("admin/usuario", "GET",    5,    null,   cb)  → GET    /admin/usuario/5
 *   crud("admin/usuario", "POST",   null, datos,  cb)  → POST   /admin/usuario
 *   crud("admin/usuario", "PUT",    5,    datos,  cb)  → PUT    /admin/usuario/5  (o POST+_method si es FormData)
 *   crud("admin/usuario", "PATCH",  5,    datos,  cb)  → PATCH  /admin/usuario/5  (o POST+_method si es FormData)
 *   crud("admin/usuario", "DELETE", 5,    null,   cb)  → DELETE /admin/usuario/5
 */
export async function crud(url, metodo, idRegistro = null, datos = null, callback) {
    try {
        const csrfToken = document
            .querySelector('meta[name="csrf-token"]')
            .getAttribute("content");

        const headers = { "X-CSRF-TOKEN": csrfToken };
        const esFormData = datos instanceof FormData;

        // Construir la URI final
        const uri = idRegistro != null ? `/${url}/${idRegistro}` : `/${url}`;

        // PHP no puede leer multipart/form-data en PUT ni PATCH.
        // Solución: enviar como POST con el campo _method para que Laravel haga el spoofing.
        let metodoReal = metodo;
        if (esFormData && (metodo === "PUT" || metodo === "PATCH")) {
            if (!datos.has("_method")) {
                datos.append("_method", metodo);
            }
            metodoReal = "POST";
        }

        // Preparar el body y Content-Type
        let body = null;
        if (datos !== null) {
            if (esFormData) {
                // No fijar Content-Type: el browser lo establece automáticamente con el boundary
                body = datos;
            } else {
                headers["Content-Type"] = "application/json";
                body = JSON.stringify(datos);
            }
        }

        const response = await fetch(uri, {
            method: metodoReal,
            headers,
            body,
        });

        // 422 se deja pasar para que el callback maneje los errores de validación
        if (!response.ok && response.status !== 422) {
            throw new Error(`Error del servidor: ${response.status}`);
        }

        const respuestaParseada = await response.json();
        callback(null, respuestaParseada);

    } catch (error) {
        console.error("[crud.js] Error capturado:", error);
        try {
            callback(error, null);
        } catch (callbackError) {
            console.error("[crud.js] El callback lanzó un error — recuerda verificar el parámetro 'error' antes de acceder a 'response':", callbackError);
        }
    }
}
