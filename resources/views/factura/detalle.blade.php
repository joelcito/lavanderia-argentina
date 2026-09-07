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
    <div class="modal-dialog modal-dialog-centered" style="max-width: 95%">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h3 class="fw-bold">FORMULARIO DE LASER</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body scroll-y">
                <div id="formulario-laser-bloque"></div>
            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-12">
                        <button class="btn btn-sm w-100 btn-success" onclick="guardarLaser()">Guardar Laser</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Modal - Add task-->

<!--begin::Modal - Add task-->
<div class="modal fade" id="modalListadoLaser" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 90%">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h3 class="fw-bold">LISTADO DE LASER</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                 <div style="overflow-x:auto">
                    <div id="listado-laser-bloque"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--end::Modal - Add task-->

<!--begin::Modal - Add task-->
<div class="modal fade" id="modalListadoOjales" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 80%">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h3 class="fw-bold">LISTADO DE OJALES</h3>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary btn-sm" onclick="modalNuevoOjal()">
                        <i class="fa fa-plus"></i> Nuevo Ojal
                    </button>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body scroll-y">
                <div id="listado-ojales-bloque"></div>
            </div>
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
        </div>
    </div>
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

<!--begin::Modal - Add task-->
<div class="modal fade" id="modalEdicionOjal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h3 class="fw-bold">FORMULARIO EDICION OJAL</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body scroll-y">
                <form id="formularioEdicionOjal">
                    <input type="hidden" id="ojal_id" name="ojal_id">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nombre_ojal" class="form-label">Cantidad</label>
                                <input type="text" class="form-control form-control-sm" id="ojal_cantidad" name="ojal_cantidad" onkeyup="calcularSubTotalOjalEdicio()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="descripcion_ojal" class="form-label">Precio</label>
                                <input type="text" class="form-control form-control-sm" id="ojal_precio" name="ojal_precio" onkeyup="calcularSubTotalOjalEdicio()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="descripcion_ojal" class="form-label">Subtotal</label>
                                <input type="text" class="form-control form-control-sm" id="ojal_subtotal" name="ojal_subtotal" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarEdicionOjal()">Guardar</button>
            </div>
        </div>
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Add task-->

<!--begin::Modal - Add task-->
<div class="modal fade" id="modalNuevoOjal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_user_header">
                <h3 class="fw-bold">FORMULARIO NUEVO OJAL</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body scroll-y">
                <form id="formularioNuevoOjal">
                    <input type="text" id="new_ojal_factura_id" name="new_ojal_factura_id" value="{{ $factura->id }}">
                    <input type="text" id="new_ojal_orden_trabajo_id" name="new_ojal_orden_trabajo_id">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="nombre_ojal" class="form-label">Cantidad</label>
                                <input type="text" class="form-control form-control-sm" id="new_ojal_cantidad"
                                    name="new_ojal_cantidad" onkeyup="calcularSubTotalOjalNuevo()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="descripcion_ojal" class="form-label">Precio</label>
                                <input type="text" class="form-control form-control-sm" id="new_ojal_precio"
                                    name="new_ojal_precio" onkeyup="calcularSubTotalOjalNuevo()">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="descripcion_ojal" class="form-label">Subtotal</label>
                                <input type="text" class="form-control form-control-sm" id="new_ojal_subtotal"
                                    name="new_ojal_subtotal" readonly>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" onclick="guardarNuevonOjal()">Guardar</button>
            </div>
        </div>
    </div>
    <!--end::Modal dialog-->
</div>
<!--end::Modal - Add task-->


{{-- ============================================================ --}}
{{-- MODAL RECETA DEL LAVADO --}}
{{-- ============================================================ --}}

<div class="modal fade" id="modalFacturaReceta" tabindex="-1" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered" style="max-width: 98%;">

        <div class="modal-content">

            <div class="modal-header">

                <div>

                    <h3 class="fw-bold mb-1">
                        RECETA DEL LAVADO
                    </h3>

                    {{-- <small class="text-muted">
                        Factura #{{ $factura->id }}
                    </small> --}}

                    <small class="text-muted">
                        Factura #{{ $factura->id }} | OT: <strong id="fr_numero_ot_texto"> - </strong> | Peso: <strong id="fr_peso_ot_texto"> - </strong> Kg
                    </small>

                </div>


                <button type="button" class="btn-close" data-bs-dismiss="modal">
                </button>

            </div>


            <div class="modal-body scroll-y">

                <form id="formularioFacturaReceta">

                    <input type="hidden" name="factura_id" id="fr_factura_id" value="{{ $factura->id }}">
                    <input type="hidden" name="factura_receta_id" id="fr_factura_receta_id" value="0">
                    <input type="hidden" name="receta_id" id="fr_receta_id" value="">
                    <input type="hidden" name="order_trabajo_id" id="fr_order_trabajo_id" value="">

                    {{-- ================================================ --}}
                    {{-- RECETA MAESTRA --}}
                    {{-- ================================================ --}}

                    <div class="card bg-light-primary mb-5">

                        <div class="card-body py-4">

                            <div class="row align-items-end">


                                <div class="col-md-8">

                                    <label class="form-label fw-bold">

                                        Buscar receta maestra

                                    </label>


                                    <select class="form-select form-select-sm" id="fr_buscar_receta">

                                        <option value="">
                                            Seleccione una receta
                                        </option>

                                    </select>

                                </div>


                                <div class="col-md-4">

                                    <button type="button" class="btn btn-primary btn-sm w-100"
                                        onclick="cargarRecetaMaestra()">

                                        <i class="fa fa-copy"></i>

                                        Cargar copia de la receta

                                    </button>

                                </div>

                            </div>


                            <div id="fr_receta_asociada_texto" class="alert alert-success mt-4 mb-0 d-none">

                            </div>

                        </div>

                    </div>



                    {{-- ================================================ --}}
                    {{-- CABECERA --}}
                    {{-- ================================================ --}}

                    <div id="fr_bloque_editor" style="display:none;">


                        <div class="card mb-5">

                            <div class="card-header">

                                <h4 class="card-title">
                                    Datos de la receta
                                </h4>

                            </div>


                            <div class="card-body">

                                <div class="row mb-4">

                                    <div class="col-md-3">

                                        <label class="form-label fw-bold">
                                            Peso Kg del lavado
                                        </label>

                                        <input type="number" class="form-control form-control-sm" id="fr_peso_kg" step="0.00001" readonly>

                                    </div>


                                    <div class="col-md-3">

                                        <label class="form-label fw-bold">
                                            Peso Gr del lavado
                                        </label>

                                        <input type="number" class="form-control form-control-sm" id="fr_peso_gr" step="0.00001" readonly>

                                    </div>

                                </div>


                                <div class="row">


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Nombre
                                        </label>

                                        <input type="text" class="form-control form-control-sm" id="fr_nombre"
                                            name="nombre">

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Tipo Tela
                                        </label>

                                        <select class="form-select form-select-sm" id="fr_tipo_tela_id"
                                            name="tipo_tela_id">
                                        </select>

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Color Tela
                                        </label>

                                        <select class="form-select form-select-sm" id="fr_color_tela_id"
                                            name="color_tela_id">
                                        </select>

                                    </div>

                                </div>


                                <div class="row mt-4">


                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Nombre Tela
                                        </label>

                                        <select class="form-select form-select-sm" id="fr_nombre_tela_id"
                                            name="nombre_tela_id">
                                        </select>

                                    </div>


                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Prelavado
                                        </label>

                                        <select class="form-select form-select-sm" id="fr_prelavado_id"
                                            name="prelavado_id">
                                        </select>

                                    </div>


                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Focalizado
                                        </label>

                                        <select class="form-select form-select-sm" id="fr_focalizado_id"
                                            name="focalizado_id">
                                        </select>

                                    </div>


                                    <div class="col-md-3">

                                        <label class="form-label">
                                            Nevado
                                        </label>

                                        <select class="form-select form-select-sm" id="fr_nevado_id" name="nevado_id">
                                        </select>

                                    </div>

                                </div>


                                <div class="row mt-4">


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Característica
                                        </label>

                                        <select class="form-select form-select-sm" id="fr_caracteristica_id"
                                            name="caracteristica_id">
                                        </select>

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Proceso Principal
                                        </label>

                                        <select class="form-select form-select-sm" id="fr_tipo_proceso_id"
                                            name="tipo_proceso_id">
                                        </select>

                                    </div>


                                    <div class="col-md-4">

                                        <label class="form-label">
                                            Descripción
                                        </label>

                                        <input type="text" class="form-control form-control-sm" id="fr_descripcion"
                                            name="descripcion">

                                    </div>

                                </div>

                            </div>

                        </div>



                        {{-- ============================================ --}}
                        {{-- PROCESOS --}}
                        {{-- ============================================ --}}

                        <div class="card">

                            <div class="card-header
                                       d-flex
                                       align-items-center
                                       justify-content-between">

                                <h4 class="card-title">
                                    Procesos y productos
                                </h4>


                                {{-- <button type="button" class="btn btn-primary btn-sm"
                                    onclick="agregarProcesoFacturaReceta()">

                                    <i class="fa fa-plus"></i>

                                    Agregar Proceso

                                </button> --}}

                            </div>


                            <div class="card-body">

                                <div id="fr_procesos">

                                </div>

                            </div>

                            <button type="button" class="btn btn-primary btn-sm" onclick="agregarProcesoFacturaReceta()">

                                <i class="fa fa-plus"></i>

                                Agregar Proceso

                            </button>

                        </div>


                    </div>

                </form>

            </div>


            <div class="modal-footer">


                <button type="button" id="fr_boton_eliminar" class="btn btn-danger" style="display:none;"
                    onclick="eliminarFacturaReceta()">

                    <i class="fa fa-trash"></i>

                    Eliminar receta asociada

                </button>


                <button type="button" class="btn btn-light" data-bs-dismiss="modal">

                    Cerrar

                </button>


                <button type="button" id="fr_boton_guardar" class="btn btn-success" style="display:none;"
                    onclick="guardarFacturaReceta()">

                    <i class="fa fa-save"></i>

                    Guardar receta del lavado

                </button>

            </div>

        </div>

    </div>

