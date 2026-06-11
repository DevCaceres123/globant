/**
 * Muestra un overlay de carga sobre el elemento indicado.
 * @param {string} selector - Selector CSS del contenedor (ej: '.card', '#mi-card')
 */
export function mostrarCarga(selector) {
    const el = $(selector);
    if (el.find('.overlay-carga').length) return;

    el.css('position', 'relative').append(`
        <div class="overlay-carga" style="
            position: absolute; inset: 0; z-index: 100;
            background: rgba(255,255,255,0.75);
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            border-radius: inherit; gap: .6rem;">
            <div class="spinner-border" style="color: var(--wine); width:4.2rem; height:4.2rem;" role="status"></div>
            <span style="font-size:.82rem; font-weight:600; color: var(--wine);">Espere por favor...</span>
        </div>
    `);
}

/**
 * Elimina el overlay de carga del elemento indicado.
 * @param {string} selector - Selector CSS del contenedor
 */
export function ocultarCarga(selector) {
    $(selector).find('.overlay-carga').remove();
}
