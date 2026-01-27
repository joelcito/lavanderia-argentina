<form id="formularioLaser">
    <input type="hidden" name="orden_trabajo_id" id="orden_trabajo_id" value="{{ $ordenTrabajo }}">
    @foreach ( $ordenesTrabajos as $key => $ordenTrabajo)
        <div class="row">
            <div class="col-md-6">
                {{-- <div class="fv-row mb-7"> --}}
                    @if ($key==0)
                        <label class="required fw-semibold fs-6 mb-2">Cantidad Prendas</label>
                    @endif
                    <input type="text" class="form-control form-control-sm" id="numero_prendas_orden_trabajo" name="numero_prendas_orden_trabajo" readonly value="{{ (int) $ordenTrabajo->cantidad." - ".$ordenTrabajo->prenda->nombre }}">
                {{-- </div> --}}
            </div>
            <div class="col-md-6">
                {{-- <div class="fv-row mb-7"> --}}
                    @if ($key==0)
                        <label class="required fw-semibold fs-6 mb-2">Observaciones</label>
                    @endif
                    <input type="text" class="form-control form-control-sm" id="observacion_orden_trabajo" name="observacion_orden_trabajo" readonly value="{{ $ordenTrabajo->observacion }}">
                {{-- </div> --}}
            </div>
        </div>
    @endforeach
    <hr>
    <div class="row">
        <div class="col-md-6">
            <div class="fv-row mb-7">
                <label class="required fw-semibold fs-6 mb-2">Orden de trabajo</label>
                <input type="text" class="form-control form-control-sm" id="numero_orden_trabajo" name="numero_orden_trabajo" readonly value="{{ $nroOt }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="fv-row mb-7 mt-3">
                <button type="button" onclick="agregarFilaLaser()" class="btn btn-success btn-sm btn-circle w-100 mt-5"><i class="fa fa-plus"></i> Agregar Laser</button>
            </div>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-12">
            {{-- <div style="overflow-x: auto;"> --}}
                <!--begin::Table-->
                <table style="width: 100%" id="table_laser">
                    <thead>
                        <tr>
                            <th >Talla</th>
                            <th >Cantidad</th>
                            <th >Intensidad</th>
                            <th >Altura</th>
                            <th >Dpi</th>
                            <th >Pos. 1</th>
                            <th >Pos. 2</th>
                            <th >Pos. 3</th>
                            <th >Pos. 4</th>
                            <th >Pre. x Mesa</th>
                            <th >Seg/Pre</th>
                            <th >Pre Min</th>
                            <th >Sub total</th>
                            <th >Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input name="talla_laser[1]" type="text" style="width: 100%;"></td>
                            <td><input name="cantidad_laser[1]" type="text" style="width: 100%;"></td>
                            <td><input name="intensidad_laser[1]" type="text" style="width: 100%;"></td>
                            <td><input name="altura_laser[1]" type="text" style="width: 100%;"></td>
                            <td><input name="dpi_laser[1]" type="text" style="width: 100%;"></td>
                            <td><input name="pos_1_laser[1]" id="pos_1_laser_1" onkeyup="sumaTimepos(1)" value="0" onclick="this.select()" type="text" style="width: 100%;"></td>
                            <td><input name="pos_2_laser[1]" id="pos_2_laser_1" onkeyup="sumaTimepos(1)" value="0" onclick="this.select()" type="text" style="width: 100%;"></td>
                            <td><input name="pos_3_laser[1]" id="pos_3_laser_1" onkeyup="sumaTimepos(1)" value="0" onclick="this.select()" type="text" style="width: 100%;"></td>
                            <td><input name="pos_4_laser[1]" id="pos_4_laser_1" onkeyup="sumaTimepos(1)" value="0" onclick="this.select()" type="text" style="width: 100%;"></td>
                            <td><input name="prenda_x_mesa_laser[1]" id="prenda_x_mesa_laser_1" onkeyup="sumaTimepos(1)" onclick="this.select()" value="1" type="text" style="width: 100%;"></td>
                            <td><input name="tiempo_total_laser[1]" id="tiempo_total_laser_1" type="text" style="width: 100%;" readonly></td>
                            <td><input class="precioMinutosValor" name="precio_minuto_valor[1]" id="precio_minuto_valor_1" type="text" style="width: 100%" value="6" onchange="calculapreciominutototal(1)"></td>
                            <td><input type="text" style="width: 100%" value="0" name="valor_laser[1]" id="valor_laser_1"></td>
                            <td><button title="Duplicar Debajo" onclick="duplicarDebajo(1)" class="btn btn-success btn-icon btn-sm btn-circle" type="button"><i class="fa fa-plus"></i></button></td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="12"><input type="text" value="6" id="precio_minuto" name="precio_minuto" style="width: 100%;" onchange="cambiarPrecioMinuto()"></td>
                        </tr>
                    </tfoot>
                </table>
                <!--end::Table-->
            {{-- </div> --}}
        </div>
    </div>
</form>
