{{-- <div style="overflow-x: auto;"> --}}
    <!--begin::Table-->
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_laser">
        <thead>
            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                <th>Talla</th>
                <th>Cantidad</th>
                <th>Intens</th>
                <th>Altura</th>
                <th>Dpi</th>
                <th>Pos._1__</th>
                <th>Pos._2__</th>
                <th>Pos._3__</th>
                <th>Pos._4__</th>
                <th>Pre x Mesa</th>
                <th>Seg/Pre</th>
                <th>Pre Pronostico</th>
                <th>Precio Base</th>
                <th>Precio Cliente</th>
                <th>Sub Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-600 fw-semibold">
            @forelse ($ordem_trabajos as $ordenTrabajo)
                <tr>
                    <td>
                        <input name="modificar_talla_laser_{{ $ordenTrabajo->id }}" id="modificar_talla_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('talla_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="text" value="{{ $ordenTrabajo->talla }}">{{--{{ $ordenTrabajo->talla }}--}}
                        <span style="display: none" id="text_talla_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_cantidad_laser_{{ $ordenTrabajo->id }}" id="modificar_cantidad_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('cantidad_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->cantidad }}">{{--{{ $ordenTrabajo->cantidad }}--}}
                        <span style="display: none" id="text_cantidad_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_intensidad_laser_{{ $ordenTrabajo->id }}" id="modificar_intensidad_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('intensidad_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->intensidad }}">{{--{{ $ordenTrabajo->intensidad }}--}}
                        <span style="display: none" id="text_intensidad_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_altura_laser_{{ $ordenTrabajo->id }}" id="modificar_altura_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('altura_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->altura }}">{{--{{ $ordenTrabajo->altura }}--}}
                        <span style="display: none" id="text_altura_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_dpi_laser_{{ $ordenTrabajo->id }}" id="modificar_dpi_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('dpi_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->dpi }}">{{--{{ $ordenTrabajo->dpi }}--}}
                        <span style="display: none" id="text_dpi_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_pos_1_laser_{{ $ordenTrabajo->id }}" id="modificar_pos_1_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('pos_1_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->posicion_1 }}">{{--{{ $ordenTrabajo->posicion_1 }}--}}
                        <span style="display: none" id="text_pos_1_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_pos_2_laser_{{ $ordenTrabajo->id }}" id="modificar_pos_2_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('pos_2_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->posicion_2 }}">{{--{{ $ordenTrabajo->posicion_2 }}--}}
                        <span style="display: none" id="text_pos_2_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_pos_3_laser_{{ $ordenTrabajo->id }}" id="modificar_pos_3_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('pos_3_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->posicion_3 }}">{{--{{ $ordenTrabajo->posicion_3 }}--}}
                        <span style="display: none" id="text_pos_3_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_pos_4_laser_{{ $ordenTrabajo->id }}" id="modificar_pos_4_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('pos_4_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->posicion_4 }}">{{--{{ $ordenTrabajo->posicion_4 }}--}}
                        <span style="display: none" id="text_pos_4_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_prenda_x_mesa_laser_{{ $ordenTrabajo->id }}" id="modificar_prenda_x_mesa_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('prenda_x_mesa_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->nro_prenda_mesa }}">{{--{{ $ordenTrabajo->nro_prenda_mesa }}--}}
                        <span style="display: none" id="text_prenda_x_mesa_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_tiempo_total_laser_{{ $ordenTrabajo->id }}" id="modificar_tiempo_total_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('tiempo_total_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->tiempo }}" readonly>{{--{{ $ordenTrabajo->tiempo }}--}}
                        <span style="display: none" id="text_tiempo_total_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_precio_pronosticado_{{ $ordenTrabajo->id }}" id="modificar_precio_pronosticado_{{ $ordenTrabajo->id }}" onchange="filaModificado('precio_pronosticado', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->precio_pronosticado }}" readonly>{{--{{ $ordenTrabajo->precio_pronosticado }}--}}
                        <span style="display: none" id="text_precio_pronosticado_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_precio_minuto_valor_{{ $ordenTrabajo->id }}" id="modificar_precio_minuto_valor_{{ $ordenTrabajo->id }}" onchange="filaModificado('precio_minuto_valor', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->precio_minuto }}">{{--{{ $ordenTrabajo->precio_minuto }}--}}
                        <span style="display: none" id="text_precio_minuto_valor_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_precio_cliente_{{ $ordenTrabajo->id }}" id="modificar_precio_cliente_{{ $ordenTrabajo->id }}" onchange="filaModificado('precio_cliente', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->precio }}">{{--{{ $ordenTrabajo->precio }}--}}
                        <span style="display: none" id="text_precio_cliente_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <input name="modificar_valor_laser_{{ $ordenTrabajo->id }}" id="modificar_valor_laser_{{ $ordenTrabajo->id }}" onchange="filaModificado('valor_laser', '{{$ordenTrabajo->id}}')" autocomplete="off" style="width: 100%" type="number" value="{{ $ordenTrabajo->subtotal }}" readonly>{{--{{ $ordenTrabajo->subtotal }}--}}
                        <span style="display: none" id="text_valor_laser_{{$ordenTrabajo->id}}" class="text-warning">Modificado!!!</span>
                    </td>
                    <td>
                        <button title="Editar Laser" onclick="editarLaser({{$ordenTrabajo->id}})" class="btn btn-circle btn-icon btn-sm btn-success"><i class="fa fa-save"></i></button>
                        <button title="Eliminar Laser" onclick="eliminarLaser({{$ordenTrabajo->id}})" class="btn btn-circle btn-icon btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            @empty
                <h4 class="text-danger">No hay datos</h4>
            @endforelse
        </tbody>
    </table>
    <!--end::Table-->
{{-- </div> --}}

<script>
    $(document).ready(function() {
        $('#kt_table_laser').DataTable({
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
        // responsive: true
        });


    });
</script>
