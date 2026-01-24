<form id="formularioNewOt">
    <div class="row">
        <input type="hidden" value="{{ $factura_id }}" id="factura_orden_trabajo" name="factura_orden_trabajo">
        <div class="col-md">
            <label class="required fw-semibold fs-6 mb-2">Cantidad</label>
            <input type="number" id="cantidad_venta" style="width: 100%" name="cantidad_venta"
                required min="1" value="0" onkeyup="calcularsubTotal()">
        </div>
        <div class="col-md">
            <label class="required fw-semibold fs-6 mb-2">Prenda</label>
            <select name="prenda_id" id="prenda_id" style="width: 100%"
                data-placeholder="SELECIONE" required>
                <option></option>
                @foreach ($prendas as $prenda)
                    <option value="{{ $prenda->id }}">{{ $prenda->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md">
            <label class="required fw-semibold fs-6 mb-2">Ojales</label>
            <input type="text" style="width: 100%" id="numero_ojales" name="numero_ojales"
                required onchange="cuantificarOjales()" onclick="this.select()">
        </div>
        <div class="col-md">
            <label class="fw-semibold fs-6 mb-2">Tela</label>
            <select name="tela_id" id="tela_id" style="width: 100%" data-placeholder="SELECIONE">
                <option></option>
                @foreach ($telas as $tela)
                    <option value="{{ $tela->id }}">{{ $tela->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md">
            <label class="required fw-semibold fs-6 mb-2">P. Lav.</label>
            <select name="prelavado_id" id="prelavado_id" data-placeholder="SELECIONE" required
                style="width: 100%">
                <option></option>
                @foreach ($prelavados as $prelavado)
                    <option value="{{ $prelavado->id }}">{{ $prelavado->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md">
            <label class="fw-semibold fs-6 mb-2">Nevado</label>
            <select name="nevado_id" id="nevado_id" data-placeholder="SELECIONE" style="width: 100%">
                <option></option>
                @foreach ($nevados as $nevado)
                    <option value="{{ $nevado->id }}">{{ $nevado->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md">
            <label class="fw-semibold fs-6 mb-2">Focalizado</label>
            <select name="focalizado_id" id="focalizado_id" data-placeholder="SELECIONE" style="width: 100%">
                <option></option>
                @foreach ($focalizados as $focalizado)
                    <option value="{{ $focalizado->id }}">{{ $focalizado->nombre }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md">
            <label class="required fw-semibold fs-6 mb-2">T. Tela</label>
            <select name="tipo_tela_id" id="tipo_tela_id" data-placeholder="SELECIONE"
                required style="width: 100%">
                <option></option>
                @foreach ($tipoTelas as $tipoTela)
                    <option value="{{ $tipoTela->id }}">{{ $tipoTela->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md">
            <label class="required fw-semibold fs-6 mb-2">Co. Tela</label>
            <select name="color_tela_id" id="color_tela_id" data-placeholder="SELECIONE"
                required style="width: 100%">
                <option></option>
                @foreach ($colorTelas as $colorTela)
                    <option value="{{ $colorTela->id }}">{{ $colorTela->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md">
            <label class="fw-semibold fs-6 mb-2">Ca. Tela</label>
            <select name="caracteristica_tela_id" id="caracteristica_tela_id" data-placeholder="SELECIONE" style="width: 100%">
                <option></option>
                @foreach ($caracteristicaTelas as $caracteristicaTela)
                    <option value="{{ $caracteristicaTela->id }}">
                        {{ $caracteristicaTela->nombre }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md">
            <label class="fw-semibold fs-6 mb-2">Peso</label>
            <input type="number" id="peso" name="peso" min="0" step="0.01" style="width: 100%">
        </div>
        <div class="col-md">
            <label class="required fw-semibold fs-6 mb-2">Precio</label>
            <input type="number" id="precio_venta" name="precio_venta" required min="0.01" step="0.01" value="0" onkeyup="calcularsubTotal()" style="width: 100%">
        </div>
        <div class="col-md">
            <label class="required fw-semibold fs-6 mb-2">S. Total</label>
            <input type="number" id="sub_total" name="sub_total" required min="0.01" step="0.01"  style="width: 100%">
        </div>
        <div class="col-md">
            <label class="fw-semibold fs-6 mb-2">Obs</label>
            <input type="text" id="observacion" name="observacion" style="width: 100%">
        </div>
        <div class="col-md">
            <label class="required fw-semibold fs-6 mb-2">N° OT</label>
            <input type="number" id="nro_ot" name="nro_ot" required min="1"
                style="width: 100%">
        </div>
        <div class="col-md-1 d-flex align-items-end">
            <div class="d-flex justify-content-center gap-2 w-100">
                <button class="btn btn-success btn-circle btn-sm btn-icon" type="button"
                    onclick="agregarProducto()" title="Agregar al Carro de compras"
                    id="boton-agrega-producto">
                    <i class="fa fa-xs fa-shopping-cart"></i> +
                </button>
            </div>
        </div>
    </div>
    <div class="row" style="display: none" id="bloque-ojales">
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">N Ojales</label>
            <input type="number" id="nro_ojales" name="nro_ojales" required min="1"
                style="width: 100%" readonly>
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Precio</label>
            <input type="number" id="precio_ojales" name="precio_ojales" required min="0.1"
                style="width: 100%" value="0.33" step="0.01" onchange="recalularPrecioOjales()">
        </div>
        <div class="col-md-4">
            <label class="required fw-semibold fs-6 mb-2">Total</label>
            <input type="number" id="total_ojales" name="total_ojales" required min="1"
                style="width: 100%" readonly step="0.01">
        </div>
    </div>
</form>

<hr>
<div class="row">
    <div class="col-md-12">
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
                @php
                    $totalOrdenTrabajo = 0;
                @endphp
                @forelse ( $ordenesTrabajos as $ordenTrabajo)
                    @php
                        $totalOrdenTrabajo += $ordenTrabajo->subtotal;
                    @endphp
                    <tr>
                        <td>
                            <input type="number" min="0.01" step="0.01" value="{{ $ordenTrabajo->cantidad }}" style="width: 100%" onchange="cambiarDato('CANTIDAD', {{ $ordenTrabajo->id }}, this)">
                            <small style="display: none;" class="text-success" id="CANTIDAD_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <select name="" id="" style="width: 100%;" onchange="cambiarDato('PRENDA', {{ $ordenTrabajo->id }}, this)">
                                <option value=""></option>
                                @foreach ($prendas as $prenda)
                                    <option {{ $ordenTrabajo->prenda_id == $prenda->id? 'selected' : '' }} value="{{ $prenda->id }}">{{ $prenda->nombre }}</option>
                                @endforeach
                            </select>
                            <small style="display: none;" class="text-success" id="PRENDA_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <input type="number" min="1" value="{{ $ordenTrabajo->numero_ojales }}" style="width: 100%" onchange="cambiarDato('OJAL', {{ $ordenTrabajo->id }}, this)" autocomplete="off">
                            <small style="display: none;" class="text-success" id="OJAL_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <select name="" id="" style="width: 100%;" onchange="cambiarDato('TELA', {{ $ordenTrabajo->id }}, this)">
                                <option value=""></option>
                                @foreach ($telas as $tela)
                                    <option {{ $ordenTrabajo->tela_id == $tela->id? 'selected' : '' }} value="{{ $tela->id }}">{{ $tela->nombre }}</option>
                                @endforeach
                            </select>
                            <small style="display: none;" class="text-success" id="TELA_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <select name="" id="" style="width: 100%;" onchange="cambiarDato('PRE_LAVADO', {{ $ordenTrabajo->id }}, this)">
                                <option value=""></option>
                                @foreach ($prelavados as $prelavado)
                                    <option {{ $ordenTrabajo->prelavado_id == $prelavado->id? 'selected' : '' }} value="{{ $prelavado->id }}">{{ $prelavado->nombre }}</option>
                                @endforeach
                            </select>
                            <small style="display: none;" class="text-success" id="PRE_LAVADO_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <select name="" id="" style="width: 100%;" onchange="cambiarDato('NEVADO', {{ $ordenTrabajo->id }}, this)">
                                <option value=""></option>
                                @foreach ($nevados as $nevado)
                                    <option {{ $ordenTrabajo->nevado_id == $nevado->id? 'selected' : '' }} value="{{ $nevado->id }}">{{ $nevado->nombre }}</option>
                                @endforeach
                            </select>
                            <small style="display: none;" class="text-success" id="NEVADO_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <select name="" id="" style="width: 100%;" onchange="cambiarDato('FOCALIZADO', {{ $ordenTrabajo->id }}, this)">
                                <option value=""></option>
                                @foreach ($focalizados as $focalizado)
                                    <option {{ $ordenTrabajo->focalizado_id == $focalizado->id? 'selected' : '' }} value="{{ $focalizado->id }}">{{ $focalizado->nombre }}</option>
                                @endforeach
                            </select>
                            <small style="display: none;" class="text-success" id="FOCALIZADO_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <select name="" id="" style="width: 100%;" onchange="cambiarDato('TIPO_TELA', {{ $ordenTrabajo->id }}, this)">
                                <option value=""></option>
                                @foreach ($tipoTelas as $tipoTela)
                                    <option {{ $ordenTrabajo->tipo_tela_id == $tipoTela->id? 'selected' : '' }} value="{{ $tipoTela->id }}">{{ $tipoTela->nombre }}</option>
                                @endforeach
                            </select>
                            <small style="display: none;" class="text-success" id="TIPO_TELA_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <select name="" id="" style="width: 100%;" onchange="cambiarDato('COLOR_TELA', {{ $ordenTrabajo->id }}, this)">
                                <option value=""></option>
                                @foreach ($colorTelas as $coloTela)
                                    <option {{ $ordenTrabajo->color_tela_id == $coloTela->id? 'selected' : '' }} value="{{ $coloTela->id }}">{{ $coloTela->nombre }}</option>
                                @endforeach
                            </select>
                            <small style="display: none;" class="text-success" id="COLOR_TELA_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <select name="" id="" style="width: 100%;" onchange="cambiarDato('CARACTERISTICA_TELA', {{ $ordenTrabajo->id }}, this)">
                                <option value=""></option>
                                @foreach ($caracteristicaTelas as $caracteristicaTela)
                                    <option {{ $ordenTrabajo->color_tela_id == $caracteristicaTela->id? 'selected' : '' }} value="{{ $caracteristicaTela->id }}">{{ $caracteristicaTela->nombre }}</option>
                                @endforeach
                            </select>
                            <small style="display: none;" class="text-success" id="CARACTERISTICA_TELA_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <input type="number" min="0.01" step="0.01" value="{{ $ordenTrabajo->peso }}" style="width: 100%" onchange="cambiarDato('PESO', {{ $ordenTrabajo->id }}, this)">
                            <small style="display: none;" class="text-success" id="PESO_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <input type="number" min="0.01" step="0.01" value="{{ $ordenTrabajo->precio }}" style="width: 100%" onchange="cambiarDato('PRECIO', {{ $ordenTrabajo->id }}, this)">
                            <small style="display: none;" class="text-success" id="PRECIO_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <input type="number" min="0.01" step="0.01" value="{{ $ordenTrabajo->subtotal }}" style="width: 100%" readonly>
                        </td>
                        <td>
                            <input type="text" min="0.01" step="0.01" value="{{ $ordenTrabajo->observacion }}" style="width: 100%" onchange="cambiarDato('OBSERVACIONES', {{ $ordenTrabajo->id }}, this)">
                            <small style="display: none;" class="text-success" id="OBSERVACIONES_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                        <td>
                            <input type="number" min="0.01" step="0.01" value="{{ $ordenTrabajo->nro_ot }}" style="width: 100%" onchange="cambiarDato('NRO_OT', {{ $ordenTrabajo->id }}, this)">
                            <small style="display: none;" class="text-success" id="NRO_OT_{{ $ordenTrabajo->id }}">Guardado con exito.</small>
                        </td>
                    </tr>
                @empty
                    <h4 class="text-danger">No hay datos</h4>
                @endforelse
            </tbody>
            <thead>
                <tr style="background-color: #c2c2c2">
                    <td colspan="10"><b>TOTAL ORDEN TRABAJO</b></td>
                    <td colspan="5"><input style="width: 100%"  type="text" readonly value="{{ $totalOrdenTrabajo }}"></td>
                </tr>
            </thead>
        </table>
    </div>
</div>
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
