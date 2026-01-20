@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-light-info">
                <h3 class="card-title fw-bold">REPORTE PROCESO POR OT</h3>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('reporte.proceso.pdf') }}" target="_blank">
                    @csrf
                    <div class="row">

                        <div class="col-md-6">
                            <label>Factura</label>
                            <select id="factura_select" class="form-control" required>
                                <option value="">Seleccione Factura</option>
                                @foreach($facturas as $factura)
                                    <option value="{{ $factura->id }}">
                                        {{ $factura->numero_factura }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label>OT</label>
                            <select id="numero_ot_select" name="order_trabajo_id" class="form-control" required>
                                <option value="">Seleccione OT</option>
                            </select>
                        </div>

                        <input type="hidden" name="factura_id" id="factura_id">

                        <div class="col-md-12 mt-3">
                            <button class="btn btn-success">Generar PDF</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        const urlObtenerOTs = "{{ route('reporte.proceso.obtenerOTs', ':factura_id') }}";
    </script>

    <script>
        $(document).ready(function () {
            $('#factura_select').on('change', function () {
                let facturaId = $(this).val();
                $('#factura_id').val(facturaId);

                if (!facturaId) {
                    $('#numero_ot_select').html('<option value="">Seleccione OT</option>');
                    return;
                }


                let url = urlObtenerOTs.replace(':factura_id', facturaId);

                $.get(url, function (data) {
                    let html = '<option value="">Seleccione OT</option>';

                    if (data.length > 0) {
                        data.forEach(function (ot) {
                            html += `<option value="${ot.id}">OT ${ot.nro_ot}</option>`;
                        });
                    } else {
                        html += '<option value="">No hay OTs disponibles</option>';
                    }

                    $('#numero_ot_select').html(html);
                }).fail(function (xhr) {
                    console.error('Error AJAX OTs:', xhr);
                });
            });
        });
    </script>
@endsection