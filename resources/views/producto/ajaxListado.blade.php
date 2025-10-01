<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_productos">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Proveedor</th>
                <th>Nombre</th>
                <th>Tipo</th>
                <th>Codigo</th>
                <th>Minimo Stock</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($productos as $producto)
                <tr>
                    <td>{{ $producto->proveedor_id }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->tipo }}</td>
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->minimo_stock }}</td>
                    <td>
                        <button class="btn btn-icon btn-sm btn-warning btn-circle" title="Editar producto" onclick="editarProducto({{ json_encode($producto) }})"><i class="fa fa-edit"></i></button>
                        <button class="btn btn-icon btn-sm btn-danger btn-circle" title="Eliminar producto" onclick="eliminarProducto('{{ $producto->id }}',  '{{ $producto->nombre }}')"><i class="fa fa-trash"></i></button>
                    </td>
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
        $('#kt_table_productos').DataTable({
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
