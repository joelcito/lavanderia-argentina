@extends('layouts.app')
@section('css')
<link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
    .tamanio_boton {
        font-size: 6px;
    }
</style>
@endsection
@section('metadatos')
<meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')

<!--begin::Modal - Add task-->
<div class="modal fade" id="modalCotizacion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h3 class="fw-bold">FORMULARIO DE COTIZACION</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body scroll-y">
                <form id="formularioCotizacion">
                    <input type="hidden" id="cotizacion_id" name="cotizacion_id">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Cedula</label>
                                <input type="number" class="form-control form-control-sm" id="cedula" name="cedula"
                                    onchange="buscarCliente()" required>
                                <input type="hidden" id="cliente_id" name="cliente_id">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Nombre</label>
                                <input type="text" class="form-control form-control-sm" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Ap Paterno</label>
                                <input type="text" class="form-control form-control-sm" id="ap_paterno"
                                    name="ap_paterno">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Ap Materno</label>
                                <input type="text" class="form-control form-control-sm" id="ap_materno"
                                    name="ap_materno">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Cantidad Prendas</label>
                                <input type="text" class="form-control form-control-sm" id="cantidad_prenda"
                                    name="cantidad_prenda" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Peso Kg</label>
                                <input type="text" class="form-control form-control-sm" id="peso_kg" name="peso_kg"
                                    onchange="calcularPesoGramo()" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Peso Gr</label>
                                <input type="text" class="form-control form-control-sm" id="peso_gr" name="peso_gr"
                                    readonly required>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Prelavado</label>
                                <select class="form-control form-control-sm" name="prelavado_id" id="prelavado_id" required>
                                    @foreach ( $prelavados as $prelavado)
                                    <option value="{{$prelavado->id}}">{{$prelavado->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Nevado</label>
                                <select class="form-control form-control-sm" name="nevado_id" id="nevado_id" required>
                                    @foreach ( $nevados as $nevado)
                                    <option value="{{$nevado->id}}">{{$nevado->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Focalizado</label>
                                <select class="form-control form-control-sm" name="focalizado_id" id="focalizado_id" required>
                                    @foreach ($focalizados as $focalizado)
                                    <option value="{{$focalizado->id}}">{{$focalizado->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Tipo Tela</label>
                                <select class="form-control form-control-sm" name="tipo_tela_id" id="tipo_tela_id" required>
                                    @foreach ($tipoTelas as $tipoTela)
                                    <option value="{{$tipoTela->id}}">{{$tipoTela->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Color Tela</label>
                                <select class="form-control form-control-sm" name="color_tela_id" id="color_tela_id" required>
                                    @foreach ($colorTelas as $colorTela)
                                    <option value="{{$colorTela->id}}">{{$colorTela->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Tipo Prenda</label>
                                <select class="form-control form-control-sm" name="tipo_prenda_id" id="tipo_prenda_id" required>
                                    @foreach ($tipoPrendas as $tipoPrenda)
                                    <option value="{{$tipoPrenda->id}}">{{$tipoPrenda->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Descripcion</label>
                                <input type="text" class="form-control form-control-sm" id="descripcion" name="descripcion">
                            </div>
                        </div>
                    </div>
                    <hr>

                    <!--begin::Repeater-->
                    <div id="kt_docs_repeater_nested">

                        <div class="form-group">
                            <div data-repeater-list="procesos">

                                <div data-repeater-item>

                                    <div class="row mb-5">

                                        <div class="col-md-3">
                                            <label class="form-label">Proceso:</label>
                                            <select name="proceso_id" class="form-control form-control-sm">
                                                @foreach ($tipoProcesos as $tipoProceso)
                                                <option value="{{ $tipoProceso->id }}">
                                                    {{ $tipoProceso->nombre }}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-7">

                                            <div class="inner-repeater">

                                                <div data-repeater-list="productos" class="mb-5">

                                                    <div data-repeater-item>

                                                        <div class="row align-items-end mb-3">

                                                            <div class="col-md-4">
                                                                <label class="form-label">Producto</label>
                                                                <select name="producto_id"
                                                                    class="form-control form-control-sm producto">
                                                                    <option value="">Seleccione</option>
                                                                    @foreach ($productos as $producto)
                                                                    <option value="{{ $producto->id }}"
                                                                        data-producto='@json($producto)'
                                                                        data-ingreso='@json($producto->ultimoIngreso)'>
                                                                        {{ $producto->nombre.(($producto->ultimoIngreso)
                                                                        ? ' |
                                                                        '.$producto->ultimoIngreso?->precio_compra_g :
                                                                        '') }}
                                                                    </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="form-label">Porcentaje</label>
                                                                <input type="number" name="porcentaje"
                                                                    class="form-control form-control-sm porcentaje"
                                                                    min="0" step="0.01">
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="form-label">Cantidad</label>
                                                                <input type="number" name="cantidad"
                                                                    class="form-control form-control-sm cantidad"
                                                                    min="0" step="0.01">
                                                            </div>

                                                            <div class="col-md-2">
                                                                <label class="form-label">Total</label>
                                                                <input type="number" name="total"
                                                                    class="form-control form-control-sm total" min="0"
                                                                    step="0.01">
                                                            </div>

                                                            <div class="col-md-2">
                                                                <button
                                                                    class="border border-secondary btn btn-icon btn-flex btn-light-danger"
                                                                    data-repeater-delete type="button">
                                                                    <i class="ki-duotone ki-trash fs-5"><span
                                                                            class="path1"></span><span
                                                                            class="path2"></span><span
                                                                            class="path3"></span><span
                                                                            class="path4"></span><span
                                                                            class="path5"></span></i>
                                                                </button>
                                                            </div>

                                                        </div>

                                                    </div>

                                                </div>

                                                <button type="button" data-repeater-create
                                                    class="btn btn-sm btn-flex btn-light-primary btn-sm">
                                                    <i class="ki-duotone ki-plus fs-5"></i>
                                                    Agregar Producto
                                                </button>

                                            </div>

                                        </div>

                                        <div class="col-md-2">
                                            <button type="button" data-repeater-delete
                                                class="btn btn-sm btn-flex btn-light-danger mt-8">
                                                <i class="ki-duotone ki-trash fs-5"></i>
                                                Eliminar Proceso
                                            </button>
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                        <div class="form-group">
                            <button type="button" data-repeater-create class="btn btn-flex btn-light-primary">
                                <i class="ki-duotone ki-plus fs-3"></i>
                                Agregar Proceso
                            </button>
                        </div>

                    </div>
                    <!--end::Repeater-->
                    <hr>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Proceso</label>
                                <input type="text" class="form-control form-control-sm" id="proceso_focalizado"
                                    name="proceso_focalizado" value="FOCALIZADO" readonly>
                                <input type="hidden" id="proceso_id_focalizado" name="proceso_id_focalizado" value="4" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Precio</label>
                                <input type="text" class="form-control form-control-sm" id="precio_focalizado"
                                    name="precio_focalizado" value="0"
                                    onchange="actualizarPRecioIndividual('precio_focalizado', 'total_focalizado')">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Total</label>
                                <input type="text" class="form-control form-control-sm" id="total_focalizado"
                                    name="total_focalizado" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Proceso</label>
                                <input type="text" class="form-control form-control-sm" id="proceso_planchado"
                                    name="proceso_planchado" value="PLANCHADO" readonly>
                                <input type="hidden" id="proceso_id_planchado" name="proceso_id_planchado" value="20" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Precio</label>
                                <input type="text" class="form-control form-control-sm" id="precio_planchado"
                                    name="precio_planchado" value="0"
                                    onchange="actualizarPRecioIndividual('precio_planchado', 'total_planchado')">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Total</label>
                                <input type="text" class="form-control form-control-sm" id="total_planchado"
                                    name="total_planchado" value="0">
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Mano Obra (bs/Unid)</label>
                                <input type="text" class="form-control form-control-sm" id="mano_obra" name="mano_obra"
                                    value="0" required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Servicio Basico (bs/Unid)</label>
                                <input type="text" class="form-control form-control-sm" id="servicio_basico"
                                    name="servicio_basico" value="0"required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Mantenimiento (bs/Unid)</label>
                                <input type="text" class="form-control form-control-sm" id="mantenimiento"
                                    name="mantenimiento" value="0"required>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Interes Bancario (bs/Unid)</label>
                                <input type="text" class="form-control form-control-sm" id="interes_bancario"
                                    name="interes_bancario" value="0"required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">% Ganancia</label>
                                        <input type="text" class="form-control form-control-sm" id="porc_gananci"
                                            name="porc_gananci" value="0"required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Prec Ven Pronosticado</label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="precio_ven_pronosticado" name="precio_ven_pronosticado" value="0"required>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="fv-row mb-7">
                                        <label class="required fw-semibold fs-6 mb-2">Prec Ven Pronos S3</label>
                                        <input type="text" class="form-control form-control-sm"
                                            id="precio_venta_prosnosticado_s3" name="precio_venta_prosnosticado_s3"
                                            value="0"required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-info w-100 btn-sm" type="button"
                                onclick="calcularCostos()">Calcular</button>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="fv-row mb-7 mt-8">
                                <input type="text" class="form-control form-control-sm" value="COSTO" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Simulacion 1</label>
                                <input type="text" class="form-control form-control-sm" id="costo_frost"
                                    name="costo_frost" value="0" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Simulacion 2</label>
                                <input type="text" class="form-control form-control-sm" id="costo_frost_foc"
                                    name="costo_frost_foc" value="0" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Simulacion 3</label>
                                <input type="text" class="form-control form-control-sm" id="costo_frost_foc_cont"
                                    name="costo_frost_foc_cont" value="0" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" value="PRECIO" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                {{-- <label class="required fw-semibold fs-6 mb-2">Frost</label> --}}
                                <input type="text" class="form-control form-control-sm" id="precio_frost"
                                    name="precio_frost" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                {{-- <label class="required fw-semibold fs-6 mb-2">Frost + Foc</label> --}}
                                <input type="text" class="form-control form-control-sm" id="precio_frost_foc"
                                    name="precio_frost_foc" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                {{-- <label class="required fw-semibold fs-6 mb-2">Frost + Foc + Cont</label> --}}
                                <input type="text" class="form-control form-control-sm" id="precio_frost_foc_cont"
                                    name="precio_frost_foc_cont" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" value="UTILIDAD" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" id="utilidad_frost"
                                    name="utilidad_frost" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" id="utilidad_frost_foc"
                                    name="utilidad_frost_foc" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" id="utilidad_frost_foc_cont"
                                    name="utilidad_frost_foc_cont" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" value="% GANACIA" readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" id="porcentaje_ganancia_s1"
                                    name="porcentaje_ganancia_s1" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" id="porcentaje_ganancia_s2"
                                    name="porcentaje_ganancia_s2" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" id="porcentaje_ganancia_s3"
                                    name="porcentaje_ganancia_s3" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" value="UTILIDAD PRONOSTICADA"
                                    readonly>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" id="utilidad_pronosticada_s1"
                                    name="utilidad_pronosticada_s1" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" id="utilidad_pronosticada_s2"
                                    name="utilidad_pronosticada_s2" value="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="fv-row mb-7">
                                <input type="text" class="form-control form-control-sm" id="utilidad_pronosticada_s3"
                                    name="utilidad_pronosticada_s3" value="0">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-success w-100 btn-sm" type="button"
                                onclick="guardar()">Guardar</button>
                        </div>
                    </div>
                </form>
            </div>
            <!--end::Modal body-->
        </div>
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Add task-->


<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxlg">
            <div class="card shadow-sm">
                <div class="card-header bg-light-info py-4 d-flex align-items-center justify-content-between">
                    <h3 class="card-title fw-bold">Listado de Cotizaciones</h3>
                    <div class="card-toolbar">
                        <button type="button" class="btn btn-primary btn-sm" onclick="modalNuevoCotizacion()">
                            <i class="fa fa-plus"></i> Nuevo Cotizacion
                        </button>
                    </div>
                </div>

                <div class="card-body py-4" id="table_listado">
                    <!-- El listado se carga por AJAX -->
                </div>
            </div>
        </div>
    </div>
</div>


@stop()

@section('js')
<script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
<script>
    $.ajaxSetup({
            // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $(document).ready(function() {
            ajaxListado();

            $('#kt_docs_repeater_nested').repeater({
                repeaters: [{
                    selector: '.inner-repeater',
                    show: function () {
                        $(this).slideDown();
                    },

                    hide: function (deleteElement) {
                        $(this).slideUp(deleteElement);
                    }
                }],

                show: function () {
                    $(this).slideDown();
                },

                hide: function (deleteElement) {
                    $(this).slideUp(deleteElement);
                }
            });

            // $(document).on('input', '.porcentaje', function () {

            //     let fila = $(this).closest('[data-repeater-item]');
            //     let peso_gr = $('#peso_gr').val();

            //     let porcentaje = parseFloat($(this).val()) || 0;

            //     console.log("########################");
            //     console.log(fila.find('.producto option:selected').attr('data-producto'));

            //     let producto = JSON.parse(fila.find('.producto option:selected').attr('data-producto'));

            //     console.log(producto);

            //     if (!producto) {
            //         return;
            //     }

            //     // let cantidad = (pesoBase * porcentaje) / 100;
            //     let cantidad = (peso_gr * porcentaje) / 100;

            //     // fila.find('.cantidad').val(cantidad.toFixed(2));
            //     fila.find('.cantidad').val(cantidad.toFixed(2));

            //     calcularTotal(fila);

            // });

            $(document).on('input', '.porcentaje', function () {

            let fila = $(this).closest('[data-repeater-item]');
            let peso_gr = parseFloat($('#peso_gr').val()) || 0;

            let porcentaje = parseFloat($(this).val()) || 0;

            let dataProducto = fila.find('.producto option:selected').attr('data-producto');

            console.log("DATA PRODUCTO:", dataProducto);

            if (!dataProducto) {
            // console.log("No hay producto seleccionado");
            Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'warning',
            title: 'No hay producto seleccionado.',
            text: 'No hay producto seleccionado.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
            });
            fila.find('.cantidad').val(0);
            fila.find('.total').val(0);
            return;
            }

            let producto = JSON.parse(dataProducto);

            console.log("PRODUCTO:", producto);

            if (!producto) {
            return;
            }

            let cantidad = (peso_gr * porcentaje) / 100;

            fila.find('.cantidad').val(cantidad.toFixed(2));

            calcularTotal(fila);

            });

            $(document).on('input', '.cantidad', function () {

                let fila = $(this).closest('[data-repeater-item]');
                let peso_gr = parseFloat($('#peso_gr').val()) || 0;

                let cantidad = parseFloat($(this).val()) || 0;

                let producto = JSON.parse(fila.find('.producto option:selected').attr('data-producto'));

                if (!producto || peso_gr === 0) {
                return;
                }

                // porcentaje correcto
                let porcentaje = (cantidad / peso_gr) * 100;

                console.log(porcentaje, peso_gr, cantidad);

                fila.find('.porcentaje').val(porcentaje.toFixed(2));

                calcularTotal(fila);

            });

            $(document).on('change', '.producto', function () {

                let fila = $(this).closest('[data-repeater-item]');

                fila.find('.porcentaje').trigger('input');

            });

        });

        // function calcularTotal(fila) {
        //     let ingreso = JSON.parse(fila.find('.producto option:selected').attr('data-ingreso'));
        //     let cantidad = parseFloat(fila.find('.cantidad').val()) || 0;
        //     let precioConvertido = ingreso.precio_compra_g;
        //     let total = precioConvertido * cantidad;
        //     fila.find('.total').val(total.toFixed(2));
        // }

        function calcularTotal(fila) {

            let dataIngreso = fila.find('.producto option:selected').attr('data-ingreso');

            if (!dataIngreso) {
            fila.find('.total').val(0);
            return;
            }

            let ingreso = JSON.parse(dataIngreso);

            if(ingreso != null){
                let cantidad = parseFloat(fila.find('.cantidad').val()) || 0;
                let precioConvertido = parseFloat(ingreso.precio_compra_g) || 0;
                let total = precioConvertido * cantidad;
                fila.find('.total').val(total.toFixed(2));
            }else{
                Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'warning',
                title: 'El producto no tiene precio registrado',
                text: 'Seleccione otro producto o registre su ingreso.',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
                });
                fila.find('.total').val(0);
            }
        }

        // function calcularTotal(fila) {

        // let dataIngreso = fila.find('.producto option:selected').attr('data-ingreso');

        // if (!dataIngreso) {
        // fila.find('.total').val(0);

        // Swal.fire({
        // toast: true,
        // position: 'top-end',
        // icon: 'warning',
        // title: 'El producto no tiene precio registrado',
        // text: 'Seleccione otro producto o registre su ingreso.',
        // showConfirmButton: false,
        // timer: 3000,
        // timerProgressBar: true
        // });

        // return;
        // }

        // let ingreso = JSON.parse(dataIngreso);

        // let cantidad = parseFloat(fila.find('.cantidad').val()) || 0;
        // let precioConvertido = parseFloat(ingreso.precio_compra_g) || 0;

        // let total = precioConvertido * cantidad;

        // fila.find('.total').val(total.toFixed(2));
        // }

        function ajaxListado(){
            let datos = {};
            $.ajax({
                url: "{{ route('cotizacion.ajaxListado') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {

                    if (resultado.estado) {
                        $('#table_listado').html(resultado.data.listado)
                    } else {

                    }
                    // Ocultar SweetAlert2 cuando la solicitud sea exitosa
                    // Swal.close();
                }
            })
        }

        function modalNuevoCotizacion(){
            $('#cotizacion_id').val(0);
            $('#cliente_id').val(0);
            $('#cedula').val(null);
            $('#nombre').val(null);
            $('#ap_paterno').val(null);
            $('#ap_materno').val(null);

            $('#cantidad_prenda').val(null);
            $('#peso_kg').val(null);
            $('#peso_gr').val(null);
            $('#prelavado_id').val(null);
            $('#nevado_id').val(null);
            $('#focalizado_id').val(null);

            // Valores por defecto
            $('#precio_focalizado').val(0);
            $('#total_focalizado').val(0);

            $('#precio_planchado').val(0);
            $('#total_planchado').val(0);

            $('#mano_obra').val(0);
            $('#servicio_basico').val(0);
            $('#mantenimiento').val(0);
            $('#interes_bancario').val(0);

            $('#porc_gananci').val(0);

            $('#precio_ven_pronosticado').val(0);
            $('#precio_venta_prosnosticado_s3').val(0);

            $('#precio_venta_prosnosticado_s3').val(0);

            $('#costo_frost').val(0);
            $('#costo_frost_foc').val(0);
            $('#costo_frost_foc_cont').val(0);
            $('#precio_frost').val(0);
            $('#precio_frost_foc').val(0);
            $('#precio_frost_foc_cont').val(0);
            $('#utilidad_frost').val(0);
            $('#utilidad_frost_foc').val(0);
            $('#utilidad_frost_foc_cont').val(0);
            $('#porcentaje_ganancia_s1').val(0);
            $('#porcentaje_ganancia_s2').val(0);
            $('#porcentaje_ganancia_s3').val(0);
            $('#utilidad_pronosticada_s1').val(0);
            $('#utilidad_pronosticada_s2').val(0);
            $('#utilidad_pronosticada_s3').val(0);

            // Limpiar repeater
            $('[data-repeater-list="procesos"]').empty();

            // Crear una fila inicial del repeater si necesitas una por defecto
            $('#kt_docs_repeater_nested > .form-group:last [data-repeater-create]').click();

            $('#modalCotizacion').modal('show')
        }

        function buscarCliente(){
            var c = $('#cedula').val();
            $.ajax({
                url: "{{ route('cotizacion.buscarCliente') }}",
                method: "POST",
                data: {
                    cedula:c
                },
                success: function(resultado) {
                    console.log(resultado);
                    if (resultado.estado) {
                        $('#cliente_id').val(resultado.data.cliente.id);
                        $('#nombre').val(resultado.data.cliente.nombres);
                        $('#ap_paterno').val(resultado.data.cliente.ap_paterno);
                        $('#ap_materno').val(resultado.data.cliente.ap_materno);
                    } else {
                        $('#cliente_id').val(0);
                        $('#nombre').val(null);
                        $('#ap_paterno').val(null);
                        $('#ap_materno').val(null);
                    }
                },
                error: function(xhr) {
                    limpiarErorres();

                    if (xhr.status === 422) {
                        let errores = xhr.responseJSON.errors;

                        for (let campo in errores) {
                            let mensaje = errores[campo][0];

                            let input = $(`[name="${campo}"]`);
                            input.addClass("is-invalid");
                            input.after(`<div class="invalid-feedback">${mensaje}</div>`);
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error inesperado.',
                        });
                    }
                }
            });
        }

        function calcularPesoGramo(){
            var pkg = $('#peso_kg').val();
            var g = pkg * 1000 ;
            $('#peso_gr').val(g)
        }

        function calcularCostos() {

            let totalGeneral = 0;

            // ===================== REPEATER =====================
            $('[data-repeater-list="procesos"] > [data-repeater-item]').each(function () {

                $(this)
                    .find('[data-repeater-list="productos"] > [data-repeater-item]')
                    .each(function () {

                        let fila = $(this);

                        // let porcentaje = parseFloat(fila.find('.porcentaje').val()) || 0;
                        // let cantidad   = parseFloat(fila.find('.cantidad').val()) || 0;
                        let total = parseFloat(fila.find('.total').val()) || 0;

                        // let total = porcentaje * cantidad;

                        // fila.find('.total').val(total.toFixed(2));

                        totalGeneral += total;
                    });

            });

            // ===================== INPUTS BASE =====================
            let cantidadPrendas = parseFloat($('#cantidad_prenda').val()) || 0;

            let manoObra        = parseFloat($('#mano_obra').val()) || 0;
            let servicioBasico  = parseFloat($('#servicio_basico').val()) || 0;
            let mantenimiento   = parseFloat($('#mantenimiento').val()) || 0;
            let interesBancario = parseFloat($('#interes_bancario').val()) || 0;

            let totalPlanchado     = parseFloat($('#total_planchado').val()) || 0;
            let totalFocalizado    = parseFloat($('#total_focalizado').val()) || 0;
            let procentajeGanancia = parseFloat($('#porc_gananci').val()) || 0;

            let precioVentaPronosticadoS2 = parseFloat($('#precio_ven_pronosticado').val()) || 0;
            let precioVentaPronosticadoS3 = parseFloat($('#precio_venta_prosnosticado_s3').val()) || 0;


            let procentajeGananciaConvertido = procentajeGanancia / 100;

            // ===================== VALIDACIÓN BASE =====================
            if (cantidadPrendas <= 0) {
                alert("La cantidad de prendas debe ser mayor a 0");
                return;
            }

            // ===================== COSTO FROSTEADO =====================
            let totalPlanchadoFocalizado = totalPlanchado + totalFocalizado;
            let costoBase = (totalGeneral + totalPlanchadoFocalizado)/ cantidadPrendas;

            console.log(costoBase, totalGeneral, cantidadPrendas, totalPlanchadoFocalizado);

            let totalMasFrosteado =
                costoBase +
                manoObra +
                servicioBasico +
                mantenimiento +
                interesBancario;

            // // ===================== DATOS PARA SIMULACION 1 =====================
            $('#costo_frost').val(totalMasFrosteado.toFixed(2));
            let pibotePrecio = totalMasFrosteado / ( 1 - procentajeGananciaConvertido);
            $('#precio_frost').val(pibotePrecio.toFixed(2));
            let utilidadS1 = pibotePrecio - totalMasFrosteado;
            $('#utilidad_frost').val(utilidadS1.toFixed(2));

            let procentajeGanaciaResS1 = (pibotePrecio - totalMasFrosteado) / pibotePrecio;
            $('#porcentaje_ganancia_s1').val(procentajeGanaciaResS1.toFixed(2));

            let utilidadPronosticadaS1 = pibotePrecio - totalMasFrosteado;
            $('#utilidad_pronosticada_s1').val(utilidadPronosticadaS1.toFixed(2));

            // // ===================== DATOS PARA SIMULACION 2 =====================
            $('#costo_frost_foc').val(totalMasFrosteado.toFixed(2));
            if(precioVentaPronosticadoS2 > 0){
                $('#precio_frost_foc').val(precioVentaPronosticadoS2.toFixed(2));
                let utilidadS2 = precioVentaPronosticadoS2 - totalMasFrosteado ;
                $('#utilidad_frost_foc').val(utilidadS2.toFixed(2));

                let procentajeGanaciaResS2 = (precioVentaPronosticadoS2 - totalMasFrosteado) / precioVentaPronosticadoS2;
                $('#porcentaje_ganancia_s2').val(procentajeGanaciaResS2.toFixed(2));

                let utilidadPronosticadaS2 = precioVentaPronosticadoS2 - totalMasFrosteado;
                $('#utilidad_pronosticada_s2').val(utilidadPronosticadaS2.toFixed(2));
            }else{
                $('#precio_frost_foc').val(0);
                $('#utilidad_frost_foc').val(0);
                $('#porcentaje_ganancia_s2').val(0);
                $('#utilidad_pronosticada_s2').val(0);
            }


            // // ===================== DATOS PARA SIMULACION 3 =====================
            $('#costo_frost_foc_cont').val(totalMasFrosteado.toFixed(2));
            if(precioVentaPronosticadoS3 > 0){
                $('#precio_frost_foc_cont').val(precioVentaPronosticadoS3.toFixed(2));
                let utilidadS2 = precioVentaPronosticadoS3 - totalMasFrosteado ;
                $('#utilidad_frost_foc_cont').val(utilidadS2.toFixed(2));

                let procentajeGanaciaResS3 = (precioVentaPronosticadoS3 - totalMasFrosteado) / precioVentaPronosticadoS3;
                $('#porcentaje_ganancia_s3').val(procentajeGanaciaResS3.toFixed(2));

                let utilidadPronosticadaS3 = precioVentaPronosticadoS3 - totalMasFrosteado;
                $('#utilidad_pronosticada_s3').val(utilidadPronosticadaS3.toFixed(2));
            }else{
                $('#precio_frost_foc_cont').val(0);
                $('#utilidad_frost_foc_cont').val(0);
                $('#porcentaje_ganancia_s3').val(0);
                $('#utilidad_pronosticada_s3').val(0);
            }
        }

        function guardar(){
            if($('#formularioCotizacion')[0].checkValidity()){
                let datos = $('#formularioCotizacion').serializeArray();
                $.ajax({
                    url: "{{ route('cotizacion.guardarCotizacion') }}",
                    method: "POST",
                    data: datos,
                    success: function(resultado) {
                        if (resultado.estado) {
                            Swal.fire({
                                title: "EL REGISTRO FUE EXITOSO.",
                                icon: "success",
                                timer: 3000, // Se cierra en 3 segundos
                                showConfirmButton: false
                            });
                            ajaxListado();
                            $('#modalCotizacion').modal('hide');
                        } else {

                        }
                    },
                    error: function(xhr) {
                        limpiarErorres();
                        if (xhr.status === 422) {
                            let errores = xhr.responseJSON.errors;
                            for (let campo in errores) {
                                let mensaje = errores[campo][0];
                                let input = $(`[name="${campo}"]`);
                                input.addClass("is-invalid");
                                input.after(`<div class="invalid-feedback">${mensaje}</div>`);
                            }
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error inesperado.',
                            });
                        }
                    }
                });
            }else{
                $("#formularioCotizacion")[0].reportValidity();
            }
        }

        function actualizarPRecioIndividual(input, tipo){
            let cantidadPrenda = $('#cantidad_prenda').val();
            let precio = $('#'+input).val();
            let h = cantidadPrenda * precio;
            $('#'+tipo).val(h)
        }

        function reportePdf(id){
            let u = "{{ route('cotizacion.reportePdf', ':id') }}";
            u = u.replace(':id', id);
            window.open(u, '_blank');
        }

        function reporteExcel(id){
            let u = "{{ route('cotizacion.reporteExcel', ':id') }}";
            u = u.replace(':id', id);
            window.location.href = u;
        }

        function editarCotizacion(c){

            console.log(c);


            // Cabecera
            $('#cotizacion_id').val(c.id);
            $('#cliente_id').val(c.cliente_id);
            $('#cedula').val(c.cliente.cedula);
            $('#nombre').val(c.cliente.nombres);
            $('#ap_paterno').val(c.cliente.ap_paterno);
            $('#ap_materno').val(c.cliente.ap_materno);

            $('#cantidad_prenda').val(c.cantidad_prenda);
            $('#peso_kg').val(c.peso_kg);
            $('#peso_gr').val(c.peso_g);

            $('#prelavado_id').val(c.prelavado_id);
            $('#nevado_id').val(c.nevado_id);
            $('#focalizado_id').val(c.focalizado_id);

            $('#mano_obra').val(c.mano_obra);
            $('#servicio_basico').val(c.servicio_basico);
            $('#mantenimiento').val(c.mantenimiento);
            $('#interes_bancario').val(c.interes_bancario);

            $('#porc_gananci').val(c.porcentaje_ganacia);

            $('#precio_ven_pronosticado').val(c.precio_venta_pronosticado);
            $('#precio_venta_prosnosticado_s3').val(c.precio_venta_pronosticado_s3);

            $('#costo_frost').val(c.costo_s1);
            $('#costo_frost_foc').val(c.costo_s2);
            $('#costo_frost_foc_cont').val(c.costo_s3);

            $('#precio_frost').val(c.precio_s1);
            $('#precio_frost_foc').val(c.precio_s2);
            $('#precio_frost_foc_cont').val(c.precio_s3);

            $('#utilidad_frost').val(c.utilidad_s1);
            $('#utilidad_frost_foc').val(c.utilidad_s2);
            $('#utilidad_frost_foc_cont').val(c.utilidad_s3);

            $('#porcentaje_ganancia_s1').val(c.porcentaje_ganancia_s1);
            $('#porcentaje_ganancia_s2').val(c.porcentaje_ganancia_s2);
            $('#porcentaje_ganancia_s3').val(c.porcentaje_ganancia_s3);

            $('#utilidad_pronosticada_s1').val(c.utilidad_pronosticada_s1);
            $('#utilidad_pronosticada_s2').val(c.utilidad_pronosticada_s2);
            $('#utilidad_pronosticada_s3').val(c.utilidad_pronosticada_s3);

            // let procesos = {};

            // // Agrupar
            // c.detalles.forEach(function(d){

            //     if(d.tipo_proceso_id == 4 && d.producto_id == null){
            //         $('#precio_focalizado').val(d.cantidad);
            //         $('#total_focalizado').val(d.total);
            //         return;
            //     }

            //     if(d.tipo_proceso_id == 20 && d.producto_id == null){
            //         $('#precio_planchado').val(d.cantidad);
            //         $('#total_planchado').val(d.total);
            //         return;
            //     }
            //     if(!procesos[d.tipo_proceso_id]){
            //         procesos[d.tipo_proceso_id] = [];
            //     }

            //     console.log("---------------------------------");
            //     console.log(d);
            //     console.log("---------------------------------");

            //     procesos[d.tipo_proceso_id].push(d);

            // });

            // // Limpiar repeater
            // $('[data-repeater-list="procesos"]').empty();


            // // Crear procesos
            // Object.keys(procesos).forEach(function(tipoProceso){

            //     $('#kt_docs_repeater_nested > .form-group:last [data-repeater-create]').click();

            //     let proceso = $('[data-repeater-list="procesos"] > [data-repeater-item]').last();

            //     console.log("$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$");
            //     console.log($('[name="procesos[0]"]'));
            //     console.log("$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$$");

            //     proceso.find('select[name="proceso_id"]')
            //             .val(tipoProceso)
            //             .trigger('change');

            //     // Crear productos
            //     // procesos[tipoProceso].forEach(function(d){

            //     // proceso.find('.inner-repeater').children('[data-repeater-create]').click();

            //     // let producto = proceso
            //     // .find('[data-repeater-list="productos"] > [data-repeater-item]')
            //     // .last();

            //     // producto.find('select[name="producto_id"]')
            //     // .val(d.producto_id)
            //     // .trigger('change');

            //     // producto.find('input[name="porcentaje"]').val(d.porcentaje);
            //     // producto.find('input[name="cantidad"]').val(d.cantidad);
            //     // producto.find('input[name="total"]').val(d.total);

            //     // });

            //     procesos[tipoProceso].forEach(function(d, index){

            //     let producto;

            //     if(index == 0){
            //         // usar el producto que ya viene creado
            //         producto = proceso
            //         .find('[data-repeater-list="productos"] > [data-repeater-item]')
            //         .first();
            //     }else{
            //         // crear los demás
            //         proceso.find('.inner-repeater [data-repeater-create]').click();

            //         producto = proceso
            //         .find('[data-repeater-list="productos"] > [data-repeater-item]')
            //         .last();
            //     }

            //     producto.find('select[name="producto_id"]')
            //             .val(d.producto_id)
            //             .trigger('change');

            //     producto.find('input[name="porcentaje"]').val(d.porcentaje);
            //     producto.find('input[name="cantidad"]').val(d.cantidad);
            //     producto.find('input[name="total"]').val(d.total);

            //     });

            // });

            // $('#modalCotizacion').modal('show');





            // ... [Tu código anterior de la cabecera se mantiene exactamente igual] ...

            let procesos = {};

            // 1. Agrupar filtrando los casos especiales
            c.detalles.forEach(function(d) {
                // Casos especiales fijos que no van al repeater
                if (d.tipo_proceso_id == 4 && d.producto_id == null) {
                $('#precio_focalizado').val(d.cantidad);
                $('#total_focalizado').val(d.total);
                return;
                }

                if (d.tipo_proceso_id == 20 && d.producto_id == null) {
                $('#precio_planchado').val(d.cantidad);
                $('#total_planchado').val(d.total);
                return;
                }

            // Omitir procesos vacíos del JSON que no tienen producto para evitar duplicados basura (ej: tipo_proceso_id: 7 sin producto)
            if (d.producto_id == null) {
            return;
            }

            if (!procesos[d.tipo_proceso_id]) {
            procesos[d.tipo_proceso_id] = [];
            }

            procesos[d.tipo_proceso_id].push(d);
            });

            // 2. Limpiar el repeater principal por completo
            $('[data-repeater-list="procesos"]').empty();

            // 3. Crear y poblar los procesos de forma estructurada
            Object.keys(procesos).forEach(function(tipoProceso) {

            // Forzar el click para crear la fila del proceso padre
            $('#kt_docs_repeater_nested > .form-group:last [data-repeater-create]').click();

            // Obtener la fila recién creada (la última)
            let procesoRow = $('[data-repeater-list="procesos"] > [data-repeater-item]').last();

            // Asignar el tipo de proceso y disparar el change
            procesoRow.find('select[name^="procesos"][name$="[proceso_id]"], select[name="proceso_id"]')
            .val(tipoProceso)
            .trigger('change');

            // IMPORTANTE: Limpiar cualquier fila de producto por defecto que el repeater padre cree automáticamente
            let subRepeaterList = procesoRow.find('[data-repeater-list="productos"]');
            subRepeaterList.empty();

            // 4. Recorrer y crear los sub-productos correspondientes a este proceso
            procesos[tipoProceso].forEach(function(d) {

            // Hacer click en el botón de agregar producto ESPECÍFICO de este proceso
            procesoRow.find('.inner-repeater [data-repeater-create]').click();

            // Obtener el producto recién insertado en este sub-repeater
            let productoRow = subRepeaterList.find('[data-repeater-item]').last();

            // Llenar los campos asegurando selectores relativos precisos
            // productoRow.find('select[name*="[producto_id]"], select[name="producto_id"]')
            // .val(d.producto_id)
            // .trigger('change');

            // productoRow.find('input[name*="[porcentaje]"], input[name="porcentaje"]').val(d.porcentaje);
            // productoRow.find('input[name*="[cantidad]"], input[name="cantidad"]').val(d.cantidad);
            // productoRow.find('input[name*="[total]"], input[name="total"]').val(d.total);

            let productoSelect = productoRow.find('select[name*="[producto_id]"], select[name="producto_id"]');

            productoSelect.val(d.producto_id);

            productoRow.find('input[name*="[porcentaje]"], input[name="porcentaje"]')
            .val(d.porcentaje);

            productoRow.find('input[name*="[cantidad]"], input[name="cantidad"]')
            .val(d.cantidad);

            productoRow.find('input[name*="[total]"], input[name="total"]')
            .val(d.total);
            });
            });

            // Mostrar el modal al final de toda la carga
            $('#modalCotizacion').modal('show');




        }

</script>
@endsection
