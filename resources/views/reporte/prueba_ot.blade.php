<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Prueba OT</title>
    <!-- Cargar jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <h2>Seleccionar Factura y OT</h2>

    <label>Factura</label>
    <select id="factura_select" class="form-control">
        <option value="">Seleccione Factura</option>
        @foreach($facturas as $factura)
            <option value="{{ $factura->id }}">{{ $factura->numero_factura }}</option>
        @endforeach
    </select>

    <br><br>

    <label>OT</label>
    <select id="numero_ot_select" class="form-control">
        <option value="">Seleccione OT</option>
    </select>
    <div class="col-md-12 mt-3">
        <button class="btn btn-success">Generar PDF</button>
    </div>

    <script>
        $(document).ready(function () {
            $('#factura_select').on('change', function () {
                let facturaId = $(this).val();
                if (!facturaId) {
                    $('#numero_ot_select').html('<option value="">Seleccione OT</option>');
                    return;
                }

                let url = "{{ route('reporte.proceso.obtenerOTs', ':id') }}".replace(':id', facturaId);

                $.get(url, function (data) {
                    let html = '<option value="">Seleccione OT</option>';
                    data.forEach(function (ot) {
                        html += `<option value="${ot.nro_ot}">OT ${ot.nro_ot}</option>`;
                    });
                    $('#numero_ot_select').html(html);
                }).fail(function (xhr, status, error) {
                    console.error("Error AJAX:", status, error);
                });
            });
        });
    </script>
</body>

</html>