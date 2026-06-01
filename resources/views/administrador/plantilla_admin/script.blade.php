<script type="module">
    /* =========================================================
       CERRAR SESIÓN
       (usa el helper modular de mensajes: mensajes.js)
       ========================================================= */
    import { mensajeAlerta } from "{{ asset('funciones_helper/notificaciones/mensajes.js') }}";

    document.getElementById('logout-btn').addEventListener('click', async function (e) {
        const resultado = await Swal.fire({
            title: "NOTA!",
            text: "¿Está seguro de cerrar la sesión?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, estoy seguro",
            cancelButtonText: "Cancelar",
        });

        if (resultado.isConfirmed) {
            await cerrar_session_cam();
        } else {
            mensajeAlerta('Cancelado....!!', 'info');
        }
    });

    async function cerrar_session_cam() {
        let datos = Object.fromEntries(new FormData(document.getElementById('formulario_salir')).entries());
        try {
            let respuesta = await fetch("{{ route('salir') }}", {
                method: "POST",
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(datos)
            });
            let dato = await respuesta.json();
            mensajeAlerta(dato.mensaje, dato.tipo);
            setTimeout(() => {
                location.reload();
            }, 1500);
        } catch (error) {
            console.log('Ocurrio un error: ' + error);
        }
    }
</script>

<script>
    /* =========================================================
       CONFIGURACIÓN GLOBAL DE DATATABLES (español)
       ========================================================= */
    $.extend(true, $.fn.dataTable.defaults, {
        language: {
            processing:     "Procesando...",
            search:         "Buscar:",
            lengthMenu:     "Mostrar _MENU_ registros",
            info:           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            infoEmpty:      "Mostrando registros del 0 al 0 de un total de 0 registros",
            infoFiltered:   "(filtrado de un total de _MAX_ registros)",
            loadingRecords: "Cargando...",
            zeroRecords:    "No se encontraron resultados",
            emptyTable:     "Ningún dato disponible en esta tabla",
            paginate: {
                first:      "Primero",
                previous:   "Anterior",
                next:       "Siguiente",
                last:       "Último"
            },
            aria: {
                sortAscending:  ": Activar para ordenar la columna de manera ascendente",
                sortDescending: ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
</script>
