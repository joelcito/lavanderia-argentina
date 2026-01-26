@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/jquery.orgchart.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <style>
    </style>
@endsection
@section('metadatos')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')

<!--begin::Modal - Add task-->
<div class="modal fade" id="modalAgregarLaser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h3 class="fw-bold">FORMULARIO DE LASER</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body scroll-y">
                <form id="formularioLaser">
                    <input type="hidden" name="orden_trabajo_id" id="orden_trabajo_id" value="0">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Orden de trabajo</label>
                                <input type="text" class="form-control form-control-sm" id="numero_orden_trabajo" name="numero_orden_trabajo" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Cantidad Prendas</label>
                                <input type="text" class="form-control form-control-sm" id="numero_prendas_orden_trabajo" name="numero_prendas_orden_trabajo" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-11">
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Observaciones</label>
                                <input type="text" class="form-control form-control-sm" id="observacion_orden_trabajo" name="observacion_orden_trabajo" readonly>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <div class="fv-row mb-7 mt-3">
                                <button type="button" onclick="agregarFilaLaser()" class="btn btn-success btn-sm btn-circle">Agregar Laser</button>
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
                                            <th >Tie. Total</th>
                                            <th >Pre Min</th>
                                            <th >Precio</th>
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
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm w-100 btn-success" onclick="guardarLaser()">Guardar Laser</button>
                    </div>
                </div>
            </div>
            <!--end::Modal body-->
        </div>
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Add task-->

<!--begin::Modal - Add task-->
<div class="modal fade" id="modalOrdenTrabajo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h3 class="fw-bold">FORMULARIO DE ORDEN DE TRABAJO</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formularioOrdenTrabajo">
                    <div id="formularioAjaxOrdenTrabajo"></div>
                </form>
            </div>
            {{-- <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm w-100 btn-success" onclick="">Guardar</button>
                    </div>
                </div>
            </div> --}}
            <!--end::Modal body-->
        </div>
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Add task-->

<!--begin::Modal - Add task-->
<div class="modal fade" id="modalOrdenTrabajoImpresion" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h3 class="fw-bold">FORMULARIO DE IMPRESION POR ORDEN DE TRABAJO</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formularioOrdenTrabajoSelect">
                    <select class="form-select form-select-sm" name="numero_ot_select" id="numero_ot_select">
                    </select>
                </form>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm w-100 btn-success" onclick="imprimirOrdenTrabajo()">Guardar</button>
                    </div>
                </div>
            </div>
            <!--end::Modal body-->
        </div>
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Add task-->

<!--begin::Modal - Add task-->
<div class="modal fade" id="modalEdicionEstadoOrdenTrabajo" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h5 class="fw-bold">FORMULARIO CAMBIO DE ESTADO DE ORDEN DE TRABAJO <span class="text-info" id="numero_orden_trabajo_text"></span></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formularioCambioEstadoOrdenTrabajo">
                    <select class="form-select form-select-sm" name="estado_orden_trabajo" id="estado_orden_trabajo">
                        <option value="RECEPCIONADO">RECEPCIONADO</option>
                        <option value="TRABAJANDO">TRABAJANDO</option>
                        <option value="EN PROCESO">EN PROCESO</option>
                        <option value="FINALIZADO">FINALIZADO</option>
                        <option value="ENTREGADO">ENTREGADO</option>
                    </select>
                    <input type="hidden" id="factura_id_estado" name="factura_id_estado" value="{{ $factura->id }}">
                    <input type="hidden" id="nro_ot_estado" name="nro_ot_estado" value="{{ $factura->id }}">
                </form>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm w-100 btn-success" onclick="guardarEstadoOrdenTrabajo()">Guardar</button>
                    </div>
                </div>
            </div>
            <!--end::Modal body-->
        </div>
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Add task-->

