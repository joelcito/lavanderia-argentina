{{-- @dd($solicitudArray) --}}
<div style="overflow-x: auto;">
    <table class="table table-bordered table-hover" id="kt_table_color_tela">
        <thead>
            <tr>
                <th>Agrupados para proceso</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            {{-- @dd($solicitudArray) --}}
            @forelse ($solicitudArray as $key => $solcitudAgrupado)
                @php
                    $solicitud_id = $key;


                    $proceso = $procesos[$solicitud_id] ?? null;
                @endphp
                @if (($proceso->estado ?? null) == "TRABAJANDO")
                    <tr>
                        <td>{{ $solcitudAgrupado }}</td>
                        <td>
                            <span class="badge badge-warning">
                                {{ $proceso->estado ?? '' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm" onclick="abrirModalFocalizado('{{ $solicitud_id }}')">
                                <i class="fa fa-plus"></i>
                            </button>
                            <button title="Terminar Proceso Focalizado" class="btn btn-dark btn-icon btn-sm"
                                onclick="finalizarFocalizado('{{ $solicitud_id }}')"><i class="fa fa-up-down"></i></button>


                        </td>
                    </tr>
                @endif
            @empty
                <span class="text-danger">No hay OTs registradas</span>
            @endforelse
        </tbody>
    </table>
</div>

{{--
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


<div class="modal fade" id="modalProcesoOT" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-light-primary">
                <h5 class="modal-title" id="tituloProcesoOT"></h5>
                <button type="button" class="btn btn-icon btn-sm" data-bs-dismiss="modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <input type="hidden" id="ot_id">
                <input type="hidden" id="tipo_proceso">

                <div class="mb-3">
                    <label>Total de prendas</label>
                    <input type="text" class="form-control" id="total_prendas" readonly>
                </div>

                <div class="mb-3">
                    <label id="labelCantidadProceso"></label>
                    <input type="number" min="1" class="form-control" id="cantidad_proceso">
                </div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-primary" onclick="guardarProcesoOT()">Guardar</button>
            </div>
        </div>
    </div>
</div> --}}

<script>
    $(document).ready(function () {
        $('#kt_table_color_tela').DataTable({
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

    // function verDetalleOT(ot_id) {
    //     $.get("/procesos/detalle-ot/" + ot_id, function (data) {
    //         let html = '<table class="table table-bordered">';
    //         html += '<thead><tr><th>Producto</th><th>Nº</th><th>Maquinaria</th><th>Tipo Proceso</th><th>Fecha Ingreso</th><th>Fecha Salida</th><th>Estado</th></tr></thead><tbody>';

    //         data.forEach(p => {
    //             html += `<tr>
    //                     <td>${p.producto?.nombre ?? '-'}</td>
    //                     <td>${p.maquinaria?.numero ?? '-'}</td>
    //                     <td>${p.maquinaria?.tipo ?? '-'}</td>
    //                     <td>${p.tipo_proceso?.nombre ?? '-'}</td>
    //                     <td>${p.fecha_ingreso ?? '-'}</td>
    //                     <td>${p.fecha_salida ?? '-'}</td>
    //                     <td>${p.estado ?? '-'}</td>
    //                  </tr>`;
    //         });

    //         html += '</tbody></table>';
    //         $('#detalleOTContent').html(html);
    //         $('#modalDetalleOT').modal('show');
    //     });
    // }


    // function focalizarOT(ot_id) {
    //     abrirModalProceso(ot_id, 'focalizado');
    // }

    // function plancharOT(ot_id) {
    //     abrirModalProceso(ot_id, 'planchado');
    // }

    // function abrirModalProceso(ot_id, tipo) {

    //     $.get('/procesos/obtener-ot/' + ot_id, function (ot) {

    //         $('#ot_id').val(ot.id);
    //         $('#tipo_proceso').val(tipo);
    //         $('#total_prendas').val(ot.cantidad);
    //         $('#cantidad_proceso').val('');

    //         if (tipo === 'focalizado') {
    //             $('#tituloProcesoOT').text('Focalizar prendas');
    //             $('#labelCantidadProceso').text('Cantidad de prendas a focalizar');
    //         } else {
    //             $('#tituloProcesoOT').text('Planchar prendas');
    //             $('#labelCantidadProceso').text('Cantidad de prendas a planchar');
    //         }

    
    //         $('#modalProcesoOT').modal('show');
    //     });
    // }


    // function guardarProcesoOT() {

    //     let ot_id = $('#ot_id').val();
    //     let tipo = $('#tipo_proceso').val(); // 'focalizado' o 'planchado'
    //     let cantidad = $('#cantidad_proceso').val();

    //     // Validación rápida
    //     if (!cantidad || cantidad <= 0) {
    //         Swal.fire('Error', 'Ingrese una cantidad válida', 'error');
    //         return;
    //     }

    //     $.post("{{ route('procesos.guardarProcesoOT') }}", {
    //         _token: "{{ csrf_token() }}",
    //         ot_id: ot_id,
    //         tipo: tipo,
    //         cantidad: cantidad
    //     }, function (res) {
    //         if (res.estado) {
    //             Swal.fire('Correcto', res.mensaje, 'success');
    //             $('#modalProcesoOT').modal('hide');
    //             ajaxListado(); // recarga tabla
    //         } else {
    //             Swal.fire('Error', res.mensaje, 'error');
    //         }
    //     }).fail(function () {
    //         Swal.fire('Error', 'No se pudo guardar', 'error');
    //     });
    // }




    // function finalizarOT(ot_id) {
    //     Swal.fire({
    //         title: '¿Finalizar OT?',
    //         text: 'Esta acción marcará la orden como FINALIZADA',
    //         icon: 'warning',
    //         showCancelButton: true,
    //         confirmButtonText: 'Sí, finalizar',
    //         cancelButtonText: 'Cancelar'
    //     }).then((result) => {
    //         if (result.isConfirmed) {

    //             $.post("{{ route('procesos.finalizarOT') }}", {
    //                 id: ot_id
    //             }, function (res) {

    //                 if (res.estado) {
    //                     Swal.fire('Finalizado', res.mensaje, 'success');
    //                     ajaxListado(); // recargar listado
    //                 } else {
    //                     Swal.fire('Error', res.mensaje, 'error');
    //                 }

    //             });

    //         }
    //     });
    // }


</script>