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
            @forelse ($solicitudArray as $solcitudAgrupado)
                <tr>
                    <td>
                        {{ $solcitudAgrupado['procesado'] }} <br>
                        <span class="text-primary">{{ $solcitudAgrupado['lavado'] ?? ''}}</span>
                    </td>
                    <td><span class="badge badge-warning">{{ $solcitudAgrupado['procesoFinal']->estado }}</span></td>

                    <td>
                        <button class="btn btn-sm btn-icon btn-success" title="Entregar"
                            onclick='abrirModalEntrega(@json($solcitudAgrupado["crudo"]))'>
                            <i class="fa fa-check"></i>
                        </button>
                        <button class="btn btn-sm btn-icon btn-danger" title="Generar reporte de Proceso"
                            onclick='imprimirHistorialProceso(@json($solcitudAgrupado["crudo"]))'><i
                                class="fa fa-file-pdf"></i></button>
                    </td>


                </tr>
            @empty
                <span class="text-danger">No hay OTs registradas</span>
            @endforelse
        </tbody>
    </table>
</div>

<div class="modal fade" id="modalEntrega">
    <div class="modal-dialog modal-lg">

        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title">Seleccionar OTs a entregar</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>

            </div>
            <div class="modal-body">
                <div id="listaOTs"></div>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button class="btn btn-success" onclick="confirmarEntrega()">Entregar</button>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function () {
        $('#kt_table_color_tela').DataTable({
            lengthMenu: [10, 25, 50, 100],
            dom: '<"dt-head row"<"col-md-6"l><"col-md-6"f>><"clear">t<"dt-footer row"<"col-md-5"i><"col-md-7"p>>',
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
                        ajaxListado();
                    } else {
                        Swal.fire('Error', res.mensaje, 'error');
                    }

                });

            }
        });
    }



    function abrirModalEntrega(data) {

        otsSeleccionadas = [];

        $.post("{{ route('entregas.ajaxListado') }}", {
            _token: "{{ csrf_token() }}",
            ordenes: JSON.stringify(data)
        }, function (res) {

            let html = '';

            let ahora = new Date();
            let fechaHora = ahora.toLocaleString();

            html += `
            <div class="mb-3 text-end">
                <small class="text-muted">
                    Fecha y hora: ${fechaHora}
                </small>
            </div>
        `;

            res.facturas.forEach(f => {

                html += `
                    <div class="mb-2">
                        <h5 class="text-primary">Factura: ${f.nro_factura}</h5>
                        <small class="text-muted">Cliente: ${f.cliente ?? 'Sin cliente'}</small>
                    </div>
                `;

                f.ots.forEach(ot => {

                    let disabled = ot.estado === 'ENTREGADO' ? 'disabled' : '';
                    let badge = ot.estado === 'ENTREGADO'
                        ? '<span class="badge bg-success ms-2">Entregado</span>'
                        : '';
                    html += `
                    <div class="form-check mb-2">
                    <input class="form-check-input"
                        type="checkbox"
                        ${disabled}
                        onchange="toggleOT(${ot.id})">

                    <label>
                        <strong>OT-${ot.nro_ot}</strong> |
                        ${ot.producto} |
                        Cant: ${ot.cantidad}
                        ${badge}
                    </label>
                </div>
                `;
                });

                html += `<hr>`;
            });

            $('#listaOTs').html(html);

            let modal = new bootstrap.Modal(document.getElementById('modalEntrega'));
            modal.show();
        });
    }

    function toggleOT(id) {
        if (otsSeleccionadas.includes(id)) {
            otsSeleccionadas = otsSeleccionadas.filter(x => x !== id);
        } else {
            otsSeleccionadas.push(id);
        }
    }


    function confirmarEntrega() {

        if (otsSeleccionadas.length === 0) {
            Swal.fire('Error', 'Seleccione al menos una OT', 'error');
            return;
        }

        $.post("{{ route('entregas.confirmarEntrega') }}", {
            _token: "{{ csrf_token() }}",
            ots: otsSeleccionadas
        }, function (res) {

            if (res.estado) {

                Swal.fire('Correcto', res.mensaje, 'success');

                let modalEl = document.getElementById('modalEntrega');
                let modal = bootstrap.Modal.getInstance(modalEl);
                document.activeElement.blur();
                modal.hide();

                ajaxListado();

            } else {
                Swal.fire('Error', res.mensaje, 'error');
            }
        });
    }


</script>