<!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxlg">
            <!--begin::Card-->
            <div class="card">
                <div class="card-body py-4">
                     <!--begin::Details-->
                    <div class="d-flex mb-9">
                        <!--begin: Pic-->
                        <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
                            @if ($cliente->imagen)
                                <div style="width: 200px; height: 200px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <img width="100%" height="100%" src="{{ asset('storage/imagenesClientes') }}/{{ $cliente->imagen }}" height="110" alt="image">
                                        </div>
                                    </div>
                                </div>
                            @else
                                <img src="{{ asset('assets/img/default.jpg') }}" height="110" alt="image">
                            @endif
                        </div>
                        <!--end::Pic-->
                        <!--begin::Info-->
                        <div class="flex-grow-1" style="margin-left: 10px;">
                            <!--begin::Title-->
                            <div class="d-flex justify-content-between flex-wrap mt-1">
                                <div class="d-flex mr-3">
                                    <h2><span class="text-primary">CLIENTE: </span> {{ $cliente->nombres." ".$cliente->ap_paterno." ".$cliente->ap_materno }}</h2>
                                </div>
                            </div>

                            <hr />
                            <!--end::Title-->
                            <!--begin::Content-->
                            {{-- <div class="row">
                                <div class="col-md-8">
                                    <h6><span class="text-primary">FENOTIPO: </span> </h6>
                                </div>
                            </div>
                            <hr /> --}}

                            <div class="row">
                                <div class="col-md-3">
                                    <h6><span class="text-primary">CELULAR: </span>
                                        {{ $cliente->celular }}
                                    </h6>
                                </div>

                                <div class="col-md-3">
                                    <h6><span class="text-primary">CEDULA: </span>
                                        {{ $cliente->cedula }}
                                    </h6>
                                </div>

                                <div class="col-md-3">
                                    <h6><span class="text-primary">NIT: </span>
                                        {{ $cliente->nit }}
                                    </h6>
                                </div>

                                <div class="col-md-3">
                                    <h6><span class="text-primary">RAZON SOCIAL: </span>
                                        {{ $cliente->razon_social }}
                                    </h6>
                                </div>
                            </div>

                            <hr />

                            <div class="row">
                                <div class="col-md-12">
                                    <h6><span class="text-primary">DIRECCION: </span>
                                        {{ $cliente->direccion }}
                                    </h6>
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-md-2">
                                    <h6><span class="text-primary">REFERENCIA 1: </span>
                                        {{ $cliente->nombre_referencia_1 }}
                                    </h6>
                                </div>

                                <div class="col-md-2">
                                    <h6><span class="text-primary">CELULAR 1: </span>
                                        {{ $cliente->celular_referencia_1 }}
                                    </h6>
                                </div>

                                <div class="col-md-2">
                                    <h6><span class="text-primary">REFERENCIA 2: </span>
                                        {{ $cliente->nombre_referencia_1 }}
                                    </h6>
                                </div>

                                <div class="col-md-2">
                                    <h6><span class="text-primary">CELULAR 2: </span>
                                        {{ $cliente->celular_referencia_1 }}
                                    </h6>
                                </div>

                                <div class="col-md-2">
                                    <h6><span class="text-primary">REFERENCIA 3: </span>
                                        {{ $cliente->nombre_referencia_1 }}
                                    </h6>
                                </div>

                                <div class="col-md-2">
                                    <h6><span class="text-primary">CELULAR 3: </span>
                                        {{ $cliente->celular_referencia_1 }}
                                    </h6>
                                </div>
                            </div>

                            <!--end::Content-->
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Details-->
                    <!--end::Details-->
                    <div class="separator separator-solid"></div>
                    <!--begin::Items-->
                    <div class="d-flex align-items-center flex-wrap mt-8">
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span class="mr-4">
                                <i class="fa-solid fa-money-bill-1-wave" style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">PRECIO</span>
                                <h5>{{ $factura->total }}</h5>
                            </div>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span>
                                <i class="fas fa-barcode"  style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">A CUENTA</span>
                                <h5>{{ number_format($factura->pagos->sum('monto'), 2) }}</h5>
                            </div>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span class="mr-4">
                                <i class="fas fa-democrat"  style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">SALDO</span>
                                <h5>{{ number_format(($factura->total - $factura->pagos->sum('monto')),2) }}</h5>
                            </div>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span class="mr-4">
                                <i class="fas fa-file-pdf" style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">NOTA VENTA</span>
                                <a target="_blank" href="{{ url('factura/recibo') }}/{{ $factura->id }}" class="btn btn-danger btn-sm btn-icon w-100"><i class="fa fa-file-pdf"></i></a>
                                {{-- <h5>{{ $ejemplar->tipo }}</h5> --}}
                            </div>
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--begin::Items-->
                </div>
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
<!--end::Content wrapper-->
<hr>

