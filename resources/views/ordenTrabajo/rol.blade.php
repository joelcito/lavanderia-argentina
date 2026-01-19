@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-light-info">
                <h3 class="card-title fw-bold">Planchado / Focalizado - Ingreso de Cantidades</h3>
            </div>
            <div class="card-body">


                <div class="row mb-3">
                    <div class="col-md-6">
                        <label>Factura / Nota de Recepcion</label>
                        <select id="factura_select" class="form-control">
                            <option value="">Seleccione Factura / Nota Recepcion</option>
                            @foreach($facturas as $factura)
                                <option value="{{ $factura->id }}">{{ $factura->numero_factura }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label>OT</label>
                        <select id="ot_select" class="form-control">
                            <option value="">Seleccione OT</option>
                        </select>
                    </div>
                </div>


                <button class="btn btn-primary mb-3" id="abrirModal" disabled>Ingresar Cantidad</button>


                <div class="table-responsive">
                    <table class="table table-bordered" id="tablaCantidades">
                        <thead>
                            <tr>
                                <th>Factura / Nota de Recepcion</th>
                                <th>Nro. OT</th>
                                <th>Cantidad Planchado</th>
                                <th>Cantidad Focalizado</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalCantidad" tabindex="-1" aria-labelledby="modalCantidadLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="formCantidad">
                @csrf
                <input type="hidden" name="factura_id" id="modal_factura_id">
                <input type="hidden" name="order_trabajo_id" id="modal_ot_id">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCantidadLabel">Ingresar Cantidad</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Tipo de Proceso</label>
                            <select name="tipo" id="tipo" class="form-control" required>
                                <option value="">Seleccione</option>
                                <option value="Planchado">Planchado</option>
                                <option value="Focalizado">Focalizado</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {

            $('#factura_select').on('change', function () {
                let facturaId = $(this).val();
                $('#ot_select').html('<option value="">Seleccione OT</option>');
                $('#abrirModal').prop('disabled', true);

                if (!facturaId) return;

                // $.get(`/ordenTrabajo/ots/${facturaId}`, function (data) {
                let urlBase = "{{ route('order-trabajo.obtenerOTs', ':id') }}";
                let d = urlBase.replace(':id', facturaId);
                $.get(d, function (data) {
                    if (data.length > 0) {
                        data.forEach(function (ot) {
                            $('#ot_select').append(`<option value="${ot.id}">OT ${ot.nro_ot}</option>`);
                        });
                    }
                });
            });


            $('#ot_select').on('change', function () {
                let otId = $(this).val();
                $('#abrirModal').prop('disabled', !otId);
            });


            $('#abrirModal').on('click', function () {
                $('#modal_factura_id').val($('#factura_select').val());
                $('#modal_ot_id').val($('#ot_select').val());
                $('#formCantidad')[0].reset();
                $('#modalCantidad').modal('show');
            });


            $('#formCantidad').on('submit', function (e) {
                e.preventDefault();
                let formData = $(this).serialize();

                $.post("{{ route('order-trabajo.guardarCantidad') }}", formData, function (response) {
                    $('#modalCantidad').modal('hide');

                    Swal.fire({
                        icon: 'success',
                        title: 'Guardado',
                        text: response.mensaje,
                    });


                    listarCantidades();
                });
            });


            function listarCantidades() {
                $.get("{{ route('order-trabajo.listarCantidades') }}", function (data) {
                    let html = '';
                    data.forEach(function (item) {
                        html += `<tr>
                            <td>${item.factura_numero}</td>
                            <td>${item.nro_ot}</td>
                            <td>${item.cantidad_planchado ?? 0}</td>
                            <td>${item.cantidad_focalizado ?? 0}</td>
                        </tr>`;
                    });
                    $('#tablaCantidades tbody').html(html);
                });
            }


            listarCantidades();

        });
    </script>
@endsection
