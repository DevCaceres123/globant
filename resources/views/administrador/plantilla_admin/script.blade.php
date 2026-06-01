<script>
    //para cerrar la session

    document.getElementById('logout-btn').addEventListener('click', function (e) {
        Swal.fire({
            title: "NOTA!",
            text: "¿Está seguro de Eliminar la carrera?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Sí, Estoy seguro",
            cancelButtonText: "Cancelar",
        }).then(async function (result) {
            if (result.isConfirmed) {
                await cerrar_session_cam();

            } else {
                alert("Se canceló la eliminacion");
            }
        });
    });

    //PARA CERRAR LA SESION
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
            Swal.mixin({
                toast: !0,
                position: "top-end",
                showConfirmButton: !1,
                timer: 1500,
                timerProgressBar: !0,
                didOpen: e => {
                    e.addEventListener("mouseenter", Swal.stopTimer), e.addEventListener("mouseleave", Swal
                        .resumeTimer)
                }
            }).fire({
                icon: dato.tipo,
                title: dato.mensaje,
            })
            setTimeout(() => {
                location.reload();
            }, 1500);
        } catch (error) {
            console.log('Ocurrio un error: ' + error);
        }
    }
</script>



<script>
    // Configuración global de DataTables en español
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