</div>

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
                                <h5>{{ number_format($factura->total , 2) }}</h5>
                            </div>
                        </div>
                        <!--end::Item-->

                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span>
                                <i class="fas fa-barcode"  style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">DESCUENTO</span>
                                <h5>{{ number_format($factura->descuento_adicional, 2) }}</h5>
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
                                <h5>{{ number_format((($factura->total - $factura->descuento_adicional) - $factura->pagos->sum('monto')),2) }}</h5>
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
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span class="mr-4">
                                <i class="fas fa-edit" style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">EDITAR</span>
                                <button onclick="ajaxFormularioEditarOrdenTrabajo()" class="btn btn-warning btn-sm btn-icon w-100"><i class="fa fa-edit"></i></button>
                            </div>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span class="mr-4">
                                <i class="fas fa-plus" style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">IMPRESION DE OT</span>
                                <button onclick="ajaxNroOtFactura()" class="btn btn-info btn-sm btn-icon w-100"><i class="fa fa-plus"></i></button>
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

                                {{-- <!--begin::Item-->
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
                                <!--end::Item--> --}}

                                {{-- <!--begin::Item-->
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
                                <!--end::Item--> --}}
                            </div>
                            <!--end::Accordion-->

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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

        let frTipoTelas = [];
        let frColorTelas = [];
        let frNombreTelas = [];
        let frPrelavados = [];
        let frFocalizados = [];
        let frNevados = [];
        let frCaracteristicas = [];
        let frTipoProcesos = [];
        let frProductos = [];

        let frProcesoContador = 0;

        let frPesoOt = 0;

        $(document).ready(function() {
            ajaxListadoOrdenTrabajos();

            $(document).on('input','#modalFacturaReceta .fr-porcentaje',function () {

                let fila =$(this).closest('.fr-producto');

                /*
                * Peso de la OT en Kg
                * convertido a gramos
                */

                let pesoGr =
                frPesoOt * 1000;


                let porcentaje =
                parseFloat(
                $(this).val()
                ) || 0;


                if (pesoGr <= 0) { fila .find('.fr-cantidad') .val(0); return; } let cantidad=( pesoGr * porcentaje ) / 100; fila
                    .find('.fr-cantidad') .val( cantidad.toFixed(2) ); calcularTotalFacturaReceta( fila ); } );

            });

            $(document).on('input','#modalFacturaReceta .fr-cantidad',function () {
                let fila =
                    $(this)
                        .closest('.fr-producto');


                let pesoGr =
                    frPesoOt * 1000;


                let cantidad =
                    parseFloat(
                        $(this).val()
                    ) || 0;


                if (pesoGr <= 0) {

                    fila
                        .find('.fr-porcentaje')
                        .val(0);

                    return;
                }


                let porcentaje =
                    (
                        cantidad /
                        pesoGr
                    )
                    *
                    100;


                fila
                    .find('.fr-porcentaje')
                    .val(
                        porcentaje.toFixed(2)
                    );


                calcularTotalFacturaReceta(
                    fila
                );
            });

            $(document).on('change','#modalFacturaReceta .fr-producto-id',function () {

                    let fila =
                        $(this)
                            .closest('.fr-producto');


                    calcularTotalFacturaReceta(
                        fila
                    );
                }
            );

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

        function ajaxListadoOjales(ot){
            $('#new_ojal_orden_trabajo_id').val(ot)
            let datos = {factura:{{ $factura->id }}, ot:ot};
            $.ajax({
                url: "{{ route('ordenTrabajo.ajaxListadoOjales') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {
                    if (resultado.estado){
                        $('#listado-ojales-bloque').html(resultado.data.listado)
                        $('#modalListadoOjales').modal('show')
                    }
                }
            })
        }

        function ajaxListadoLaser(ot){
            let datos = {factura:{{ $factura->id }}, ot:ot};
            $.ajax({
                url: "{{ route('ordenTrabajo.ajaxListadoLaser') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {
                    if (resultado.estado){
                        $('#listado-laser-bloque').html(resultado.data.listado)
                        $('#modalListadoLaser').modal('show')
                    }
                }
            })
        }

        function modalAgregarLaser(ordenTrabajo, nroOt, observacion, cantidad){

            let datos = {
                factura     : {{ $factura->id }},
                ordenTrabajo: ordenTrabajo,
                nroOt       : nroOt,
                observacion : observacion,
                cantidad    : cantidad
            };
            $.ajax({
                url: "{{ route('ordenTrabajo.ajaxFormularioLaser') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {
                    if (resultado.estado){

                        $('#formulario-laser-bloque').html(resultado.data.formulario)

                        // $('#orden_trabajo_id').val(ordenTrabajo);
                        // $('#observacion_orden_trabajo').val(observacion);
                        // $('#numero_orden_trabajo').val(nroOt);
                        // $('#numero_prendas_orden_trabajo').val(cantidad);
                        $('#modalAgregarLaser').modal('show');

                    }
                }
            })

        }

        function agregarFilaLaser(){

            filaTableLaser++;

            let nuevaFila = `
                <tr id="fila_laser_${filaTableLaser}">

                    <td><input autocomplete="off" name="talla_laser[${filaTableLaser}]" type="text" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="cantidad_laser[${filaTableLaser}]" onkeyup="sumaCantidadPrendas(${filaTableLaser})" onclick="this.select()" id="cantidad_laser_${filaTableLaser}" type="number" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="intensidad_laser[${filaTableLaser}]" type="number" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="altura_laser[${filaTableLaser}]" type="number" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="dpi_laser[${filaTableLaser}]" type="number" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="pos_1_laser[${filaTableLaser}]" id="pos_1_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="number" step="0.001" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="pos_2_laser[${filaTableLaser}]" id="pos_2_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="number" step="0.001" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="pos_3_laser[${filaTableLaser}]" id="pos_3_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="number" step="0.001" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="pos_4_laser[${filaTableLaser}]" id="pos_4_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="number" step="0.001" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="prenda_x_mesa_laser[${filaTableLaser}]" id="prenda_x_mesa_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" onclick="this.select()" value="1" type="text" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="tiempo_total_laser[${filaTableLaser}]" id="tiempo_total_laser_${filaTableLaser}" type="text" style="width: 100%;" readonly></td>
                    <td><input autocomplete="off" name="precio_pronosticado[${filaTableLaser}]" id="precio_pronosticado_${filaTableLaser}" type="number" style="width: 100%;"></td>
                    <td><input autocomplete="off" name="precio_minuto_valor[${filaTableLaser}]" id="precio_minuto_valor_${filaTableLaser}" type="text" style="width: 100%" value="6" onchange="calculapreciominutototal(${filaTableLaser})" class="precioMinutosValor"></td>
                    <td><input autocomplete="off" name="precio_cliente[${filaTableLaser}]" id="precio_cliente_${filaTableLaser}" type="number" style="width: 100%" value="0"  onkeyup="calcularPrecioFinal(${filaTableLaser})"></td>
                    <td><input autocomplete="off" name="valor_laser[${filaTableLaser}]" id="valor_laser_${filaTableLaser}" type="number" style="width: 100%" value="0"></td>

                    <td>
                        <button title="Duplicar Debajo"
                            onclick="duplicarDebajo(${filaTableLaser})"
                            class="btn btn-success btn-icon btn-sm btn-circle" type="button">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button title="Duplicar Debajo"
                            onclick="elimiarFila(this)"
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

            let talla               = filaOriginal.find('input[name="talla_laser['+fila+']"]').val();
            let cantidad            = filaOriginal.find('input[name="cantidad_laser['+fila+']"]').val();
            let intensidad          = filaOriginal.find('input[name="intensidad_laser['+fila+']"]').val();
            let altura              = filaOriginal.find('input[name="altura_laser['+fila+']"]').val();
            let dpi                 = filaOriginal.find('input[name="dpi_laser['+fila+']"]').val();
            let pos1                = filaOriginal.find('input[name="pos_1_laser['+fila+']"]').val();
            let pos2                = filaOriginal.find('input[name="pos_2_laser['+fila+']"]').val();
            let pos3                = filaOriginal.find('input[name="pos_3_laser['+fila+']"]').val();
            let pos4                = filaOriginal.find('input[name="pos_4_laser['+fila+']"]').val();
            let pxm                 = filaOriginal.find('input[name="prenda_x_mesa_laser['+fila+']"]').val();
            let tiempo              = filaOriginal.find('input[name="tiempo_total_laser['+fila+']"]').val();
            let precio_pronosticado = filaOriginal.find('input[name="precio_pronosticado['+fila+']"]').val();
            let precio_minuto_valor = filaOriginal.find('input[name="precio_minuto_valor['+fila+']"]').val();
            let precio_cliente      = filaOriginal.find('input[name="precio_cliente['+fila+']"]').val();
            let valor_laser         = filaOriginal.find('input[name="valor_laser['+fila+']"]').val();

            let nuevaFila = `
                <tr id="fila_laser_${filaTableLaser}">
                    <td><input value="${talla}" autocomplete="off" name="talla_laser[${filaTableLaser}]" type="text" style="width: 100%;"></td>
                    <td><input value="${cantidad}" autocomplete="off" name="cantidad_laser[${filaTableLaser}]" onkeyup="sumaCantidadPrendas(${filaTableLaser})" onclick="this.select()" id="cantidad_laser_${filaTableLaser}" type="number" style="width: 100%;"></td>
                    <td><input value="${intensidad}" autocomplete="off" name="intensidad_laser[${filaTableLaser}]" type="number" style="width: 100%;"></td>
                    <td><input value="${altura}" autocomplete="off" name="altura_laser[${filaTableLaser}]" type="number" style="width: 100%;"></td>
                    <td><input value="${dpi}" autocomplete="off" name="dpi_laser[${filaTableLaser}]" type="number" style="width: 100%;"></td>
                    <td><input value="${pos1}" autocomplete="off" name="pos_1_laser[${filaTableLaser}]" id="pos_1_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="number" step="0.001" style="width: 100%;"></td>
                    <td><input value="${pos2}" autocomplete="off" name="pos_2_laser[${filaTableLaser}]" id="pos_2_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="number" step="0.001" style="width: 100%;"></td>
                    <td><input value="${pos3}" autocomplete="off" name="pos_3_laser[${filaTableLaser}]" id="pos_3_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="number" step="0.001" style="width: 100%;"></td>
                    <td><input value="${pos4}" autocomplete="off" name="pos_4_laser[${filaTableLaser}]" id="pos_4_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" value="0" onclick="this.select()" type="number" step="0.001" style="width: 100%;"></td>
                    <td><input value="${pxm}" autocomplete="off" name="prenda_x_mesa_laser[${filaTableLaser}]" id="prenda_x_mesa_laser_${filaTableLaser}" onkeyup="sumaTimepos(${filaTableLaser})" onclick="this.select()" value="1" type="text" style="width: 100%;"></td>
                    <td><input value="${tiempo}" autocomplete="off" name="tiempo_total_laser[${filaTableLaser}]" id="tiempo_total_laser_${filaTableLaser}" type="text" style="width: 100%;" readonly></td>
                    <td><input value="${precio_pronosticado}" autocomplete="off" name="precio_pronosticado[${filaTableLaser}]" id="precio_pronosticado_${filaTableLaser}" type="number" style="width: 100%;"></td>
                    <td><input value="${precio_minuto_valor}" autocomplete="off" name="precio_minuto_valor[${filaTableLaser}]" id="precio_minuto_valor_${filaTableLaser}" type="text" style="width: 100%" value="6" onchange="calculapreciominutototal(${filaTableLaser})" class="precioMinutosValor"></td>
                    <td><input value="${precio_cliente}" autocomplete="off" name="precio_cliente[${filaTableLaser}]" id="precio_cliente_${filaTableLaser}" type="number" style="width: 100%" value="0"  onkeyup="calcularPrecioFinal(${filaTableLaser})"></td>
                    <td><input value="${valor_laser}" autocomplete="off" name="valor_laser[${filaTableLaser}]" id="valor_laser_${filaTableLaser}" type="number" style="width: 100%" value="0"></td>

                    <td>
                        <button title="Duplicar Debajo"
                            onclick="duplicarDebajo(${filaTableLaser})"
                            class="btn btn-success btn-icon btn-sm btn-circle" type="button">
                            <i class="fa fa-plus"></i>
                        </button>
                        <button title="Duplicar Debajo"
                            onclick="elimiarFila(this)"
                            class="btn btn-danger btn-icon btn-sm btn-circle" type="button">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            `;

            filaOriginal.after(nuevaFila);

            sumaCantidadPrendas();

        }

        function elimiarFila(btn){
            $(btn).closest('tr').remove();
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

            let timempo1     = $('#pos_1_laser_'+fila).val();
            let timempo2     = $('#pos_2_laser_'+fila).val();
            let timempo3     = $('#pos_3_laser_'+fila).val();
            let timempo4     = $('#pos_4_laser_'+fila).val();
            let cantidadMesa = $('#prenda_x_mesa_laser_'+fila).val();

            let sumTotalTiempo = parseFloat(timempo1) + parseFloat(timempo2) + parseFloat(timempo3) + parseFloat(timempo4);

            let resultado = (sumTotalTiempo / cantidadMesa).toFixed(3);
            $('#tiempo_total_laser_' + fila).val(resultado);

            // AHORA CALCULAMOS LOS MINUTOS TOTALES
            let minTotal = parseFloat($('#minutos_totales').val());
            $('#minutos_totales').val(minTotal + sumTotalTiempo)

            // AHORA VEMOS PARA EL PRECIO PRONOSTICADO
            let precio_minuto = $('#precio_minuto_valor_1').val()
            let pronostico = ((resultado * precio_minuto) / 60).toFixed(3);
            $('#precio_pronosticado_' + fila).val(pronostico);

        }

        function cambiarPrecioMinuto(){

            $('.precioMinutosValor').val($('#precio_minuto').val()).trigger('change');

        }

        function calculapreciominutototal(dato){

            console.log("HACER ALGO !!!");

            // let valor = $('#precio_minuto_valor_'+dato).val();
            // let totalMin = $('#tiempo_total_laser_'+dato).val();

            // $('#valor_laser_'+dato).val(parseFloat(valor) * parseFloat(totalMin));
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
                        ajaxListadoOrdenTrabajos();
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

        function calcularPrecioFinal(fila){

            let precioCliente = $('#precio_cliente_'+fila).val();
            let cantidad = $('#cantidad_laser_'+fila).val();

            $('#valor_laser_'+fila).val(precioCliente * cantidad);

        }

        function sumaCantidadPrendas() {
            let total = 0;
            $('input[name^="cantidad_laser"]').each(function () {
                let valor = parseInt($(this).val()) || 0;
                total += valor;
            });
            $('#total_prendas').val(total);
        }

        function filaModificado(tipo, orden){
            console.log(tipo, orden);
            let fila = orden;

            if(
                tipo == "pos_1_laser" ||
                tipo == "pos_2_laser" ||
                tipo == "pos_3_laser" ||
                tipo == "pos_4_laser" ||
                tipo == "prenda_x_mesa_laser"

            ){

                let timempo1     = $('#modificar_pos_1_laser_'+fila).val();
                let timempo2     = $('#modificar_pos_2_laser_'+fila).val();
                let timempo3     = $('#modificar_pos_3_laser_'+fila).val();
                let timempo4     = $('#modificar_pos_4_laser_'+fila).val();
                let cantidadMesa = $('#modificar_prenda_x_mesa_laser_'+fila).val();

                let sumTotalTiempo = parseFloat(timempo1) + parseFloat(timempo2) + parseFloat(timempo3) + parseFloat(timempo4);

                let resultado = (sumTotalTiempo / cantidadMesa).toFixed(3);
                $('#modificar_tiempo_total_laser_' + fila).val(resultado);

                // AHORA CALCULAMOS LOS MINUTOS TOTALES
                // let minTotal = parseFloat($('#minutos_totales').val());
                // $('#minutos_totales').val(minTotal + sumTotalTiempo)

                // AHORA VEMOS PARA EL PRECIO PRONOSTICADO
                let precio_minuto = $('#modificar_precio_minuto_valor_'+fila).val()
                let pronostico = ((resultado * precio_minuto) / 60).toFixed(3);
                $('#modificar_precio_pronosticado_' + fila).val(pronostico);

            }else if(
                tipo == "cantidad_laser"
            ){
                //  let total = 0;
                // $('input[name^="cantidad_laser"]').each(function () {
                //     let valor = parseInt($(this).val()) || 0;
                //     total += valor;
                // });
                // $('#total_prendas').val(total);
            }else if(
                tipo == "precio_cliente"
            ){

                let precioCliente = $('#modificar_precio_cliente_'+fila).val();
                let cantidad = $('#modificar_cantidad_laser_'+fila).val();

                $('#modificar_valor_laser_'+fila).val(precioCliente * cantidad);

            }

            $('#text_'+tipo+'_'+fila).show();
        }

        function editarLaser(fila){
            $.ajax({
                url: "{{ route('ordenTrabajo.editarLaser') }}",
                method: "POST",
                data: {
                    modificar_talla_laser        : $('#modificar_talla_laser_'+fila).val(),
                    modificar_cantidad_laser     : $('#modificar_cantidad_laser_'+fila).val(),
                    modificar_intensidad_laser   : $('#modificar_intensidad_laser_'+fila).val(),
                    modificar_altura_laser       : $('#modificar_altura_laser_'+fila).val(),
                    modificar_dpi_laser          : $('#modificar_dpi_laser_'+fila).val(),
                    modificar_pos_1_laser        : $('#modificar_pos_1_laser_'+fila).val(),
                    modificar_pos_2_laser        : $('#modificar_pos_2_laser_'+fila).val(),
                    modificar_pos_3_laser        : $('#modificar_pos_3_laser_'+fila).val(),
                    modificar_pos_4_laser        : $('#modificar_pos_4_laser_'+fila).val(),
                    modificar_prenda_x_mesa_laser: $('#modificar_prenda_x_mesa_laser_'+fila).val(),
                    modificar_tiempo_total_laser : $('#modificar_tiempo_total_laser_'+fila).val(),
                    modificar_precio_pronosticado: $('#modificar_precio_pronosticado_'+fila).val(),
                    modificar_precio_minuto_valor: $('#modificar_precio_minuto_valor_'+fila).val(),
                    modificar_precio_cliente     : $('#modificar_precio_cliente_'+fila).val(),
                    modificar_valor_laser        : $('#modificar_valor_laser_'+fila).val(),
                    orden                        : fila
                },
                success: function(resultado) {
                    if (resultado.estado){
                        Swal.fire(
                            'Exito',
                            'Se modifico con exito',
                            'success'
                        );
                        $('#modalListadoLaser').modal('hide');
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

        function eliminarLaser(orden){
            Swal.fire({
                title: "Esta seguro de eliminar el LASER?",
                text: "Ya no podras revertir eso!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let datos ={dato:orden};
                    $.ajax({
                        url: "{{ url('ordenTrabajo/eliminarLaser') }}",
                        method: "POST",
                        data: datos,
                        success: function(resultado) {
                            if (resultado.estado){
                                Swal.fire(
                                    'Exito',
                                    'Se elimino con exito',
                                    'success'
                                );
                                $('#modalListadoLaser').modal('hide');
                            }
                        }
                    })
                }
            });
        }

        function editarOjal(ojal){
            $('#ojal_id').val(ojal.id)
            $('#ojal_cantidad').val(ojal.cantidad)
            $('#ojal_precio').val(ojal.precio)
            $('#ojal_subtotal').val(ojal.subtotal)

            // $('#modalListadoOjales').modal('hide')
            $('#modalEdicionOjal').modal('show')
        }

        function calcularSubTotalOjalEdicio(){
            let cantidad = parseFloat($('#ojal_cantidad').val())
            let precio = parseFloat($('#ojal_precio').val())

            $('#ojal_subtotal').val((cantidad * precio).toFixed(2));
        }

        function calcularSubTotalOjalNuevo(){
            let cantidad = parseFloat($('#new_ojal_cantidad').val())
            let precio = parseFloat($('#new_ojal_precio').val())

            $('#new_ojal_subtotal').val((cantidad * precio).toFixed(2));
        }

        function guardarEdicionOjal(){
            let datos = $('#formularioEdicionOjal').serializeArray();
            $.ajax({
                url: "{{ route('ordenTrabajo.guardarEdicionOjal') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {
                    if (resultado.estado){
                        Swal.fire(
                            'Exito',
                            'Se modifico con exito',
                            'success'
                        );
                        $('#modalEdicionOjal').modal('hide');
                        location.reload();
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

        function eliminarOjal(ojal){
            Swal.fire({
                title: "Esta seguro de eliminar el OJAL?",
                text: "Ya no podras revertir eso!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, eliminar!"
            }).then((result) => {
                if (result.isConfirmed) {
                    let datos ={dato:ojal};
                    $.ajax({
                    url: "{{ url('ordenTrabajo/eliminarOjal') }}",
                    method: "POST",
                    data: datos,
                    success: function(resultado) {
                        if (resultado.estado){
                            Swal.fire(
                                'Exito',
                                'Se elimino con exito',
                                'success'
                            );
                            $('#modalListadoOjales').modal('hide');
                        }
                    }
                    })
                }
            });
        }

        function modalNuevoOjal(){
            $('#new_ojal_cantidad').val(0);
            $('#new_ojal_precio').val(0);
            $('#new_ojal_subtotal').val(0);

            $('#modalListadoOjales').modal('hide');
            $('#modalNuevoOjal').modal('show');
        }

        function guardarNuevonOjal(){
            let datos = $('#formularioNuevoOjal').serializeArray();
            $.ajax({
                url: "{{ route('ordenTrabajo.guardarNuevonOjal') }}",
                method: "POST",
                data: datos,
                success: function(resultado) {
                    if (resultado.estado){
                        Swal.fire(
                            'Exito',
                            'Se modifico con exito',
                            'success'
                        );
                        $('#modalNuevoOjal').modal('hide');
                        location.reload();
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


        /*
        |--------------------------------------------------------------------------
        | ABRIR MODAL
        |--------------------------------------------------------------------------
        */

        function modalFacturaReceta(orderTrabajoId) {

            $('#fr_order_trabajo_id')
                .val(
                    orderTrabajoId
                );


            $('#fr_factura_receta_id')
                .val(0);

            $('#fr_receta_id')
                .val('');


            $('#fr_bloque_editor')
                .hide();

            $('#fr_boton_guardar')
                .hide();

            $('#fr_boton_eliminar')
                .hide();

            $('#fr_receta_asociada_texto')
                .addClass('d-none')
                .html('');


            $.ajax({

                url:
                    "{{ route('facturaReceta.datosModal') }}",

                method:
                    "POST",

                data: {

                    factura_id:
                        {{ $factura->id }},

                    order_trabajo_id:
                        orderTrabajoId
                },

                success: function(resultado) {

                if (!resultado.estado) {

                Swal.fire(
                'Error',
                resultado.message,
                'error'
                );

                return;
                }


                let data =
                resultado.data;


                /*
                |--------------------------------------------------------------------------
                | CATÁLOGOS
                |--------------------------------------------------------------------------
                */

                frTipoTelas =
                data.tipoTelas ?? [];

                frColorTelas =
                data.colorTelas ?? [];

                frNombreTelas =
                data.nombreTelas ?? [];

                frPrelavados =
                data.prelavados ?? [];

                frFocalizados =
                data.focalizados ?? [];

                frNevados =
                data.nevados ?? [];

                frCaracteristicas =
                data.caracteristicas ?? [];

                frTipoProcesos =
                data.tipoProcesos ?? [];

                frProductos =
                data.productos ?? [];


                /*
                |--------------------------------------------------------------------------
                | ORDEN DE TRABAJO
                |--------------------------------------------------------------------------
                */

                frPesoOt =
                parseFloat(
                data.orderTrabajo?.peso
                ) || 0;


                /*
                * OT
                */

                $('#fr_numero_ot_texto')
                .text(
                data.orderTrabajo?.nro_ot
                ?? '-'
                );


                /*
                * Peso cabecera
                */

                $('#fr_peso_ot_texto')
                .text(
                frPesoOt.toFixed(2)
                );


                /*
                * Peso Kg
                */

                $('#fr_peso_kg')
                .val(
                frPesoOt.toFixed(2)
                );


                /*
                * Peso gramos
                */

                $('#fr_peso_gr')
                .val(
                (
                frPesoOt * 1000
                ).toFixed(2)
                );


                /*
                |--------------------------------------------------------------------------
                | CATÁLOGOS
                |--------------------------------------------------------------------------
                */

                cargarCatalogosFacturaReceta();


                /*
                |--------------------------------------------------------------------------
                | LISTADO RECETAS MAESTRAS
                |--------------------------------------------------------------------------
                */

                $('#fr_buscar_receta')
                .empty()
                .append(
                '<option value="">Seleccione una receta</option>'
                );


                $.each(
                data.recetas ?? [],
                function(index, receta) {

                $('#fr_buscar_receta')
                .append(
                $('<option>', {

                    value:
                    receta.id,

                    text:
                    receta.nombre
                    })
                    );
                    }
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | RECETA EXISTENTE DE ESTA OT
                    |--------------------------------------------------------------------------
                    */

                    if (
                    data.facturaReceta
                    ) {

                    cargarFacturaRecetaExistente(
                    data.facturaReceta
                    );
                    }


                    $('#modalFacturaReceta')
                    .modal('show');
                    }
            });
        }



        /*
        |--------------------------------------------------------------------------
        | LLENAR CATÁLOGOS
        |--------------------------------------------------------------------------
        */

        function cargarCatalogosFacturaReceta() {

            llenarSelectFacturaReceta(
                '#fr_tipo_tela_id',
                frTipoTelas
            );

            llenarSelectFacturaReceta(
                '#fr_color_tela_id',
                frColorTelas
            );

            llenarSelectFacturaReceta(
                '#fr_nombre_tela_id',
                frNombreTelas
            );

            llenarSelectFacturaReceta(
                '#fr_prelavado_id',
                frPrelavados
            );

            llenarSelectFacturaReceta(
                '#fr_focalizado_id',
                frFocalizados
            );

            llenarSelectFacturaReceta(
                '#fr_nevado_id',
                frNevados
            );

            llenarSelectFacturaReceta(
                '#fr_caracteristica_id',
                frCaracteristicas
            );

            llenarSelectFacturaReceta(
                '#fr_tipo_proceso_id',
                frTipoProcesos
            );
        }



        function llenarSelectFacturaReceta(
            selector,
            listado
        ) {

            let select =
                $(selector);


            select
                .empty()
                .append(
                    '<option value="">Seleccione</option>'
                );


            $.each(
                listado,
                function(index, item) {

                    select.append(

                        $('<option>', {

                            value:
                                item.id,

                            text:
                                item.nombre

                        })

                    );

                }
            );
        }



        /*
        |--------------------------------------------------------------------------
        | CARGAR RECETA MAESTRA
        |--------------------------------------------------------------------------
        */

        function cargarRecetaMaestra() {

            let receta_id =
                $('#fr_buscar_receta')
                .val();


            if (!receta_id) {

                Swal.fire(
                    'Atención',
                    'Seleccione una receta.',
                    'warning'
                );

                return;
            }


            /*
            * Importante:
            * esto todavía NO guarda en la BD.
            */
            Swal.fire({

                title:
                    '¿Cargar esta receta?',

                text:
                    'Se copiará al formulario y podrá modificarla antes de guardar.',

                icon:
                    'question',

                showCancelButton:
                    true,

                confirmButtonText:
                    'Sí, cargar',

                cancelButtonText:
                    'Cancelar'

            }).then((result) => {


                if (!result.isConfirmed) {

                    return;
                }


                $.ajax({

                    url:
                        "{{ route('facturaReceta.obtenerReceta') }}",

                    method:
                        "POST",

                    data: {

                        receta_id:
                            receta_id

                    },

                    success: function(resultado) {

                        if (!resultado.estado) {

                            Swal.fire(
                                'Error',
                                resultado.message,
                                'error'
                            );

                            return;
                        }


                        let receta =
                            resultado.data.receta;


                        cargarDatosRecetaEnEditor(
                            receta,
                            false
                        );


                        Swal.fire({

                            icon:
                                'success',

                            title:
                                'Receta copiada',

                            text:
                                'Ahora puede modificar los datos antes de guardarlos.',

                            timer:
                                1800,

                            showConfirmButton:
                                false
                        });
                    }

                });

            });
        }



        /*
        |--------------------------------------------------------------------------
        | CARGAR RECETA MAESTRA EN EDITOR
        |--------------------------------------------------------------------------
        */

        function cargarDatosRecetaEnEditor(
            receta,
            esFacturaReceta
        ) {

            $('#fr_bloque_editor')
                .show();

            $('#fr_boton_guardar')
                .show();


            /*
            * Si viene de receta maestra
            */
            if (!esFacturaReceta) {

                $('#fr_receta_id')
                    .val(
                        receta.id
                    );

                $('#fr_buscar_receta')
                    .val(
                        receta.id
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | CABECERA
            |--------------------------------------------------------------------------
            */

            $('#fr_nombre')
                .val(
                    receta.nombre ?? ''
                );

            $('#fr_tipo_tela_id')
                .val(
                    receta.tipo_tela_id ?? ''
                );

            $('#fr_color_tela_id')
                .val(
                    receta.color_tela_id ?? ''
                );

            $('#fr_nombre_tela_id')
                .val(
                    receta.nombre_tela_id ?? ''
                );

            $('#fr_prelavado_id')
                .val(
                    receta.prelavado_id ?? ''
                );

            $('#fr_focalizado_id')
                .val(
                    receta.focalizado_id ?? ''
                );

            $('#fr_nevado_id')
                .val(
                    receta.nevado_id ?? ''
                );

            $('#fr_caracteristica_id')
                .val(
                    receta.caracteristica_id ?? ''
                );

            $('#fr_tipo_proceso_id')
                .val(
                    receta.tipo_proceso_id ?? ''
                );

            $('#fr_descripcion')
                .val(
                    receta.descripcion ?? ''
                );


            /*
            |--------------------------------------------------------------------------
            | PROCESOS
            |--------------------------------------------------------------------------
            */

            let procesos =
                convertirDetallesAProcesos(
                    receta.detalles ?? []
                );


            renderProcesosFacturaReceta(
                procesos
            );
        }



        /*
        |--------------------------------------------------------------------------
        | CARGAR FACTURA RECETA YA GUARDADA
        |--------------------------------------------------------------------------
        */

        function cargarFacturaRecetaExistente(
            facturaReceta
        ) {

            $('#fr_factura_receta_id')
                .val(
                    facturaReceta.id
                );


            $('#fr_receta_id')
                .val(
                    facturaReceta.receta_id ?? ''
                );


            if (
                facturaReceta.receta_id
            ) {

                $('#fr_buscar_receta')
                    .val(
                        facturaReceta.receta_id
                    );

            }


            let nombrePlantilla =
                facturaReceta.receta
                ?
                facturaReceta.receta.nombre
                :
                'Sin receta maestra';


            $('#fr_receta_asociada_texto')
                .removeClass('d-none')
                .html(
                    '<strong>Receta asociada:</strong> '
                    +
                    escaparHtml(nombrePlantilla)
                    +
                    ' <br><small>Esta es una copia editable de la receta original.</small>'
                );


            $('#fr_boton_eliminar')
                .show();


            /*
            * Cargamos la COPIA,
            * no la receta original.
            */
            cargarDatosRecetaEnEditor(
                facturaReceta,
                true
            );
        }



        /*
        |--------------------------------------------------------------------------
        | CONVERTIR DETALLES A PROCESOS
        |--------------------------------------------------------------------------
        |
        | NO agrupamos únicamente por tipo_proceso_id.
        |
        | Usamos:
        |
        | orden_proceso + tipo_proceso_id
        |
        | Así puede existir:
        |
        | 1 Lavado
        | 2 Teñido
        | 3 Lavado
        |
        |--------------------------------------------------------------------------
        */

        function convertirDetallesAProcesos(
            detalles
        ) {

            let procesos = {};


            detalles
                .sort(function(a, b) {

                    let procesoA =
                        parseInt(
                            a.orden_proceso
                            ?? 999999
                        );

                    let procesoB =
                        parseInt(
                            b.orden_proceso
                            ?? 999999
                        );


                    if (
                        procesoA !==
                        procesoB
                    ) {

                        return (
                            procesoA -
                            procesoB
                        );
                    }


                    return (

                        parseInt(
                            a.orden_producto
                            ?? 999999
                        )

                        -

                        parseInt(
                            b.orden_producto
                            ?? 999999
                        )

                    );

                })
                .forEach(
                    function(detalle) {


                        let clave =
                            (
                                detalle.orden_proceso
                                ?? ''
                            )
                            +
                            '_'
                            +
                            (
                                detalle.tipo_proceso_id
                                ?? ''
                            );


                        if (
                            !procesos[clave]
                        ) {

                            procesos[clave] = {

                                orden_proceso:
                                    detalle.orden_proceso,

                                tipo_proceso_id:
                                    detalle.tipo_proceso_id,

                                productos:
                                    []

                            };
                        }


                        procesos[
                            clave
                        ]
                        .productos
                        .push({

                            receta_detalle_id:
                                detalle.receta_detalle_id
                                ??
                                detalle.id
                                ??
                                null,

                            producto_id:
                                detalle.producto_id,

                            orden_producto:
                                detalle.orden_producto,

                            porcentaje:
                                detalle.porcentaje,

                            cantidad:
                                detalle.cantidad,

                            total:
                                detalle.total,

                            tiempo:
                                detalle.tiempo,

                            temperatura:
                                detalle.temperatura,

                            ph:
                                detalle.ph,

                            rb:
                                detalle.rb,

                            descripcion:
                                detalle.descripcion
                        });

                    }
                );


            return Object.values(
                procesos
            )
            .sort(
                function(a, b) {

                    return (

                        parseInt(
                            a.orden_proceso
                            ?? 999999
                        )

                        -

                        parseInt(
                            b.orden_proceso
                            ?? 999999
                        )

                    );

                }
            );
        }



        /*
        |--------------------------------------------------------------------------
        | RENDERIZAR TODOS LOS PROCESOS
        |--------------------------------------------------------------------------
        */

        function renderProcesosFacturaReceta(
            procesos
        ) {

            $('#fr_procesos')
                .empty();


            frProcesoContador =
                0;


            $.each(
                procesos,
                function(index, proceso) {

                    agregarProcesoFacturaReceta(
                        proceso
                    );

                }
            );


            /*
            * Si la receta está vacía,
            * damos un proceso inicial.
            */
            if (
                procesos.length === 0
            ) {

                agregarProcesoFacturaReceta();
            }
        }



        /*
        |--------------------------------------------------------------------------
        | AGREGAR PROCESO
        |--------------------------------------------------------------------------
        */

        function agregarProcesoFacturaReceta(
            proceso = null
        ) {

            frProcesoContador++;


            let procesoId =
                frProcesoContador;


            let ordenProceso =
                proceso
                ?
                (
                    proceso.orden_proceso
                    ?? procesoId
                )
                :
                procesoId;


            let tipoProcesoId =
                proceso
                ?
                (
                    proceso.tipo_proceso_id
                    ?? ''
                )
                :
                '';


            let html = `

                <div
                    class="card border mb-5 fr-proceso"
                    data-proceso="${procesoId}">

                    <div class="card-header bg-light">

                        <div
                            class="card-title
                                w-100">

                            <div
                                class="row
                                    w-100
                                    align-items-end">


                                <div class="col-md-2">

                                    <label class="form-label">
                                        Orden
                                    </label>

                                    <input
                                        type="number"
                                        min="1"
                                        class="form-control
                                            form-control-sm
                                            fr-orden-proceso"
                                        value="${escaparHtml(ordenProceso)}">

                                </div>


                                <div class="col-md-7">

                                    <label class="form-label">
                                        Proceso
                                    </label>

                                    <select
                                        class="form-select
                                            form-select-sm
                                            fr-tipo-proceso">

                                        ${opcionesTipoProceso(tipoProcesoId)}

                                    </select>

                                </div>


                                <div class="col-md-3">

                                    <button
                                        type="button"
                                        class="btn
                                            btn-danger
                                            btn-sm
                                            w-100"
                                        onclick="eliminarProcesoFacturaReceta(this)">

                                        <i class="fa fa-trash"></i>

                                        Quitar Proceso

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>


                    <div class="card-body">

                        <div
                            class="fr-productos">

                        </div>


                        <button
                            type="button"
                            class="btn
                                btn-primary
                                btn-sm
                                mt-3"
                            onclick="agregarProductoFacturaReceta(this)">

                            <i class="fa fa-plus"></i>

                            Agregar Producto

                        </button>

                    </div>

                </div>
            `;


            $('#fr_procesos')
                .append(
                    html
                );


            let bloque =
                $('#fr_procesos')
                .find(
                    `[data-proceso="${procesoId}"]`
                );


            if (
                proceso
                &&
                proceso.productos
            ) {


                $.each(
                    proceso.productos,
                    function(index, producto) {

                        agregarProductoFacturaReceta(
                            bloque
                                .find(
                                    '.fr-productos'
                                ),
                            producto
                        );

                    }
                );


            } else {


                agregarProductoFacturaReceta(
                    bloque
                        .find(
                            '.fr-productos'
                        )
                );

            }
        }



        /*
        |--------------------------------------------------------------------------
        | ELIMINAR PROCESO
        |--------------------------------------------------------------------------
        */

        function eliminarProcesoFacturaReceta(
            boton
        ) {

            $(boton)
                .closest(
                    '.fr-proceso'
                )
                .remove();
        }



        /*
        |--------------------------------------------------------------------------
        | OPCIONES PROCESOS
        |--------------------------------------------------------------------------
        */

        function opcionesTipoProceso(
            seleccionado = ''
        ) {

            let html =
                '<option value="">Seleccione</option>';


            $.each(
                frTipoProcesos,
                function(index, item) {


                    let selected =
                        String(item.id)
                        ===
                        String(seleccionado)
                        ?
                        'selected'
                        :
                        '';


                    html += `

                        <option
                            value="${item.id}"
                            ${selected}>

                            ${escaparHtml(item.nombre)}

                        </option>
                    `;

                }
            );


            return html;
        }



        /*
        |--------------------------------------------------------------------------
        | OPCIONES PRODUCTOS
        |--------------------------------------------------------------------------
        */

        function opcionesProductos(
            seleccionado = ''
        ) {

            let html =
                '<option value="">Seleccione</option>';


            $.each(
                frProductos,
                function(index, item) {


                    let selected =
                        String(item.id)
                        ===
                        String(seleccionado)
                        ?
                        'selected'
                        :
                        '';


                    html += `

                        <option
                            value="${item.id}"
                            ${selected}>

                            ${escaparHtml(item.nombre)}

                        </option>
                    `;

                }
            );


            return html;
        }



        /*
        |--------------------------------------------------------------------------
        | AGREGAR PRODUCTO
        |--------------------------------------------------------------------------
        */

        function agregarProductoFacturaReceta(
            elemento,
            producto = null
        ) {

            /*
            * Puede llegar:
            *
            * 1. el botón
            * 2. directamente el contenedor
            */

            let contenedor;


            if (
                $(elemento)
                .hasClass(
                    'fr-productos'
                )
            ) {

                contenedor =
                    $(elemento);

            } else {

                contenedor =
                    $(elemento)
                    .closest(
                        '.card-body'
                    )
                    .find(
                        '> .fr-productos'
                    );
            }


            let cantidadProductos =
                contenedor
                .find(
                    '.fr-producto'
                )
                .length;


            let ordenProducto =
                producto
                ?
                (
                    producto.orden_producto
                    ??
                    cantidadProductos + 1
                )
                :
                cantidadProductos + 1;


            let recetaDetalleId =
                producto
                ?
                (
                    producto.receta_detalle_id
                    ?? ''
                )
                :
                '';


            let productoId =
                producto
                ?
                (
                    producto.producto_id
                    ?? ''
                )
                :
                '';


            let porcentaje =
                producto
                ?
                (
                    producto.porcentaje
                    ?? ''
                )
                :
                '';


            let cantidad =
                producto
                ?
                (
                    producto.cantidad
                    ?? ''
                )
                :
                '';


            let total =
                producto
                ?
                (
                    producto.total
                    ?? ''
                )
                :
                '';


            let tiempo =
                producto
                ?
                (
                    producto.tiempo
                    ?? ''
                )
                :
                '';


            let temperatura =
                producto
                ?
                (
                    producto.temperatura
                    ?? ''
                )
                :
                '';


            let ph =
                producto
                ?
                (
                    producto.ph
                    ?? ''
                )
                :
                '';


            let rb =
                producto
                ?
                (
                    producto.rb
                    ?? ''
                )
                :
                '';


            let descripcion =
                producto
                ?
                (
                    producto.descripcion
                    ?? ''
                )
                :
                '';


            let html = `

                <div
                    class="border
                        rounded
                        p-4
                        mb-4
                        fr-producto">

                    <input
                        type="hidden"
                        class="fr-receta-detalle-id"
                        value="${escaparHtml(recetaDetalleId)}">


                    <div class="row align-items-end">


                        <div class="col-md-1">

                            <label class="form-label">
                                Orden
                            </label>

                            <input
                                type="number"
                                min="1"
                                class="form-control
                                    form-control-sm
                                    fr-orden-producto"
                                value="${escaparHtml(ordenProducto)}">

                        </div>


                        <div class="col-md-3">

                            <label class="form-label">
                                Producto
                            </label>

                            <select
                                class="form-select
                                    form-select-sm
                                    fr-producto-id">

                                ${opcionesProductos(productoId)}

                            </select>

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                %
                            </label>

                            <input
                                type="number"
                                step="0.00001"
                                class="form-control
                                    form-control-sm
                                    fr-porcentaje"
                                value="${escaparHtml(porcentaje)}">

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                Cantidad
                            </label>

                            <input
                                type="number"
                                step="0.00001"
                                class="form-control
                                    form-control-sm
                                    fr-cantidad"
                                value="${escaparHtml(cantidad)}">

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                Total
                            </label>

                            <input
                                type="number"
                                step="0.00001"
                                class="form-control
                                    form-control-sm
                                    fr-total"
                                value="${escaparHtml(total)}">

                        </div>


                        <div class="col-md-2">

                            <button
                                type="button"
                                class="btn
                                    btn-danger
                                    btn-sm
                                    w-100"
                                onclick="eliminarProductoFacturaReceta(this)">

                                <i class="fa fa-trash"></i>

                                Quitar

                            </button>

                        </div>

                    </div>


                    <div class="row mt-4">


                        <div class="col-md-2">

                            <label class="form-label">
                                Tiempo
                            </label>

                            <div class="input-group input-group-sm">

                                <input
                                    type="number"
                                    step="0.00001"
                                    class="form-control fr-tiempo"
                                    value="${escaparHtml(tiempo)}">

                                <span class="input-group-text">
                                    min
                                </span>

                            </div>

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                Temperatura
                            </label>

                            <div class="input-group input-group-sm">

                                <input
                                    type="number"
                                    step="0.00001"
                                    class="form-control fr-temperatura"
                                    value="${escaparHtml(temperatura)}">

                                <span class="input-group-text">
                                    °C
                                </span>

                            </div>

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                PH
                            </label>

                            <input
                                type="number"
                                step="0.00001"
                                class="form-control
                                    form-control-sm
                                    fr-ph"
                                value="${escaparHtml(ph)}">

                        </div>


                        <div class="col-md-2">

                            <label class="form-label">
                                RB
                            </label>

                            <input
                                type="number"
                                step="0.00001"
                                class="form-control
                                    form-control-sm
                                    fr-rb"
                                value="${escaparHtml(rb)}">

                        </div>


                        <div class="col-md-4">

                            <label class="form-label">
                                Descripción Técnica
                            </label>

                            <input
                                type="text"
                                class="form-control
                                    form-control-sm
                                    fr-descripcion-detalle"
                                value="${escaparHtml(descripcion)}">

                        </div>

                    </div>

                </div>
            `;


            contenedor.append(
                html
            );
        }



        /*
        |--------------------------------------------------------------------------
        | ELIMINAR PRODUCTO
        |--------------------------------------------------------------------------
        */

        function eliminarProductoFacturaReceta(
            boton
        ) {

            $(boton)
                .closest(
                    '.fr-producto'
                )
                .remove();
        }



        /*
        |--------------------------------------------------------------------------
        | CONSTRUIR PROCESOS PARA GUARDAR
        |--------------------------------------------------------------------------
        */

        function obtenerProcesosFacturaReceta() {

            let procesos = [];


            $('#fr_procesos')
                .find(
                    '> .fr-proceso'
                )
                .each(
                    function() {


                        let procesoRow =
                            $(this);


                        let proceso = {

                            orden_proceso:
                                procesoRow
                                .find(
                                    '.fr-orden-proceso'
                                )
                                .val(),

                            tipo_proceso_id:
                                procesoRow
                                .find(
                                    '.fr-tipo-proceso'
                                )
                                .val(),

                            productos:
                                []
                        };


                        procesoRow
                            .find(
                                '.fr-productos > .fr-producto'
                            )
                            .each(
                                function() {


                                    let productoRow =
                                        $(this);


                                    proceso
                                        .productos
                                        .push({

                                            receta_detalle_id:
                                                productoRow
                                                .find(
                                                    '.fr-receta-detalle-id'
                                                )
                                                .val(),

                                            orden_producto:
                                                productoRow
                                                .find(
                                                    '.fr-orden-producto'
                                                )
                                                .val(),

                                            producto_id:
                                                productoRow
                                                .find(
                                                    '.fr-producto-id'
                                                )
                                                .val(),

                                            porcentaje:
                                                productoRow
                                                .find(
                                                    '.fr-porcentaje'
                                                )
                                                .val(),

                                            cantidad:
                                                productoRow
                                                .find(
                                                    '.fr-cantidad'
                                                )
                                                .val(),

                                            total:
                                                productoRow
                                                .find(
                                                    '.fr-total'
                                                )
                                                .val(),

                                            tiempo:
                                                productoRow
                                                .find(
                                                    '.fr-tiempo'
                                                )
                                                .val(),

                                            temperatura:
                                                productoRow
                                                .find(
                                                    '.fr-temperatura'
                                                )
                                                .val(),

                                            ph:
                                                productoRow
                                                .find(
                                                    '.fr-ph'
                                                )
                                                .val(),

                                            rb:
                                                productoRow
                                                .find(
                                                    '.fr-rb'
                                                )
                                                .val(),

                                            descripcion:
                                                productoRow
                                                .find(
                                                    '.fr-descripcion-detalle'
                                                )
                                                .val()
                                        });

                                }
                            );


                        procesos.push(
                            proceso
                        );

                    }
                );


            return procesos;
        }



        /*
        |--------------------------------------------------------------------------
        | GUARDAR
        |--------------------------------------------------------------------------
        */

        function guardarFacturaReceta() {

            let procesos =
                obtenerProcesosFacturaReceta();


            if (
                procesos.length === 0
            ) {

                Swal.fire(
                    'Atención',
                    'Debe registrar al menos un proceso.',
                    'warning'
                );

                return;
            }


            /*
            * Validación básica
            */
            let error =
                false;


            $.each(
                procesos,
                function(index, proceso) {

                    if (
                        !proceso.tipo_proceso_id
                    ) {

                        error =
                            true;

                        return false;
                    }


                    $.each(
                        proceso.productos,
                        function(indexProducto, producto) {

                            if (
                                !producto.producto_id
                            ) {

                                error =
                                    true;

                                return false;
                            }

                        }
                    );

                }
            );


            if (error) {

                Swal.fire(
                    'Atención',
                    'Todos los procesos y productos deben estar seleccionados.',
                    'warning'
                );

                return;
            }


            let datos = {

                factura_id:
                    $('#fr_factura_id')
                    .val(),

                factura_receta_id:
                    $('#fr_factura_receta_id')
                    .val(),

                receta_id:
                    $('#fr_receta_id')
                    .val(),

                nombre:
                    $('#fr_nombre')
                    .val(),

                tipo_tela_id:
                    $('#fr_tipo_tela_id')
                    .val(),

                color_tela_id:
                    $('#fr_color_tela_id')
                    .val(),

                nombre_tela_id:
                    $('#fr_nombre_tela_id')
                    .val(),

                prelavado_id:
                    $('#fr_prelavado_id')
                    .val(),

                focalizado_id:
                    $('#fr_focalizado_id')
                    .val(),

                nevado_id:
                    $('#fr_nevado_id')
                    .val(),

                caracteristica_id:
                    $('#fr_caracteristica_id')
                    .val(),

                tipo_proceso_id:
                    $('#fr_tipo_proceso_id')
                    .val(),

                descripcion:
                    $('#fr_descripcion')
                    .val(),

                procesos:
                    procesos,
                order_trabajo_id:$('#fr_order_trabajo_id').val(),
            };


            $('#fr_boton_guardar')
                .prop(
                    'disabled',
                    true
                );


            $.ajax({

                url:
                    "{{ route('facturaReceta.guardar') }}",

                method:
                    "POST",

                data:
                    datos,

                success: function(resultado) {


                    $('#fr_boton_guardar')
                        .prop(
                            'disabled',
                            false
                        );


                    if (
                        resultado.estado
                    ) {


                        $('#fr_factura_receta_id')
                            .val(
                                resultado
                                .data
                                .factura_receta_id
                            );


                        $('#fr_boton_eliminar')
                            .show();


                        Swal.fire({

                            icon:
                                'success',

                            title:
                                'Guardado',

                            text:
                                'La receta del lavado fue guardada correctamente.',

                            timer:
                                2000,

                            showConfirmButton:
                                false
                        });


                        /*
                        * Recargamos los datos
                        * para que quede exactamente
                        * como está en BD.
                        */
                        recargarFacturaRecetaModal();


                    } else {


                        Swal.fire(
                            'Error',
                            resultado.message
                            ??
                            'No se pudo guardar.',
                            'error'
                        );
                    }

                },


                error: function(xhr) {


                    $('#fr_boton_guardar')
                        .prop(
                            'disabled',
                            false
                        );


                    Swal.fire(
                        'Error',
                        'Ocurrió un error al guardar.',
                        'error'
                    );
                }

            });
        }



        /*
        |--------------------------------------------------------------------------
        | RECARGAR MODAL DESPUÉS DE GUARDAR
        |--------------------------------------------------------------------------
        */

        function recargarFacturaRecetaModal() {

            $.ajax({

                url:
                    "{{ route('facturaReceta.datosModal') }}",

                method:
                    "POST",

                data: {

                    factura_id:
                        "{{ $factura->id }}"

                },

                success: function(resultado) {


                    if (
                        resultado.estado
                        &&
                        resultado.data.facturaReceta
                    ) {

                        cargarFacturaRecetaExistente(
                            resultado.data.facturaReceta
                        );
                    }

                }

            });
        }



        /*
        |--------------------------------------------------------------------------
        | ELIMINAR RECETA DE LA FACTURA
        |--------------------------------------------------------------------------
        */

        function eliminarFacturaReceta() {

            let facturaRecetaId =
                $('#fr_factura_receta_id')
                .val();


            if (
                !facturaRecetaId
                ||
                facturaRecetaId == 0
            ) {

                return;
            }


            Swal.fire({

                title:
                    '¿Eliminar receta del lavado?',

                text:
                    'Se eliminará la copia asociada a esta factura. La receta maestra no será eliminada.',

                icon:
                    'warning',

                showCancelButton:
                    true,

                confirmButtonColor:
                    '#d33',

                confirmButtonText:
                    'Sí, eliminar',

                cancelButtonText:
                    'Cancelar'

            }).then(
                function(result) {


                    if (
                        !result.isConfirmed
                    ) {

                        return;
                    }


                    $.ajax({

                        url:
                            "{{ route('facturaReceta.eliminar') }}",

                        method:
                            "POST",

                        data: {

                            factura_receta_id:
                                facturaRecetaId,

                            factura_id:
                                "{{ $factura->id }}"
                        },


                        success: function(resultado) {


                            if (
                                resultado.estado
                            ) {


                                Swal.fire(
                                    'Eliminado',
                                    'La receta asociada fue eliminada correctamente.',
                                    'success'
                                );


                                $('#fr_factura_receta_id')
                                    .val(0);

                                $('#fr_receta_id')
                                    .val('');

                                $('#fr_buscar_receta')
                                    .val('');

                                $('#fr_procesos')
                                    .empty();

                                $('#fr_bloque_editor')
                                    .hide();

                                $('#fr_boton_guardar')
                                    .hide();

                                $('#fr_boton_eliminar')
                                    .hide();

                                $('#fr_receta_asociada_texto')
                                    .addClass(
                                        'd-none'
                                    )
                                    .html('');


                            } else {


                                Swal.fire(
                                    'Error',
                                    resultado.message,
                                    'error'
                                );
                            }

                        }

                    });

                }
            );
        }



        /*
        |--------------------------------------------------------------------------
        | ESCAPAR HTML
        |--------------------------------------------------------------------------
        */

        function escaparHtml(
            valor
        ) {

            if (
                valor === null
                ||
                valor === undefined
            ) {

                return '';
            }


            return String(
                valor
            )
            .replace(
                /&/g,
                '&amp;'
            )
            .replace(
                /</g,
                '&lt;'
            )
            .replace(
                />/g,
                '&gt;'
            )
            .replace(
                /"/g,
                '&quot;'
            )
            .replace(
                /'/g,
                '&#039;'
            );
        }

        function obtenerPesoGramosFacturaReceta() {
            let pesoKg = parseFloat($('#fr_peso_ot_texto').text()) || 0;
            return pesoKg * 1000;
        }

        function recalcularCantidadesPorPesoOt() {

            let pesoGr =
                frPesoOt * 1000;


            if (pesoGr <= 0) {

                return;
            }


            $('#fr_procesos').find('.fr-producto').each(function () {

                let fila =$(this);

                let porcentaje =
                    parseFloat(
                        fila
                            .find('.fr-porcentaje')
                            .val()
                    ) || 0;


                let cantidad =
                    (
                        pesoGr *
                        porcentaje
                    )
                    /
                    100;


                fila
                    .find('.fr-cantidad')
                    .val(
                        cantidad.toFixed(2)
                    );


                calcularTotalFacturaReceta(
                    fila
                );
            });
        }



        function calcularTotalFacturaReceta(fila) {

            /*
            |--------------------------------------------------------------------------
            | PRODUCTO SELECCIONADO
            |--------------------------------------------------------------------------
            */

            let productoId =
                fila
                    .find('.fr-producto-id')
                    .val();


            if (!productoId) {

                fila
                    .find('.fr-total')
                    .val('0.00');

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | BUSCAMOS PRODUCTO EN frProductos
            |--------------------------------------------------------------------------
            */

            let producto =
                frProductos.find(
                    function(item) {

                        return String(item.id)
                            ===
                            String(productoId);
                    }
                );


            if (!producto) {

                fila
                    .find('.fr-total')
                    .val('0.00');

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | OBTENEMOS ÚLTIMO INGRESO
            |--------------------------------------------------------------------------
            |
            | Laravel al convertir a JSON normalmente manda:
            |
            | ultimo_ingreso
            |
            */

            let ingreso =
                producto.ultimo_ingreso
                ??
                producto.ultimoIngreso
                ??
                null;


            if (!ingreso) {

                fila
                    .find('.fr-total')
                    .val('0.00');

                return;
            }


            /*
            |--------------------------------------------------------------------------
            | PRECIO COMPRA POR GRAMO
            |--------------------------------------------------------------------------
            */

            let precioCompraGr =
                parseFloat(
                    ingreso.precio_compra_g
                ) || 0;


            /*
            |--------------------------------------------------------------------------
            | CANTIDAD
            |--------------------------------------------------------------------------
            */

            let cantidad =
                parseFloat(
                    fila
                        .find('.fr-cantidad')
                        .val()
                ) || 0;


            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            let total =
                cantidad *
                precioCompraGr;


            fila
                .find('.fr-total')
                .val(
                    total.toFixed(2)
                );
        }


   </script>
@endsection