<div class="row">
    <div class="col-md-12">
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <div id="kt_app_content_container" class="app-container container-xxlg">
                    <div class="card shadow-sm">
                        <div class="card-body py-4">
                            <!--begin::Accordion-->
                            <div class="accordion accordion-icon-collapse" id="kt_accordion_3">
                                <!--begin::Item-->
                                <div class="mb-5">
                                    <!--begin::Header-->
                                    <div class="accordion-header py-3 d-flex" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_item_1">
                                        <span class="accordion-icon">
                                            <i class="ki-duotone ki-plus-square fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                            <i class="ki-duotone ki-minus-square fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                        <h3 class="fs-4 fw-semibold mb-0 ms-4">REGISTRO DE ORDENES DE TRABAJO</h3>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div id="kt_accordion_3_item_1" class="fs-6 collapse show ps-10" data-bs-parent="#kt_accordion_3">
                                        <div id="tabla-orden-trabjo"></div>
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="mb-5">
                                    <!--begin::Header-->
                                    <div class="accordion-header py-3 d-flex collapsed" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_item_2">
                                        <span class="accordion-icon">
                                        <i class="ki-duotone ki-plus-square fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <i class="ki-duotone ki-minus-square fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                        <h3 class="fs-4 fw-semibold mb-0 ms-4">REGISTRO OJALES</h3>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div id="kt_accordion_3_item_2" class="collapse fs-6 ps-10" data-bs-parent="#kt_accordion_3">
                                        <div id="tabla-ojales"></div>
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Item-->

                                <!--begin::Item-->
                                <div class="mb-5">
                                    <!--begin::Header-->
                                    <div class="accordion-header py-3 d-flex collapsed" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_item_3">
                                        <span class="accordion-icon">
                                        <i class="ki-duotone ki-plus-square fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                        <i class="ki-duotone ki-minus-square fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                        <h3 class="fs-4 fw-semibold mb-0 ms-4">REGISTRO DE LASER</h3>
                                    </div>
                                    <!--end::Header-->

                                    <!--begin::Body-->
                                    <div id="kt_accordion_3_item_3" class="collapse fs-6 ps-10" data-bs-parent="#kt_accordion_3">
                                        <div id="tabla-lasers"></div>
                                    </div>
                                    <!--end::Body-->
                                </div>
                                <!--end::Item-->
                            </div>
                            <!--end::Accordion-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- <div class="row">
    <div class="col-md-12">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxlg">
                    <!--begin::Card-->
                    <div class="card">
                        <div class="card-header flex-wrap bg-light py-4">
                            <div id="kt_app_toolbar_container" class="app-container container-xxlg d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Registros Biometricos</h1>
                                    <!--end::Title-->
                                </div>
                                <!--end::Page title-->
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div id="tabla_biometrias"></div>
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxlg">
                    <!--begin::Card-->
                    <div class="card">
                        <div class="card-header flex-wrap bg-light py-4">
                            <div id="kt_app_toolbar_container" class="app-container container-xxlg d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Registros Morfologicos</h1>
                                    <!--end::Title-->
                                </div>
                                <!--end::Page title-->
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div id="tabla_morfilogicos"></div>
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxlg">
                    <!--begin::Card-->
                    <div class="card">
                        <div class="card-header flex-wrap bg-light py-4">
                            <div id="kt_app_toolbar_container" class="app-container container-xxlg d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Registros Fibras</h1>
                                    <!--end::Title-->
                                </div>
                                <!--end::Page title-->
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div id="tabla_analisis_fibras"></div>
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxlg">
                    <!--begin::Card-->
                    <div class="card">
                        <div class="card-header flex-wrap bg-light py-4">
                            <div id="kt_app_toolbar_container" class="app-container container-xxlg d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">GENERACIONES DEL EJEMPLAR</h1>
                                    <!--end::Title-->
                                </div>
                                <!--end::Page title-->
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div id="chart-container"></div>
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
</div> --}}

