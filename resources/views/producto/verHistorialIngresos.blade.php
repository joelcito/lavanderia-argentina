<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_roles">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Producto</th>
                <th>Sucursal</th>
                <th>Cantidad</th>
                <th>Fecha</th>
                <th>Codigo Compra</th>
                <th>Precio</th>
                <th>Precio Kg</th>
                <th>Precio G</th>
                <th>Descripcion</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($movimientos as $movimiento)
                <tr>
                    <td>{{ $movimiento->producto->nombre }}</td>
                    <td>{{ $movimiento->sucursal->nombre }}</td>
                    <td>{{ $movimiento->ingreso }}</td>
                    <td>{{ $movimiento->fecha }}</td>
                    <td>{{ $movimiento->codigo_compra }}</td>
                    <td>{{ $movimiento->precio }}</td>
                    <td>{{ $movimiento->precio_compra_kg }}</td>
                    <td>{{ $movimiento->precio_compra_g }}</td>
                    <td>{{ $movimiento->descripcion }}</td>
                </tr>
            @empty
                <h4 class="text-danger">No hay datos</h4>
            @endforelse
        </tbody>
    </table>
    <!--end::Table-->
</div>

<script>
    $(document).ready(function() {
        $('#kt_table_roles').DataTable({
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
</script>
