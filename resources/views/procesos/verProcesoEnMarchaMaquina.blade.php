<div class="row">
    <div class="col-md-4">
        <button class="btn btn-sm w-100 btn-dark" onclick="finalizarProceso({{ $procesos[0]->maquinaria->id }})"><i class="fa fa-stop"></i>Terminar</button>
    </div>
    <div class="col-md-4">
        <button class="btn btn-sm w-100 btn-warning" onclick='editarTiempoProceso(@json($procesos[0]))'><i class="fa fa-edit"></i>Editar tiempo</button>
    </div>
    <div class="col-md-4">
        <button class="btn btn-sm w-100 btn-primary" onclick="agregarProductoAlProceso({{ $procesos[0]->maquinaria_id }}, {{ $procesos[0]->tipo_proceso_id }})"><i class="fa fa-plus"></i>Agergar Producto</button>
    </div>
</div>
<div style="overflow-x: auto;">
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_procesos_trabajando">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Maquinaria</th>
                <th>Producto</th>
                <th>Tipo Proceso</th>
                <th>Fecha Ingreso</th>
                <th>Fecha Salida</th>
                <th>Tiempo</th>
                <th>Temperatura</th>
                <th>PH</th>
                <th>RB</th>
                <th>Descripcion</th>
                <th>Estado</th>
                {{-- <th></th> --}}
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($procesos as $proceso)
                <tr>
                    <td>{{ $proceso->maquinaria->tipo }}</td>
                    <td>{{ $proceso->producto?->nombre }}</td>
                    <td>{{ $proceso->tipoProceso->nombre }}</td>
                    <td>{{ $proceso->fecha_ingreso }}</td>
                    <td>{{ $proceso->fecha_salida }}</td>
                    <td>{{ $proceso->tiempo }}</td>
                    <td>{{ $proceso->temperatura }}</td>
                    <td>{{ $proceso->ph }}</td>
                    <td>{{ $proceso->rb }}</td>
                    <td>{{ $proceso->descripcion }}</td>
                    <td><span class="badge badge-warning">{{ $proceso->estado }}</span></td>
                    {{-- <td>
                        <button class="btn btn-icon btn-sm btn-circle btn-dark" title="Terminar Proceso" onclick="finalizarProceso({{ $proceso->maquinaria->id }})"><i class="fa fa-stop"></i></button>
                        <button class="btn btn-icon btn-circle btn-warning btn-sm" title="Editar Tiempo" ><i class="fa fa-edit"></i></button>
                    </td> --}}
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
        $('#kt_table_procesos_trabajando').DataTable({
            lengthMenu: [10, 25, 50,onclick='editarTiempoProceso(@json($proceso))' 100], // Opciones de longitud de página
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
