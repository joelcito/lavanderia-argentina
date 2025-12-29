<div style="overflow-x: auto;">
    <table class="table table-bordered table-hover" id="table_ots">
        <thead>
            <tr>
                <th>Número OT</th>
                <th>Numero de Venta</th>
                <th>Estado OT</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($ots as $ot)
                <tr>
                    <td>{{ $ot->nro_ot }}</td>
                    <td>
                        {{ $ot->factura->numero_factura ?? 'SIN FACTURA' }}
                    </td>
                    <td>{{ $ot->estado }}</td>

                    <td>
                        <button class="btn btn-info btn-sm" onclick="verDetalleOT({{ $ot->id }})">
                            Ver detalle OT
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="finalizarOT({{ $ot->id }})">
                            <i class="fa fa-check"></i> Finalizar OT
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No hay OTs registradas</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>


<div class="modal fade" id="modalDetalleOT" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-light-info">
                <h5 class="modal-title">Detalle de Procesos de la OT</h5>
                <button type="button" class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="detalleOTContent" style="overflow-x: auto;"></div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#kt_table_usuarios').DataTable({
            lengthMenu: [10, 25, 50, 100], // Opciones de longitud de página
            dom: '<"dt-head row"<"col-md-6"l><"col-md-6"f>><"clear">t<"dt-footer row"<"col-md-5"i><"col-md-7"p>>', // Use dom for basic layout
            language: {
                paginate: {
                    first: 'Primero',
                    last: 'Último',
                    next: 'Siguiente',
                    previous: 'Anterior'
                },
                search: 'Buscar:',
                lengthMenu: 'Mostrar _MENU_ registros por página',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                emptyTable: 'No hay datos disponibles'
            },
            order: [],
            //  searching: true,
            responsive: true
        });


    });

    function verDetalleOT(ot_id) {
        $.get("/procesos/detalle-ot/" + ot_id, function (data) {
            let html = '<table class="table table-bordered">';
            html += '<thead><tr><th>Producto</th><th>Maquinaria</th><th>Tipo Proceso</th><th>Fecha Ingreso</th><th>Fecha Salida</th><th>Estado</th></tr></thead><tbody>';

            data.forEach(p => {
                html += `<tr>
                        <td>${p.producto?.nombre ?? '-'}</td>
                        <td>${p.maquinaria?.tipo ?? '-'}</td>
                        <td>${p.tipo_proceso?.nombre ?? '-'}</td>
                        <td>${p.fecha_ingreso ?? '-'}</td>
                        <td>${p.fecha_salida ?? '-'}</td>
                        <td>${p.estado ?? '-'}</td>
                     </tr>`;
            });

            html += '</tbody></table>';
            $('#detalleOTContent').html(html);
            $('#modalDetalleOT').modal('show');
        });
    }



    function finalizarOT(ot_id) {
        Swal.fire({
            title: '¿Finalizar OT?',
            text: 'Esta acción marcará la orden como FINALIZADA',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, finalizar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {

                $.post("{{ route('procesos.finalizarOT') }}", {
                    id: ot_id
                }, function (res) {

                    if (res.estado) {
                        Swal.fire('Finalizado', res.mensaje, 'success');
                        ajaxListado(); // recargar listado
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }

                });

            }
        });
    }
</script>