@stop()

@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.orgchart.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        $.ajaxSetup({
            // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        let filaTableLaser = 1;

        $(document).ready(function() {
            ajaxListadoOrdenTrabajos();
            ajaxListadoOjales();
            ajaxListadoLaser();
        });

        function ajaxListadoOrdenTrabajos(){
            let datos = {factura:{{ $factura->id }}};
            $.ajax({
                url: "{{ route('ordenTrabajo.ajaxListadoOrdenTrabajos') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {
                    if (resultado.estado)
                        $('#tabla-orden-trabjo').html(resultado.data.listado)
                }
            })
        }

        function ajaxListadoOjales(){
            let datos = {factura:{{ $factura->id }}};
            $.ajax({
                url: "{{ route('ordenTrabajo.ajaxListadoOjales') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {
                    if (resultado.estado)
                        $('#tabla-ojales').html(resultado.data.listado)
                }
            })
        }

        function ajaxListadoLaser(){
            let datos = {factura:{{ $factura->id }}};
            $.ajax({
                url: "{{ route('ordenTrabajo.ajaxListadoLaser') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {
                    if (resultado.estado)
                        $('#tabla-lasers').html(resultado.data.listado)
                }
            })
        }

        function modalAgregarLaser(ordenTrabajo, nroOt, observacion, cantidad){

            $('#orden_trabajo_id').val(ordenTrabajo);
            $('#observacion_orden_trabajo').val(observacion);
            $('#numero_orden_trabajo').val(nroOt);
            $('#numero_prendas_orden_trabajo').val(cantidad);
            $('#modalAgregarLaser').modal('show');
        }

        function agregarFilaLaser(){

            filaTableLaser++;

            let nuevaFila = `
                <tr>
                    <td><input name="talla_laser[${filaTableLaser}]" type="text" style="width: 100%;"></td>
                    <td><input name="cantidad_laser[${filaTableLaser}]" type="text" style="width: 100%;"></td>
                    <td><input name="intensidad_laser[${filaTableLaser}]" type="text" style="width: 100%;"></td>
                    <td><input name="altura_laser[${filaTableLaser}]" type="text" style="width: 100%;"></td>
                    <td><input name="dpi_laser[${filaTableLaser}]" type="text" style="width: 100%;"></td>
                    <td><input name="pos_1_laser[${filaTableLaser}]" id="pos_1_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="text" style="width: 100%;"></td>
                    <td><input name="pos_2_laser[${filaTableLaser}]" id="pos_2_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="text" style="width: 100%;"></td>
                    <td><input name="pos_3_laser[${filaTableLaser}]" id="pos_3_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="text" style="width: 100%;"></td>
                    <td><input name="pos_4_laser[${filaTableLaser}]" id="pos_4_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="text" style="width: 100%;"></td>
                    <td><input name="prenda_x_mesa_laser[${filaTableLaser}]" id="prenda_x_mesa_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="1" type="text" style="width: 100%;"></td>
                    <td><input name="tiempo_total_laser[${filaTableLaser}]" id="tiempo_total_laser_${filaTableLaser}" type="text" style="width: 100%;"></td>
                    <td><input class="precioMinutosValor" name="precio_minuto_valor[${filaTableLaser}]" id="precio_minuto_valor_${filaTableLaser}" type="text" style="width: 100%" value="6" onchange="calculapreciominutototal(${filaTableLaser})"></td>
                    <td><input type="text" style="width: 100%" name="valor_laser[${filaTableLaser}]" value="0" name="valor_laser_${filaTableLaser}" id="valor_laser_${filaTableLaser}"></td>
                    <td>
                        <button title="Duplicar Debajo"
                            onclick="duplicarDebajo(${filaTableLaser})"
                            class="btn btn-success btn-icon btn-sm btn-circle" type="button">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button title="Duplicar Debajo"
                            onclick="elimiarFila(${filaTableLaser})"
                            class="btn btn-danger btn-icon btn-sm btn-circle" type="button">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            $("#table_laser tbody").append(nuevaFila);

        }

        function duplicarDebajo(fila){

            filaTableLaser++;
            let filaOriginal = $("#table_laser tbody tr").eq(fila - 1);

            let talla      = filaOriginal.find('input[name="talla_laser['+fila+']"]').val();
            let cantidad   = filaOriginal.find('input[name="cantidad_laser['+fila+']"]').val();
            let intensidad = filaOriginal.find('input[name="intensidad_laser['+fila+']"]').val();
            let altura     = filaOriginal.find('input[name="altura_laser['+fila+']"]').val();
            let dpi        = filaOriginal.find('input[name="dpi_laser['+fila+']"]').val();
            let pos1       = filaOriginal.find('input[name="pos_1_laser['+fila+']"]').val();
            let pos2       = filaOriginal.find('input[name="pos_2_laser['+fila+']"]').val();
            let pos3       = filaOriginal.find('input[name="pos_3_laser['+fila+']"]').val();
            let pos4       = filaOriginal.find('input[name="pos_4_laser['+fila+']"]').val();
            let pxm        = filaOriginal.find('input[name="prenda_x_mesa_laser['+fila+']"]').val();
            let tiempo     = filaOriginal.find('input[name="tiempo_total_laser['+fila+']"]').val();

            let nuevaFila = `
                <tr>
                    <td><input name="talla_laser[${filaTableLaser}]" type="text" style="width: 100%;" value="${talla}"></td>
                    <td><input name="cantidad_laser[${filaTableLaser}]" type="text" style="width: 100%;" value="${cantidad}"></td>
                    <td><input name="intensidad_laser[${filaTableLaser}]" type="text" style="width: 100%;" value="${intensidad}"></td>
                    <td><input name="altura_laser[${filaTableLaser}]" type="text" style="width: 100%;" value="${altura}"></td>
                    <td><input name="dpi_laser[${filaTableLaser}]" type="text" style="width: 100%;" value="${dpi}"></td>
                    <td><input name="pos_1_laser[${filaTableLaser}]" id="pos_1_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="text" style="width: 100%;" value="${pos1}"></td>
                    <td><input name="pos_2_laser[${filaTableLaser}]" id="pos_2_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="text" style="width: 100%;" value="${pos2}"></td>
                    <td><input name="pos_3_laser[${filaTableLaser}]" id="pos_3_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="text" style="width: 100%;" value="${pos3}"></td>
                    <td><input name="pos_4_laser[${filaTableLaser}]" id="pos_4_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="text" style="width: 100%;" value="${pos4}"></td>
                    <td><input name="prenda_x_mesa_laser[${filaTableLaser}]" id="prenda_x_mesa_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" type="text" style="width: 100%;" value="${pxm}"></td>
                    <td><input name="tiempo_total_laser[${filaTableLaser}]" id="tiempo_total_laser_${filaTableLaser}" type="text" style="width: 100%;" value="${tiempo}"></td>
                    <td><input class="precioMinutosValor" name="precio_minuto_valor[${filaTableLaser}]" id="precio_minuto_valor_${filaTableLaser}" type="text" style="width: 100%" value="6" onchange="calculapreciominutototal(${filaTableLaser})"></td>
                    <td><input type="text" style="width: 100%" name="valor_laser[${filaTableLaser}]" value="0" name="valor_laser_${filaTableLaser}" id="valor_laser_${filaTableLaser}"></td>
                    <td>
                        <button title="Duplicar Debajo"
                            onclick="duplicarDebajo(${filaTableLaser})"
                            class="btn btn-success btn-icon btn-sm btn-circle" type="button">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button title="Duplicar Debajo"
                            onclick="elimiarFila(${filaTableLaser})"
                            class="btn btn-danger btn-icon btn-sm btn-circle" type="button">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            filaOriginal.after(nuevaFila);

        }

        function elimiarFila(fila){
            let filaEliminar = $("#table_laser tbody tr").eq(fila - 1);
            filaEliminar.remove();
        }

        function guardarLaser(){
            let datos = $('#formularioLaser').serializeArray();
            $.ajax({
                url: "{{ route('ordenTrabajo.guardarLaser') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {
                    if (resultado.estado){
                        // $('#tabla-orden-trabjo').html(resultado.data.listado)
                        Swal.fire(
                            'Exito',
                            'Se guardo con exito el registor laser',
                            'success'
                        );
                        $('#modalAgregarLaser').modal('hide')
                    }
                }
            })
        }

        function sumaTimepos(fila){

            let timempo1 = $('#pos_1_laser_'+fila).val();
            let timempo2 = $('#pos_2_laser_'+fila).val();
            let timempo3 = $('#pos_3_laser_'+fila).val();
            let timempo4 = $('#pos_4_laser_'+fila).val();
            let cantidadMesa = $('#prenda_x_mesa_laser_'+fila).val()

            let sumTotalTiempo = parseFloat(timempo1) + parseFloat(timempo2) + parseFloat(timempo3) + parseFloat(timempo4);

            let resultado = sumTotalTiempo / cantidadMesa;
            $('#tiempo_total_laser_' + fila).val(resultado.toFixed(2));

            // AHORA CALCULAMOS LOS MINUTOS TOTALES
            let minTotal = parseFloat($('#minutos_totales').val());
            $('#minutos_totales').val(minTotal + sumTotalTiempo)


            calcularPrecio(fila)

        }

        function calcularPrecio(item){
            let precio = $('#tiempo_total_laser_'+item).val();
            let precioMinuto = $('#precio_minuto_valor_'+item).val();
            let calculo = parseFloat(precio) * parseFloat(precioMinuto);
            $('#valor_laser_'+item).val(calculo);
        }

        function cambiarPrecioMinuto(){

            $('.precioMinutosValor').val($('#precio_minuto').val()).trigger('change');

        }

        function calculapreciominutototal(dato){
            let valor = $('#precio_minuto_valor_'+dato).val();
            let totalMin = $('#tiempo_total_laser_'+dato).val();

            $('#valor_laser_'+dato).val(parseFloat(valor) * parseFloat(totalMin));
        }

        function ajaxFormularioEditarOrdenTrabajo(orden){

            // let datos = $('#formularioLaser').serializeArray();
            $.ajax({
                url: "{{ route('ordenTrabajo.ajaxFormularioEditarOrdenTrabajo') }}",
                method: "POST",
                data: {factura:{{ $factura->id }}},
                success: function(resultado) {
                    if (resultado.estado){

                        $('#formularioAjaxOrdenTrabajo').html(resultado.data.listado)

                        $('#modalOrdenTrabajo').modal('show')
                    }
                }
            })
        }

        function ajaxNroOtFactura(){
            $.ajax({
                url: "{{ route('ordenTrabajo.ajaxNroOtFactura') }}",
                method: "POST",
                data: {factura:{{ $factura->id }}},
                success: function(resultado) {
                    if (resultado.estado){
                        let listado = resultado.data.listaOt
                        $('#numero_ot_select').empty().append('<option value="">Seleccione una OT</option>');
                        $.each(listado, function (i, element) {
                            $('#numero_ot_select').append(
                                $('<option>', {
                                    value: element.nro_ot,
                                    text: 'OT ' + element.nro_ot
                                })
                            );
                        });
                        $('#modalOrdenTrabajoImpresion').modal('show')
                    }
                }
            })
        }

        function imprimirOrdenTrabajo(){
            let select = $('#numero_ot_select').val();

            if (!select) {
                // alert('Seleccione una OT');
                Swal.fire(
                    'Error',
                    'Seleccione una OT',
                    'error'
                );
                return;
            }

            let url = "{{ route('ordenTrabajo.imprimirOrdenTrabajo', ['factura_id' => '__FACTURA__', 'nro_orden' => '__OT__']) }}"
                .replace('__FACTURA__', {{ $factura->id }})
                .replace('__OT__', select);

            // window.location.href = url;
            window.open(url, '_blank');
        }

        function editarEstadoOrdenTrabajo(orden, estado){
            console.log(orden);

            $('#numero_orden_trabajo_text').text(orden)
            $('#estado_orden_trabajo').val(estado)
            $('#nro_ot_estado').val(orden)

            $('#modalEdicionEstadoOrdenTrabajo').modal('show')
        }

        function guardarEstadoOrdenTrabajo(){
            let datos = $('#formularioCambioEstadoOrdenTrabajo').serializeArray();
            $.ajax({
                url: "{{ route('ordenTrabajo.guardarEstadoOrdenTrabajo') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {
                    if (resultado.estado){
                        ajaxListadoOrdenTrabajos();
                        Swal.fire(
                            'Exito',
                            'Se Actualizo el estado con exito',
                            'success'
                        );
                        $('#modalEdicionEstadoOrdenTrabajo').modal('hide')
                    }
                }
            })
        }

        function agregarProducto(){

            if($("#formularioNewOt")[0].checkValidity()){
                Swal.fire({
                    title: "Esta seguro de agregar un nuevo OT?",
                    text: "Ya no podras revertir eso!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Si, agregar!"
                }).then((result) => {
                    if (result.isConfirmed) {
                        let datos = $('#formularioNewOt').serializeArray();
                        $.ajax({
                            url: "{{ url('factura/agregarNuevoOrdenTrabajo') }}",
                            method: "POST",
                            data: datos,
                            success: function(resultado) {
                                if (resultado.estado){
                                    ajaxListadoOrdenTrabajos();
                                    Swal.fire(
                                        'Exito',
                                        'Se agrego con exito',
                                        'success'
                                    );
                                    ajaxListadoOjales();
                                    $('#formularioAjaxOrdenTrabajo').html("")
                                    ajaxFormularioEditarOrdenTrabajo();
                                }
                            }
                        })
                    }
                });
            }else{
                $("#formularioNewOt")[0].reportValidity();
            }
        }

        function calcularsubTotal() {
            let cantidad = parseFloat($('#cantidad_venta').val())
            let precio = parseFloat($('#precio_venta').val())

            $('#sub_total').val(cantidad * precio);
        }

        function cuantificarOjales() {

            let cantidadPrendas = $('#cantidad_venta').val();
            let cantidadOjales = $('#numero_ojales').val();
            let todo = cantidadOjales + "/" + cantidadPrendas;

            $('#numero_ojales').val(todo);

            if (cantidadOjales > 1) {

                let calculo = (cantidadOjales - 1) * cantidadPrendas;
                let precio_ojal = $('#precio_ojales').val();
                let total = parseFloat(calculo) * parseFloat(precio_ojal);

                $('#nro_ojales').val(calculo);
                $('#total_ojales').val(total.toFixed(2));
                $('#bloque-ojales').show('toggle');
            } else {
                $('#nro_ojales').val(0);
                $('#total_ojales').val(0);
                $('#bloque-ojales').hide('toggle')
            }

        }

        function cambiarDato(tipo, ordenTrabajo, dato){

            $.ajax({
                url: "{{ route('ordenTrabajo.cambiaDatoOrdenTrabajo') }}",
                method: "POST",
                data: {
                    tipo:tipo,
                    ordenTrabajo:ordenTrabajo,
                    dato:dato.value
                },
                success: function(resultado) {
                    if (resultado.estado){
                        $('#'+tipo+"_"+ordenTrabajo).show('toggle')
                    }else{
                        Swal.fire(
                            'Error',
                            'Ocurrio un error',
                            'error'
                        );
                    }
                }
            })
        }
   </script>
@endsection
