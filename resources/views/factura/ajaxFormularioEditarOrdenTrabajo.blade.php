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
                        <select name="" id="" style="width: 100%;">
                            @foreach ($prendas as $prenda)
                                <option {{ $ordenTrabajo->prenda_id == $prenda->id? 'selected' : '' }} value="{{ $prenda->id }}">{{ $prenda->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" value="{{ $ordenTrabajo->numero_ojales }}" style="width: 100%"></td>
                    <td>
                        <select name="" id="" style="width: 100%;">
                            @foreach ($telas as $tela)
                                <option {{ $ordenTrabajo->tela_id == $tela->id? 'selected' : '' }} value="{{ $tela->id }}">{{ $tela->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="" id="" style="width: 100%;">
                            @foreach ($prelavados as $prelavado)
                                <option {{ $ordenTrabajo->prelavado_id == $prelavado->id? 'selected' : '' }} value="{{ $prelavado->id }}">{{ $prelavado->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="" id="" style="width: 100%;">
                            @foreach ($nevados as $nevado)
                                <option {{ $ordenTrabajo->nevado_id == $nevado->id? 'selected' : '' }} value="{{ $nevado->id }}">{{ $nevado->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="" id="" style="width: 100%;">
                            @foreach ($focalizados as $focalizado)
                                <option {{ $ordenTrabajo->focalizado_id == $focalizado->id? 'selected' : '' }} value="{{ $focalizado->id }}">{{ $focalizado->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="" id="" style="width: 100%;">
                            @foreach ($tipoTelas as $tipoTela)
                                <option {{ $ordenTrabajo->tipo_tela_id == $tipoTela->id? 'selected' : '' }} value="{{ $tipoTela->id }}">{{ $tipoTela->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="" id="" style="width: 100%;">
                            @foreach ($colorTelas as $coloTela)
                                <option {{ $ordenTrabajo->color_tela_id == $coloTela->id? 'selected' : '' }} value="{{ $coloTela->id }}">{{ $coloTela->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <select name="" id="" style="width: 100%;">
                            @foreach ($caracteristicaTelas as $caracteristicaTela)
                                <option {{ $ordenTrabajo->color_tela_id == $caracteristicaTela->id? 'selected' : '' }} value="{{ $caracteristicaTela->id }}">{{ $caracteristicaTela->nombre }}</option>
                            @endforeach
                        </select>
                    </td>
                    <td><input type="text" value="{{ $ordenTrabajo->peso }}" style="width: 100%"></td>
                    <td><input type="text" value="{{ $ordenTrabajo->precio }}" style="width: 100%"></td>
                    <td><input type="text" value="{{ $ordenTrabajo->subtotal }}" style="width: 100%"></td>
                    <td><input type="text" value="{{ $ordenTrabajo->observacion }}" style="width: 100%"></td>
                    <td><input type="text" value="{{ $ordenTrabajo->nro_ot }}" style="width: 100%"></td>
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
