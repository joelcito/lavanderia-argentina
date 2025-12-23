{{-- <div style="overflow-x: auto;"> --}}
    <table style="width: 100%" id="kt_table_facturas">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Cantidad</th>
                <th>Prenda</th>
                <th>Ojales</th>
                <th>Tela</th>
                <th>P. Lav.</th>
                <th>Nevado</th>
                <th>Focalizado</th>
                <th>T. Tela</th>
                <th>Co. Tela</th>
                <th>Ca. Tela</th>
                <th>Peso</th>
                <th>Precio</th>
                <th>S. Total</th>
                <th>Obs</th>
                <th>N° OT</th>
                {{-- <th>Actions</th> --}}
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ( $ordenesTrabajos as $ordenTrabajo)
                <tr>
                    <td><input type="text" value="{{ $ordenTrabajo->cantidad }}" style="width: 100%"></td>
                    <td>
                        <select name="" id="">

                        </select>
                    </td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                    <td><input type="text" style="width: 100%"></td>
                </tr>
            @empty
                <h4 class="text-danger">No hay datos</h4>
            @endforelse
        </tbody>
    </table>
{{-- </div> --}}

<script>
    // $(document).ready(function() {
    //     $('#kt_table_facturas').DataTable({
    //         lengthMenu: [10, 25, 50, 100], // Opciones de longitud de página
    //         // dom: '<"dt-head row"<"col-md-6"l><"col-md-6"f>><"clear">t<"dt-footer row"<"col-md-5"i><"col-md-7"p>>', // Use dom for basic layout
    //         dom: 't<"dt-footer row"<"col-md-5"i><"col-md-7"p>>', // Use dom for basic layout
    //         language: {
    //             paginate: {
    //                 first: 'Primero',
    //                 last: 'Último',
    //                 next: 'Siguiente',
    //                 previous: 'Anterior'
    //             },
    //             search: 'Buscar:',
    //             lengthMenu: 'Mostrar _MENU_ registros por página',
    //             info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
    //             emptyTable: 'No hay datos disponibles'
    //         },
    //         order: [],
    //         //  searching: true,
    //         responsive: true
    //     });


    // });
</script>
