@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('metadatos')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')

    <!--end::Modal - New Card-->
    {{-- <div class="modal fade" id="modal_new_servicio" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-1000px">
            <div class="modal-content">
                @include('empresa.components.modalAgregaServicioProducto')
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div> --}}
    <!--end::Modal - New Card-->

    <!--end::Modal - New Card-->
    {{-- <div class="modal fade" id="modal_new_cliente" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-900px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">Formulario de Cliente</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formulario_new_cliente_empresa">
                        <div class="row">
                            <div class="col-md-3">
                                <label class="fs-6 fw-semibold form-label mb-2 required">Nombres</label>
                                <input type="text" class="form-control fw-bold form-control-solid"
                                    name="nombres_cliente_new_usuaio_empresa" id="nombres_cliente_new_usuaio_empresa"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="fs-6 fw-semibold form-label mb-2 required">Ap Paterno</label>
                                <input type="text" class="form-control fw-bold form-control-solid"
                                    name="ap_paterno_cliente_new_usuaio_empresa" id="ap_paterno_cliente_new_usuaio_empresa"
                                    required>
                            </div>
                            <div class="col-md-3">
                                <label class="fs-6 fw-semibold form-label mb-2">Ap Materno</label>
                                <input type="text" class="form-control fw-bold form-control-solid"
                                    name="ap_materno_cliente_new_usuaio_empresa" id="ap_materno_cliente_new_usuaio_empresa">
                            </div>
                            <div class="col-md-3">
                                <label class="fs-6 fw-semibold form-label mb-2">Numero de Celular</label>
                                <input type="number" class="form-control fw-bold form-control-solid"
                                    name="num_ceular_cliente_new_usuaio_empresa" id="num_ceular_cliente_new_usuaio_empresa">
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-2">
                                <label class="fs-6 fw-semibold form-label mb-2 required">Cedula</label>
                                <input type="number" class="form-control fw-bold form-control-solid"
                                    name="cedula_cliente_new_usuaio_empresa" id="cedula_cliente_new_usuaio_empresa"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <label class="fs-6 fw-semibold form-label mb-2">Complemento</label>
                                <input type="number" class="form-control fw-bold form-control-solid"
                                    name="complemento_cliente_new_usuaio_empresa"
                                    id="complemento_cliente_new_usuaio_empresa">
                            </div>
                            <div class="col-md-2">
                                <label class="fs-6 fw-semibold form-label mb-2">Nit</label>
                                <input type="number" class="form-control fw-bold form-control-solid"
                                    name="nit_cliente_new_usuaio_empresa" id="nit_cliente_new_usuaio_empresa">
                            </div>
                            <div class="col-md-3">
                                <label class="fs-6 fw-semibold form-label mb-2">Razon Social</label>
                                <input type="text" class="form-control fw-bold form-control-solid"
                                    name="razon_social_cliente_new_usuaio_empresa"
                                    id="razon_social_cliente_new_usuaio_empresa">
                            </div>
                            <div class="col-md-3">
                                <label class="fs-6 fw-semibold form-label mb-2">Correo</label>
                                <input type="text" class="form-control fw-bold form-control-solid"
                                    name="correo_cliente_new_usuaio_empresa" id="correo_cliente_new_usuaio_empresa">
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success w-100 btn-sm"
                                    onclick="guardarClienteEmpresa()">Agregar Usuario</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div> --}}
    <!--end::Modal - New Card-->

    <!--begin::Modal - Add task-->
    {{-- <div class="modal fade" id="modalAperturaCaja" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            @include('caja.components.formularioAperturaCaja')
        </div>
        <!--end::Modal dialog-->
    </div> --}}
    <!--end::Modal - Add task-->


    <!--begin::Modal - Add task-->
    {{-- <div class="modal fade" id="modalCerrarCaja" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            @include('caja.components.formularioCerrarCaja', ['cajaAbierta' => $cajaAbierta])
        </div>
        <!--end::Modal dialog-->
    </div> --}}
    <!--end::Modal - Add task-->


    {{-- <!--end::Modal - New Card-->
    <div class="modal fade" id="modal_lista_accesorios" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-600px">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold">AGREGAR ACCESORIOS</h2>
                    <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>
                    </div>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formulario_new_cliente_empresa">
                        <div class="row">
                            <div class="col-md-12">
                                <label class="fs-6 fw-semibold form-label mb-2 required">PRODUCTO</label>
                                <input type="text" class="form-control fw-bold form-control-solid"
                                    name="nombre_producto_agregar" id="nombre_producto_agregar" readonly>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <div id="tabla_accesorios"></div>
                            </div>
                        </div>
                        <div class="row mt-5">
                            <div class="col-md-12">
                                <button type="button" class="btn btn-success w-100 btn-sm"
                                    onclick="guardarClienteEmpresa()">Agregar Usuario</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <!--end::Modal content-->
        </div>
        <!--end::Modal dialog-->
    </div>
    <!--end::Modal - New Card--> --}}



    <div class="d-flex flex-column flex-column-fluid">
        <!--begin::Content-->
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <!--begin::Content container-->
            <div id="kt_app_content_container" class="app-container container-xxlg">
                <!--begin::Card-->
                <div class="card">
                    <div class="card-body py-4">
                        <div class="row">
                            <div class="col-md-12">
                                <h1
                                    class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">
                                    Formulario de Recepcion</h1>
                            </div>
                            {{-- <div class="col-md-2">
                                <a class="btn btn-sm fw-bold btn-danger w-100" onclick="modalCerrarCaja()"><i
                                        class="fa fa-plus"></i>Cerrer Caja</a>
                            </div> --}}
                        </div>
                        <hr>
                        <form id="formulario_recepcion">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <img id="imagen_cliente" src="{{ asset('assets/img/default.jpg') }}"
                                                alt="Imagen del cliente" width="100%"
                                                style="object-fit:cover; border-radius:10px; border:1px solid #ccc;">
                                        </div>
                                        <div class="col-md-8">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <label class="fs-6 fw-semibold form-label mb-2 required">Nombres</label>
                                                    <select name="cliente_seleccionado" id="cliente_seleccionado"
                                                        class="form-select form-select-sm" onchange="seleccionarCliente()">
                                                        <option value="">SELECCIONE EL CLIENTE</option>
                                                        @foreach ($clientes as $cliente)
                                                            <option value="{{ $cliente }}">
                                                                {{ $cliente->nombres . ' ' . $cliente->ap_paterno . ' ' . $cliente->ap_materno }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input type="hidden" name="cliente_seleccionado_id"
                                                        id="cliente_seleccionado_id">
                                                </div>
                                                <div class="col-md-12">
                                                    <label
                                                        class="fs-6 fw-semibold form-label mb-2 required">Telefono</label>
                                                    <input type="text" class="form-control fw-bold form-control-solid"
                                                        name="telefono_cliente" id="telefono_cliente">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fs-6 fw-semibold form-label mb-2">Direccion</label>
                                                    <input type="text" class="form-control fw-bold form-control-solid"
                                                        name="direccion_cliente" id="direccion_cliente">
                                                </div>
                                                <div class="col-md-12">
                                                    <label class="fs-6 fw-semibold form-label mb-2">Prioridad</label>
                                                    <select class="form-select form-select-sm" name="prioridad_cliente"
                                                        id="prioridad_cliente">
                                                        <option value="NORMAL">NORMAL</option>
                                                        <option value="PARA LA FERIA">PARA LA FERIA</option>
                                                        <option value="URGENTE">URGENTE</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label class="fs-6 fw-semibold form-label mb-2 required">Fecha de
                                                Recepcion</label>
                                            <input type="date" class="form-control fw-bold form-control-solid"
                                                name="fecha_recepcion_cliente" id="fecha_recepcion_cliente">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="fs-6 fw-semibold form-label mb-2">Entregado Por</label>
                                            <input type="text" class="form-control fw-bold form-control-solid"
                                                name="entregado_por" id="entregado_por">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="fs-6 fw-semibold form-label mb-2">Recibido por</label>
                                            <input type="text" class="form-control fw-bold form-control-solid"
                                                name="recibido_por" id="recibido_por"
                                                value="{{ $usuario->nombres . ' ' . $usuario->ap_paterno . ' ' . $usuario->ap_materno }}"
                                                readonly>
                                            <input type="hidden" name="usuario_recepciono_id" id="usuario_recepciono_id"
                                                value="{{ $usuario->id }}">
                                        </div>
                                        <div class="col-md-12">
                                            <label class="fs-6 fw-semibold form-label mb-2">Fecha Registro</label>
                                            <input type="date" class="form-control fw-bold form-control-solid"
                                                value="{{ date('Y-m-d') }}" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <div class="row">
                            <div class="col-md-12">

                                <!--begin::Repeater-->
                                {{-- <div id="kt_docs_repeater_advanced">
                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <div data-repeater-list="kt_docs_repeater_advanced">
                                            <div data-repeater-item>
                                                <div class="form-group row mb-5">
                                                    <div class="col-md-3">
                                                        <label class="form-label">Select Options:</label>
                                                        <select class="form-select" data-kt-repeater="select2"
                                                            data-placeholder="Select an option">
                                                            <option></option>
                                                            <option value="1">Option 1</option>
                                                            <option value="2">Option 2</option>
                                                            <option value="3">Option 3</option>
                                                        </select>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <label class="form-label">Datepicker:</label>
                                                        <input class="form-control" data-kt-repeater="datepicker"
                                                            placeholder="Pick a date" />
                                                    </div>
                                                    <div class="col-md-4">
                                                        <label class="form-label">Tags:</label>
                                                        <input class="form-control" data-kt-repeater="tagify"
                                                            value="tag1, tag2, tag3" />
                                                    </div>
                                                    <div class="col-md-2">
                                                        <a href="javascript:;" data-repeater-delete
                                                            class="btn btn-flex btn-sm btn-light-danger mt-3 mt-md-9">
                                                            <i class="ki-duotone ki-trash fs-3"><span
                                                                    class="path1"></span><span
                                                                    class="path2"></span><span
                                                                    class="path3"></span><span
                                                                    class="path4"></span><span class="path5"></span></i>
                                                            Delete
                                                        </a>
                                                    </div>
                                                </div>
                                                <tablec class="table-response">
                                                    <thead>
                                                        <tr>
                                                            <th>Cantidad</th>
                                                            <th>Prenda</th>
                                                            <th>Ojales</th>
                                                            <th>Tela</th>
                                                            <th>Pre Lavado</th>
                                                            <th>Nevado</th>
                                                            <th>Focalizado</th>
                                                            <th>T. Tela</th>
                                                            <th>Co. Tela</th>
                                                            <th>Ca. Tela</th>
                                                            <th>Peso</th>
                                                            <th>Precio</th>
                                                            <th>Sub Total</th>
                                                            <th>Obs</th>
                                                            <th>OT</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                            <td><input type="text"></td>
                                                        </tr>
                                                    </tbody>
                                                    </table>
                                            </div>
                                        </div>
                                    </div>
                                    <!--end::Form group-->

                                    <!--begin::Form group-->
                                    <div class="form-group">
                                        <a href="javascript:;" data-repeater-create
                                            class="btn btn-flex btn-light-primary">
                                            <i class="ki-duotone ki-plus fs-3"></i>
                                            Agregar OT
                                        </a>
                                    </div>
                                    <!--end::Form group-->
                                </div> --}}
                                <!--end::Repeater-->

                            </div>
                        </div>
                        {{-- <div class="row">
                            <div class="col-md-12 text-center">
                                @if ($verificacionSiat->estado === 'success')
                                    <div class="row">
                                        <div class="col-md-6 text-center">
                                            <span
                                                class="badge bg-success text-white w-100">{{ $verificacionSiat->resultado->RespuestaComunicacion->mensajesList->descripcion }}</span>
                                        </div>
                                        <div class="col-md-3">
                                            @if ($cuis)
                                                CUIS: {{ $cuis->codigo }}
                                            @else
                                                <span class="badge badge-danger">NO existe un Cuis Vigente para este
                                                    Usuario</span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            @if ($cufd)
                                                CUFD: {{ $cufd->codigo_control . ' ' . $cufd->fecha_vigencia }}
                                            @else
                                                <span class="badge badge-danger">NO existe un Cufd Vigente para este
                                                    Usuario</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-danger text-white w-100">NO HAY CONECCION CON SIAT |
                                        {{ json_encode($verificacionSiat->msg) }}</span>
                                @endif
                            </div>
                        </div> --}}
                        <div id="tabla_clientes">
                        </div>
                        <hr>
                        <div id="tabla_ventas">
                            <form id="formulario_venta">
                                <div class="row">
                                    <div class="col-md-1">
                                        <label class="required fw-semibold fs-6 mb-2">Cantidad</label>
                                        <input type="number" class="form-control form-control-sm" id="cantidad_venta"
                                            name="cantidad_venta" required min="1">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="required fw-semibold fs-6 mb-2">Prenda</label>
                                        <select class="form-select form-select-sm" name="prenda_id" id="prenda_id"
                                            data-placeholder="SELECIONE" required>
                                            <option></option>
                                            @foreach ($prendas as $prenda)
                                                <option value="{{ $prenda->id }}">{{ $prenda->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-1">
                                        <label class="required fw-semibold fs-6 mb-2">Ojales</label>
                                        <input type="number" class="form-control form-control-sm" id="numero_ojales"
                                            name="numero_ojales" required min="1">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="required fw-semibold fs-6 mb-2">Tela</label>
                                        <select class="form-select form-select-sm" name="tela_id" id="tela_id"
                                            data-placeholder="SELECIONE" required>
                                            <option></option>
                                            @foreach ($telas as $tela)
                                                <option value="{{ $tela->id }}">{{ $tela->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="required fw-semibold fs-6 mb-2">Pre Lavado</label>
                                        <select class="form-select form-select-sm" name="prelavado_id" id="prelavado_id"
                                            data-placeholder="SELECIONE" required>
                                            <option></option>
                                            @foreach ($prelavados as $prelavado)
                                                <option value="{{ $prelavado->id }}">{{ $prelavado->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="required fw-semibold fs-6 mb-2">Nevado</label>
                                        <select class="form-select form-select-sm" name="nevado_id" id="nevado_id"
                                            data-placeholder="SELECIONE" required>
                                            <option></option>
                                            @foreach ($nevados as $nevado)
                                                <option value="{{ $nevado->id }}">{{ $nevado->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="required fw-semibold fs-6 mb-2">Focalizado</label>
                                        <select class="form-select form-select-sm" name="focalizado_id"
                                            id="focalizado_id" data-placeholder="SELECIONE" required>
                                            <option></option>
                                            @foreach ($focalizados as $focalizado)
                                                <option value="{{ $focalizado->id }}">{{ $focalizado->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-11">
                                        <div class="row">
                                            <div class="col-md">
                                                <label class="required fw-semibold fs-6 mb-2">T. Tela</label>
                                                <select class="form-select form-select-sm" name="tipo_tela_id"
                                                    id="tipo_tela_id" data-placeholder="SELECIONE" required>
                                                    <option></option>
                                                    @foreach ($tipoTelas as $tipoTela)
                                                        <option value="{{ $tipoTela->id }}">{{ $tipoTela->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md">
                                                <label class="required fw-semibold fs-6 mb-2">Co. Tela</label>
                                                <select class="form-select form-select-sm" name="color_tela_id"
                                                    id="color_tela_id" data-placeholder="SELECIONE" required>
                                                    <option></option>
                                                    @foreach ($colorTelas as $colorTela)
                                                        <option value="{{ $colorTela->id }}">{{ $colorTela->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md">
                                                <label class="required fw-semibold fs-6 mb-2">Ca. Tela</label>
                                                <select class="form-select form-select-sm" name="caracteristica_tela_id"
                                                    id="caracteristica_tela_id" data-placeholder="SELECIONE" required>
                                                    <option></option>
                                                    @foreach ($caracteristicaTelas as $caracteristicaTela)
                                                        <option value="{{ $caracteristicaTela->id }}">
                                                            {{ $caracteristicaTela->nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md">
                                                <label class="required fw-semibold fs-6 mb-2">Peso</label>
                                                <input type="number" class="form-control form-control-sm" id="peso"
                                                    name="peso" required min="1">
                                            </div>

                                            <div class="col-md">
                                                <label class="required fw-semibold fs-6 mb-2">Precio</label>
                                                <input type="number" class="form-control form-control-sm"
                                                    id="precio_venta" name="precio_venta" required min="1">
                                            </div>

                                            <div class="col-md">
                                                <label class="required fw-semibold fs-6 mb-2">Sub Total</label>
                                                <input type="number" class="form-control form-control-sm" id="sub_total"
                                                    name="sub_total" required min="1">
                                            </div>

                                            <div class="col-md">
                                                <label class="required fw-semibold fs-6 mb-2">Obs</label>
                                                <input type="text" class="form-control form-control-sm"
                                                    id="observacion" name="observacion" required>
                                            </div>

                                            <div class="col-md">
                                                <label class="required fw-semibold fs-6 mb-2">N° OT</label>
                                                <input type="number" class="form-control form-control-sm" id="nro_ot"
                                                    name="nro_ot" required min="1">
                                            </div>
                                        </div>
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
                            </form>
                            <hr>
                            <div id="tabla_detalles" style="display: none;">
                                <h3 class="text-center">CARGAR DE ORDEN DE TRABAJOS</h2>
                                <div class="table-responsive" style="max-width: 100%; overflow-x: auto;">
                                    <table id="carrito" class="table align-middle table-row-dashed fs-6 gy-5">
                                        <thead>
                                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                                <th>Cantidad</th>
                                                <th>Prenda</th>
                                                <th>Ojales</th>
                                                <th>Tela</th>
                                                <th>Pre Lavado</th>
                                                <th>Nevado</th>
                                                <th>Focalizado</th>
                                                <th>T. Tela</th>
                                                <th>Co. Tela</th>
                                                <th>Ca. Tela</th>
                                                <th>Peso</th>
                                                <th>Precio</th>
                                                <th>Sub Total</th>
                                                <th>Obs</th>
                                                <th>OT</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <!-- Aquí se agregarán las filas del carrito -->
                                        </tbody>
                                        {{-- <tfoot>
                                            <tr>
                                                <th colspan="6">Descuento Adicional</th>
                                                <th colspan="3">Monto Total</th>
                                            </tr>
                                            <tr>
                                                <td colspan="6">
                                                    <input class="form-control form-control-sm" name="descuento_adicional"
                                                        id="descuento_adicional" type="number" value="0"
                                                        onchange="ejecutarDescuentoAdicional()">
                                                </td>
                                                <td colspan="3">
                                                    <input class="form-control form-control-sm" name="monto_total"
                                                        id="monto_total" type="number" readonly value="0">
                                                </td>
                                            </tr>
                                        </tfoot> --}}
                                    </table>
                                </div>

                                <div class="row" id="bloque_recibo">
                                    <div class="col-md-12 bg-light-success">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h2 class="text-center text-success">DATOS DE PAGO</h2>
                                            </div>
                                        </div>
                                        <form id="formularioGeneraRecibo">
                                            <div class="row">
                                                <div class="col-md-2">
                                                    <label class="required">Tipo Pago</label>
                                                    <select name="tipo_pago_pagado_recibo" id="tipo_pago_pagado_recibo"
                                                        class="form-control form-control-sm" onchange="validarCamposRecibo()">
                                                        <option value="">Seleccione</option>
                                                        <option value="EFECTIVO">EFECTIVO</option>
                                                        <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                                        <option value="QR">QR</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="required">Realizara algun Pago?</label>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label class="form-check form-check-custom form-check-solid me-3">
                                                            <input class="form-check-input h-20px w-20px" type="checkbox"
                                                                name="realizo_pago_recibo" value="pago"
                                                                id="realizo_pago_recibo" />
                                                            <span class="form-check-label fw-semibold">Realizo un
                                                                pago</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="required">Monto Venta</label>
                                                    <input type="number" class="form-control form-control-sm" readonly
                                                        id="monto_total_pagado_recibo" name="monto_total_pagado_recibo"
                                                        value="0">
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="required">Monto Pagado</label>
                                                    <input type="number" class="form-control form-control-sm"
                                                        id="monto_pagado_recibo" name="monto_pagado_recibo" value="0"
                                                        onkeyup="caluclarCambioRecibo(this)">
                                                </div>
                                                <div class="col-md-2">
                                                    <label class="required">Cambio</label>
                                                    <input type="number" class="form-control form-control-sm" readonly
                                                        id="cambio_pagado_recibo" name="cambio_pagado_recibo" value="0">
                                                </div>
                                            </div>
                                        </form>
                                        <div class="row mt-3">
                                            <div class="col-md-12">
                                                <button class="btn btn-sm w-100 btn-success" onclick="recepcionar()"
                                                    id="boton_enviar_recibo"> <i class="fa fa-spinner fa-spin"
                                                        style="display:none;"></i>RECEPCIONAR</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Card-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
    </div>


@stop()

@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        function seleccionarCliente() {
            let cliente = JSON.parse($('#cliente_seleccionado').val());

            $('#cliente_id_escogido').val(cliente.id);
            $('#cliente_seleccionado_id').val(cliente.id);
            $('#telefono_cliente').val(cliente.celular);
            $('#direccion_cliente').val(cliente.direccion);

            if (cliente.foto && cliente.foto !== '')
                $('#imagen_cliente').attr('src', window.location.origin + '/' + cliente.foto);
            // else
            //     $('#imagen_cliente').attr('src', '/images/sin-foto.png');

        }

        $('#kt_docs_repeater_advanced').repeater({
            initEmpty: false,

            defaultValues: {
                'text-input': 'foo'
            },

            show: function() {
                $(this).slideDown();

                // Re-init select2
                $(this).find('[data-kt-repeater="select2"]').select2();

                // Re-init flatpickr
                $(this).find('[data-kt-repeater="datepicker"]').flatpickr();

                // Re-init tagify
                new Tagify(this.querySelector('[data-kt-repeater="tagify"]'));
            },

            hide: function(deleteElement) {
                $(this).slideUp(deleteElement);
            },

            ready: function() {
                // Init select2
                $('[data-kt-repeater="select2"]').select2();

                // Init flatpickr
                $('[data-kt-repeater="datepicker"]').flatpickr();

                // Init Tagify
                new Tagify(document.querySelector('[data-kt-repeater="tagify"]'));
            }
        });

        var arrayProductos = [];
        // var arrayProductosAccesorios = {};
        // var arrayPagos = [];
        // var table;
        // var arrayProductoCar = [];

        var contadorTable = 0;

        $(document).ready(function() {

            $("#prenda_id, #tela_id, #prelavado_id, #nevado_id, #focalizado_id, #tipo_tela_id, #color_tela_id, #caracteristica_tela_id")
                .select2();

            // Inicializa el DataTable
            table = $('#carrito').DataTable({
                lengthMenu: [10, 25, 50, 100], // Opciones de longitud de página
                // dom: '<"dt-head row"<"col-md-6"l><"col-md-6"f>><"clear">t<"dt-footer row"<"col-md-5"i><"col-md-7"p>>', // Use dom for basic layout
                dom: '<"dt-head row"><"clear">t', // Use dom for basic layout
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
                responsive: true
            });


            let debounceTimer;
            $('.buscar-persona').on('keyup', function() {

                //ajaxListadoClientes();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function() {
                    ajaxListadoClientes();
                }, 300); // Espera 300 ms antes de ejecutar la función
            });


            $('input[name="uso_cafc"]').on('change', function() {
                verificarRadioSeleccionado();
            });

            $('#nombre_cliente').text('SELECCIONAR CLIENTE')

        });

        // function ajaxListadoServicios() {
        //     let datos = {}
        //     $.ajax({
        //         url: "{{ url('factura/ajaxListadoServicios') }}",
        //         method: "POST",
        //         data: datos,
        //         success: function(data) {
        //             if (data.estado === 'success') {
        //                 $('#tabla_clientes').html(data.listado)
        //             } else {

        //             }
        //         }
        //     })
        // }

        // function identificaSericio(selected) {

        //     if (selected.value != '') {
        //         var json = JSON.parse(selected.value);

        //         console.log(json);

        //         let cantidad_venta = 1;
        //         let precio_venta = json.precio_venta;
        //         let numero_serie = json.numero_serie;
        //         let stock = json.stock;

        //         $('#cantidad_venta').val(cantidad_venta);
        //         $('#precio_venta').val((cantidad_venta * precio_venta));
        //         $('#total_venta').val(precio_venta * cantidad_venta);
        //         $('#numero_serie').val(numero_serie);
        //         $('#stock_sucursal').val(stock);

        //         if (parseInt(stock) > 0) {
        //             $("#stock_sucursal").addClass("is-valid").removeClass("is-invalid");
        //             $('#boton-agrega-producto').attr('disabled', false)
        //         } else {
        //             $("#stock_sucursal").addClass("is-invalid").removeClass("is-valid");
        //             $('#boton-agrega-producto').attr('disabled', true)
        //         }

        //         //     $('#precio_venta').val(json.precio_venta)
        //         //     $('#cantidad_venta').val(1)
        //         //     $('#total_venta').val((1 * json.precio_venta))
        //         //     $('#numero_serie').val(json.numero_serie)
        //         //     $('#codigo_imei').val(json.codigo_imei)
        //         //     // $('#stock_producto').val(json.stock === null ? 0 : json.stock)
        //         //     let stockGeneral ;

        //         //     $('#equivalente_unidad').val(json.equivalente_unidad)
        //         //     $('#cantidad_por_caja').val(json.cantidad_por_caja)

        //         //     if(json.unidad_medida_id == "{{ config('siat.metro_cuadrado') }}"){
        //         //         $('.visualizacion_m2').show('toogle')
        //         //         $('#medida_producto').attr("required", true);
        //         //         $('#metro2xcaja').attr("required", true);
        //         //         $('#nro_cajas').attr("required", true);
        //         //         $('#nro_piezas').attr("required", true);
        //         //         stockGeneral = json.stockM2;
        //         //         $('#stock_producto').val(stockGeneral === null ? 0 : stockGeneral)
        //         //     }else{
        //         //         $('.visualizacion_m2').hide('toogle')
        //         //         $('#medida_producto').attr("required", false);
        //         //         $('#metro2xcaja').attr("required", false);
        //         //         $('#nro_cajas').attr("required", false);
        //         //         $('#nro_piezas').attr("required", false);
        //         //         stockGeneral = json.stock;
        //         //         $('#stock_producto').val(stockGeneral === null ? 0 : stockGeneral)
        //         //     }

        //         //     if (stockGeneral > 0 || stockGeneral !== null) {
        //         //         $('#boton-agrega-producto').attr('disabled', false);
        //         //         $('#stock-bajo').text('');
        //         //         $('#stock_producto').removeClass('is-invalid');
        //         //     } else {
        //         //         $('#boton-agrega-producto').attr('disabled', true);
        //         //         $('#stock-bajo').text('Stock insuficiente!!');
        //         //         $('#stock_producto').addClass('is-invalid');
        //         //     }
        //         // } else {
        //         //     $('#precio_venta').val(0)
        //         //     $('#cantidad_venta').val(0)
        //         //     $('#total_venta').val(0)
        //         //     $('#numero_serie').val(null)
        //         //     $('#codigo_imei').val(null)
        //         //     $('#descripcion_adicional').val(null)
        //         //     $('#stock_producto').val(0)
        //         //     $('#medida_producto').val(null)
        //         //     $('#metro2xcaja').val(null)
        //         //     $('#equivalente_unidad').val(0)
        //         //     $('#cantidad_por_caja').val(0)
        //         //     $('#nro_cajas').val(0)
        //         //     $('#nro_piezas').val(0)

        //         //     $('#cantidad_venta').removeAttr('max');

        //     }
        // }

        function agregarProducto() {

            if ($("#formulario_venta")[0].checkValidity()) {

                $('#tabla_detalles').show('toggle');

                let cantidad_venta         = $('#cantidad_venta').val();
                let prenda_id              = $('#prenda_id').val();
                let numero_ojales          = $('#numero_ojales').val();
                let tela_id                = $('#tela_id').val();
                let prelavado_id           = $('#prelavado_id').val();
                let nevado_id              = $('#nevado_id').val();
                let focalizado_id          = $('#focalizado_id').val();
                let tipo_tela_id           = $('#tipo_tela_id').val();
                let color_tela_id          = $('#color_tela_id').val();
                let caracteristica_tela_id = $('#caracteristica_tela_id').val();
                let peso                   = $('#peso').val();
                let precio_venta           = $('#precio_venta').val();
                let sub_total              = $('#sub_total').val();
                let observacion            = $('#observacion').val();
                let nro_ot                 = $('#nro_ot').val();

                let servicio = {
                    cantidad_venta        : cantidad_venta,
                    prenda_id             : prenda_id,
                    numero_ojales         : numero_ojales,
                    tela_id               : tela_id,
                    prelavado_id          : prelavado_id,
                    nevado_id             : nevado_id,
                    focalizado_id         : focalizado_id,
                    tipo_tela_id          : tipo_tela_id,
                    color_tela_id         : color_tela_id,
                    caracteristica_tela_id: caracteristica_tela_id,
                    peso                  : peso,
                    precio_venta          : precio_venta,
                    sub_total             : sub_total,
                    observacion           : observacion,
                    nro_ot                : nro_ot
                }

                contadorTable++;

                let btnEliminar = `<button class='eliminar btn btn-icon btn-danger btn-circle btn-sm'
                                    title='Eliminar del carro'
                                    onclick='eliminarItem(${contadorTable})'>
                                        <i class='fa fa-trash'></i>
                                </button>`;

                table.row.add([
                    cantidad_venta,
                    $('#prenda_id option:selected').text(),
                    numero_ojales,
                    $('#tela_id option:selected').text(),
                    $('#prelavado_id option:selected').text(),
                    $('#nevado_id option:selected').text(),
                    $('#focalizado_id option:selected').text(),
                    $('#tipo_tela_id option:selected').text(),
                    $('#color_tela_id option:selected').text(),
                    $('#caracteristica_tela_id option:selected').text(),
                    peso,
                    precio_venta,
                    sub_total,
                    observacion,
                    nro_ot,
                    btnEliminar
                ]).node().id = 'producto-' + contadorTable;
                table.draw(false);

                arrayProductos.push(servicio);

                // SETEAMOS EL VALOR PARA EL PAGO
                let montoPagado = parseFloat($('#monto_total_pagado_recibo').val());
                let s = parseFloat(sub_total);
                $('#monto_total_pagado_recibo').val(montoPagado + s);

                // LIMPIAMOS TODOS LOS SELECT
                $('#cantidad_venta').val(0);
                $('#prenda_id').val("").trigger('change');
                $('#numero_ojales').val(0);
                $('#tela_id').val("").trigger('change');
                $('#prelavado_id').val("").trigger('change');
                $('#nevado_id').val("").trigger('change');
                $('#focalizado_id').val("").trigger('change');
                $('#tipo_tela_id').val("").trigger('change');
                $('#color_tela_id').val("").trigger('change');
                $('#caracteristica_tela_id').val("").trigger('change');
                $('#peso').val(0);
                $('#precio_venta').val(0);
                $('#sub_total').val(0);
                $('#observacion').val("");
                $('#nro_ot').val(0);

            } else {
                $("#formulario_venta")[0].reportValidity();
            }

        }

        function recepcionar() {
            if($('#formularioGeneraRecibo')[0].checkValidity()){
                $.ajax({
                    url: "{{ url('factura/recepcionar') }}",
                    method: "POST",
                    data: {
                        carro             : arrayProductos,
                        cliente           : $('#cliente_seleccionado_id').val(),
                        prioridad_cliente : $('#prioridad_cliente').val(),
                        fecha_recepcion   : $('#fecha_recepcion_cliente').val(),
                        entregado_por     : $('#entregado_por').val(),
                        usuario_recepciono: $('#usuario_recepciono_id').val(),

                        tipo_pago_pagado_recibo: $('#tipo_pago_pagado_recibo').val(),
                        realizo_pago_recibo: $('#realizo_pago_recibo').is(':checked'),
                        monto_total_pagado_recibo: $('#monto_total_pagado_recibo').val(),
                        monto_pagado_recibo: $('#monto_pagado_recibo').val(),
                        cambio_pagado_recibo: $('#cambio_pagado_recibo').val(),
                    },
                    // data: datos,
                    success: function(data) {
                        if (data.estado) {
                            Swal.fire({
                                icon: 'success',
                                title: "Exito!",
                                text: "Se genero con exito la venta",
                                timer: 4000
                            })
                            location.reload();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: "Error!",
                                text: "Ocurrio un error!",
                                timer: 4000
                            })
                        }
                    }
                })
            }else{
                $('#formularioGeneraRecibo')[0].reportValidity()
            }

        }

        function validarCamposRecibo() {
            let tipo_pago = $('#tipo_pago_pagado_recibo').val()

            if (tipo_pago === 'EFECTIVO' || tipo_pago === 'TRANSFERENCIA' || tipo_pago === 'QR') {
                $('#realizo_pago_recibo').prop('required', true);
                $('#monto_pagado_recibo').prop('required', true);
                $('#cambio_pagado_recibo').prop('required', true);
                $('#realizo_pago_recibo').prop('checked', true);
                $('#monto_pagado_recibo').attr('min', 1);
            } else {
                $('#realizo_pago_recibo').prop('required', false);
                $('#monto_pagado_recibo').prop('required', false);
                $('#cambio_pagado_recibo').prop('required', false);
                $('#realizo_pago_recibo').prop('checked', false);
                $('#monto_pagado_recibo').attr('min', 0);
            }
        }

        function caluclarCambioRecibo(select) {

            let monto_total_pagado = parseFloat($('#monto_total_pagado_recibo').val())
            let monto_pagado = parseFloat(select.value)

            if (monto_pagado > monto_total_pagado) {
                $('#cambio_pagado_recibo').val(monto_pagado - monto_total_pagado)
            } else if (monto_pagado <= monto_total_pagado) {
                $('#cambio_pagado_recibo').val(0)
            }

            if (monto_pagado === 0) {
                $('#tipo_pago_pagado_recibo').prop('required', false)
                $('#realizo_pago_recibo').prop('required', false)
            } else {
                $('#tipo_pago_pagado_recibo').prop('required', true)
                $('#realizo_pago_recibo').prop('required', true)
            }
        }

        // function ejecutarDescuento(valor) {

        //     let valorDescuento = valor.value;
        //     let valorId = valor.id;
        //     let id = valorId.split("_")[1]
        //     var filaExistente = table.row("#producto-" + id);

        //     if (filaExistente.node()) {
        //         var totalCell = $(filaExistente.node()).find('.total');
        //         var valorTotal = parseFloat(totalCell.text());
        //         var subTotalCell = $(filaExistente.node()).find('.subTotal');

        //         if (parseFloat(valorDescuento) > -1) {
        //             if (valorDescuento < valorTotal) {
        //                 subTotalCell.text((valorTotal - valorDescuento).toFixed(2));
        //                 let servicio = arrayProductoCar.find(s => s.servicio_id === parseInt(id));
        //                 if (servicio) {
        //                     servicio.descuento = parseFloat(valorDescuento);
        //                     servicio.subTotal = parseFloat(servicio.total) - parseFloat(valorDescuento);

        //                     // EJECUTAMOS EL DESCUENTO
        //                     let sumaTotal = arrayProductoCar.reduce((sum, current) => sum + current.subTotal, 0);
        //                     let descuentoAdicional = $('#descuento_adicional').val()
        //                     $('#monto_total, #monto_total_pagado').val(parseFloat(sumaTotal) - parseFloat(
        //                         descuentoAdicional))
        //                 } else {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: "ERROR!",
        //                         text: "Error al actualizar el descuento",
        //                         timer: 4000
        //                     })
        //                 }

        //             } else {
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: "ERROR!",
        //                     text: "El valor de descuento no debe ser mayor al valor Total",
        //                     timer: 4000
        //                 })
        //                 $('#descuento_' + id).val(valorTotal - parseFloat(subTotalCell.text()))
        //             }
        //         } else {
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: "ERROR!",
        //                 text: "El valor de descuento debe ser mayor a 0!",
        //                 timer: 4000
        //             })
        //             $('#descuento_' + id).val(valorTotal - parseFloat(subTotalCell.text()))
        //         }
        //     } else {
        //         Swal.fire({
        //             icon: 'error',
        //             title: "ERROR!",
        //             text: "Servicion no encontrado",
        //             timer: 4000
        //         })
        //     }
        // }

        // function ajaxListadoClientes() {

        //     // if (
        //     //     $('#nit_escogido').val().length > 3 ||
        //     //     $('#nombre_escogido').val().length >= 3 ||
        //     //     $('#ap_paterno_escogido').val().length > 3 ||
        //     //     $('#ap_materno_escogido').val().length > 3
        //     // ) {
        //     let datos = $('#formulario_cliente_escogido').serializeArray();
        //     $.ajax({
        //         url: "{{ url('factura/ajaxListadoClientesBusqueda') }}",
        //         method: "POST",
        //         data: datos,
        //         success: function(data) {
        //             if (data.estado) {
        //                 if (data.data.cantidad > 0)
        //                     $('#tabla-clientes-buscados').show('toogle')

        //                 $('#tabla-clientes-buscados').html(data.data.listado)
        //             } else {

        //             }
        //         }
        //     })
        //     // }
        // }

        // function mostraBloqueMasDatosProdcuto() {

        //     $('#bloque_mas_datos_productos').toggle('show')
        // }

        // function escogerCliente(cliente, nombres, ap_paterno, ap_materno, cedula, nit, razon_social) {

        //     $('#cliente_id_escogido').val(cliente);

        //     $('#nombre_escogido').val('');
        //     $('#ap_paterno_escogido').val('');
        //     $('#ap_materno_escogido ').val('');
        //     $('#cedula_escogido').val('');

        //     $('#nit_factura').val(nit);
        //     $('#razon_factura').val(razon_social);

        //     $('#tabla-clientes-buscados').hide('toggle')

        //     let nombreusuario = "CLIENTE ESCOGIDO: " + cedula + " | " + nombres + " | " + ap_paterno + " | " + ap_materno;

        //     $('#nombre_cliente').text(nombreusuario)

        //     $('#formulario_cliente_escogido').toggle('hide');

        //     $('#bloque-botones-emisiones').show('toggle');

        //     $('#bloque_recibo').hide('toggle');
        //     $('#bloqueDatosFactura').hide('toggle');
        //     $('#bloque_facturacion').hide('toggle');
        // }

        // function escogerVentaTipo(tipo) {

        //     if (tipo === 'RECIBO') {
        //         $('#bloque_recibo').show('toggle');
        //         $('#bloqueDatosFactura').hide('toggle');
        //         $('#bloque_facturacion').hide('toggle');
        //     } else {
        //         $('#bloqueDatosFactura').show('toggle');
        //         $('#bloque_facturacion').show('toggle');
        //         $('#bloque_recibo').hide('toggle');
        //     }

        // }

        // function mostrarFormularioClientes() {
        //     $('#formulario_cliente_escogido').toggle('show');
        //     $('#tabla_detalles').hide('toggle');
        // }

        // function muestraDatosFactura() {

        //     $('#bloqueDatosFactura').show('toogle')

        // }

        // // function verificaNit() {
        // //     if ($('#tipo_documento').val() === "5") {
        // //         let nit = $('#nit_factura').val();
        // //         $.ajax({
        // //             url: "{{ url('factura/verificarNit') }}",
        // //             data: {
        // //                 nit: nit
        // //             },
        // //             type: 'POST',
        // //             dataType: 'json',
        // //             success: function(data) {
        // //                 if (data.estado) {
        // //                     if (data.data.estadoSiat) {
        // //                         $('#execpcion').prop('checked', false);
        // //                         $('#nitsiexiste').show('toggle')
        // //                         $('#nitnoexiste').hide('toggle')
        // //                     } else {
        // //                         $('#nitnoexiste').show('toggle')
        // //                         $('#nitsiexiste').hide('toggle')
        // //                         $('#execpcion').prop('checked', true);
        // //                     }
        // //                 } else {
        // //                     $('#errorValidar').show('toggle')
        // //                 }
        // //             }
        // //         });

        // //         $('#complemento').val(null)
        // //         $('#bloque_complemento').hide('toggle')

        // //     } else if ($('#tipo_documento').val() === "1") {

        // //         $('#bloque_complemento').show('toggle')
        // //         $('#nitnoexiste').hide('toggle')
        // //         $('#nitsiexiste').hide('toggle')
        // //         $('#errorValidar').hide('toggle')
        // //         $('#execpcion').prop('checked', false);

        // //     } else {
        // //         $('#nitnoexiste').hide('toggle')
        // //         $('#nitsiexiste').hide('toggle')
        // //         $('#errorValidar').hide('toggle')
        // //         $('#execpcion').prop('checked', false);

        // //         $('#bloque_complemento').hide('toggle')

        // //     }
        // // }

        // function verificaTipoPago(select) {

        //     // let valor = select.value;
        //     // if(valor == 2 || valor == 10  || valor == 83 || valor == 162 || valor == 86){
        //     //     $('#bloque-tipo-pago').show('toggle')
        //     //
        //     //     $('#monto_gift_card').val(null)
        //     //     $('#bloque-gifr-card').hide('toggle')
        //     // }else if(valor == 27 || valor == 35){
        //     //     $('#bloque-gifr-card').show('toggle')
        //     //
        //     //     $('#numero_tarjeta').val(null)
        //     //     $('#bloque-tipo-pago').hide('toggle')
        //     // }else{
        //     //     $('#monto_gift_card').val(null)
        //     //     $('#numero_tarjeta').val(null)
        //     //
        //     //     $('#bloque-gifr-card').hide('toggle')
        //     //     $('#bloque-tipo-pago').hide('toggle')
        //     // }

        //     let arrayTarjeta = [2, 10, 16, 17, 18, 19, 20, 39, 40, 41, 42, 43, 82, 83, 84, 85, 87, 88, 89, 134, 135, 136,
        //         137, 139, 140, 141, 142, 143, 144, 145, 147, 148, 149, 150, 151, 152, 154, 155, 156, 157, 158, 160, 161,
        //         162, 163, 165, 166, 167, 169, 170, 171, 172, 173, 174, 175, 176, 177, 297
        //     ];
        //     let arrayGiftCard = [27, 30, 35, 64, 68, 76, 77, 304, 94, 102, 109, 115, 120, 124, 128, 129, 130, 182, 189, 195,
        //         200, 204, 217, 224, 225, 228, 232, 241, 246, 250, 261, 265, 269, 270, 271, 275, 279, 280, 281, 291, 292,
        //         293
        //     ];
        //     let arrayTarjetaGiftCard = [86, 138, 146, 153, 159, 164, 168, 223];
        //     let valor = parseInt(select.value);
        //     // if(valor == 2 || valor == 10  || valor == 83 || valor == 86){
        //     if (arrayTarjeta.includes(valor)) {
        //         $('#bloque-tipo-pago').show('toggle')
        //         $('#monto_gift_card').val(null)
        //         $('#bloque-gifr-card').hide('toggle')
        //         // }else if(valor == 27 || valor == 35){
        //     } else if (arrayGiftCard.includes(valor)) {
        //         $('#bloque-gifr-card').show('toggle')
        //         $('#numero_tarjeta').val(null)
        //         $('#bloque-tipo-pago').hide('toggle')
        //     } else if (arrayTarjetaGiftCard.includes(valor)) {
        //         $('#bloque-gifr-card').show('toggle')
        //         $('#bloque-tipo-pago').show('toggle')
        //     } else {
        //         $('#monto_gift_card').val(null)
        //         $('#numero_tarjeta').val(null)

        //         $('#bloque-gifr-card').hide('toggle')
        //         $('#bloque-tipo-pago').hide('toggle')
        //     }
        // }

        // function verificarNumeroTarjeta() {
        //     const input = document.getElementById("numero_tarjeta");
        //     let valor = input.value;

        //     // Asegurarse de que solo se ingresen números
        //     valor = valor.replace(/\D/g, ""); // Elimina cualquier carácter no numérico

        //     // Enmascarar el número de la tarjeta
        //     if (valor.length > 8) {
        //         // const primeros4 = valor.substring(0, 4);
        //         // const ultimos4 = valor.slice(-4);
        //         // const masked = `${primeros4}${"x".repeat(valor.length - 8)}${ultimos4}`;
        //         input.value = masked;
        //     } else {
        //         input.value = valor; // Muestra el valor completo si es menor o igual a 8 dígitos
        //     }

        // }

        // function ejecutarDescuentoAdicional() {

        //     let descuentoAdcional = parseFloat($('#descuento_adicional').val())
        //     let montoTotal = parseFloat($('#monto_total').val())

        //     if (descuentoAdcional > -1) {
        //         if (descuentoAdcional < montoTotal) {
        //             let sumaTotal = arrayProductoCar.reduce((sum, current) => sum + current.subTotal, 0);
        //             let descuentoAdicional = $('#descuento_adicional').val();
        //             $('#monto_total, #monto_total_pagado').val(parseFloat(sumaTotal) - parseFloat(descuentoAdicional))
        //         } else {
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: "Error",
        //                 text: 'El descuento Adicional no debe ser mayor al monto total!',
        //             })
        //             let sumaTotal = arrayProductoCar.reduce((sum, current) => sum + current.subTotal, 0);
        //             let descuentoAdicional = 0;
        //             $('#monto_total, #monto_total_pagado').val(parseFloat(sumaTotal) - parseFloat(descuentoAdicional))
        //             $('#descuento_adicional').val(0);
        //         }
        //     } else {
        //         Swal.fire({
        //             icon: 'error',
        //             title: "Error",
        //             text: 'El descuento debe ser mayor a 0!',
        //         })
        //         let sumaTotal = arrayProductoCar.reduce((sum, current) => sum + current.subTotal, 0);
        //         let descuentoAdicional = 0;
        //         $('#monto_total, #monto_total_pagado').val(parseFloat(sumaTotal) - parseFloat(descuentoAdicional))
        //         $('#descuento_adicional').val(0);
        //     }
        // }

        // function bloqueCAFC() {
        //     if ($('#tipo_facturacion').val() === "offline") {

        //         let tipo_documento = $('#tipo_documento').val();
        //         let emision = $('#tipo_facturacion').val();

        //         verificarExcepcion(tipo_documento, emision);

        //     } else {
        //         $('#numero_factura_cafc').val(null)
        //         $('#bloque_cafc, #numero_fac_cafc').hide('toggle')
        //         $('#execpcion').prop('checked', false);

        //         $('#select_cufd_vigentes').html('')
        //         $('#bloque_cufd_offline').hide('toggle');

        //         // Marcar el radio button con value="No" usando name
        //         $('input[name="uso_cafc"][value="No"]').prop('checked', true);
        //     }
        // }

        // function verificarRadioSeleccionado() {
        //     var valorSeleccionado = $('input[name="uso_cafc"]:checked').val();
        //     if (valorSeleccionado === 'No') {

        //         $('#numero_fac_cafc').hide('toggle');
        //         $('#numero_factura_cafc').val(0)

        //     } else if (valorSeleccionado === 'Si') {
        //         $.ajax({
        //             url: "{{ url('factura/sacaNumeroCafcUltimo') }}",
        //             method: "POST",
        //             dataType: 'json',
        //             success: function(data) {
        //                 if (data.estado) {
        //                     $("#numero_factura_cafc").val(data.data.numero);
        //                     $('#numero_fac_cafc').show('toggle');
        //                 } else {
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: 'Error!',
        //                         text: "Algo fallo"
        //                     })
        //                 }
        //             }
        //         })
        //     }
        // }

        // function eliminarItem(id) {

        //     var fila = table.row("#producto-" + id);
        //     // var cantidadCell = $(fila.node()).find('.cantidad');
        //     // var cantidadActual = parseInt(cantidadCell.text());

        //     // Reducir la cantidad en 1
        //     // var nuevaCantidad = cantidadActual - 1;
        //     // cantidadCell.text(nuevaCantidad);

        //     // if (nuevaCantidad <= 0) {
        //     //     // Si la cantidad es 0 o menos, elimina la fila de la tabla
        //     table.row(fila).remove().draw(false);

        //     // Elimina el producto del array
        //     arrayProductoCar = arrayProductoCar.filter(s => s.servicio_id !== id);
        //     // } else {
        //     //     // Si la cantidad sigue siendo mayor que 0, actualiza el total y el subTotal
        //     //     var precio = parseFloat($(fila.node()).find('.total').text()) / cantidadActual;
        //     //     var nuevoTotal = nuevaCantidad * precio;
        //     //     $(fila.node()).find('.total').text(nuevoTotal.toFixed(2));

        //     //     var subTotalCell = $(fila.node()).find('.subTotal');
        //     //     var descuento = parseFloat($('#descuento_' + id).val());
        //     //     var nuevoSubTotal = nuevoTotal - descuento;
        //     //     subTotalCell.text(nuevoSubTotal.toFixed(2));

        //     //     // Actualiza los valores en el array
        //     //     let servicio = arrayProductoCar.find(s => s.servicio_id === id);
        //     //     if (servicio) {
        //     //         servicio.cantidad = nuevaCantidad;
        //     //         servicio.total = nuevoTotal;
        //     //         servicio.subTotal = nuevoSubTotal;
        //     //     }
        //     // }

        //     // Actualizar el monto total
        //     let sumaTotal = arrayProductoCar.reduce((sum, current) => sum + current.subTotal, 0);
        //     let descuentoAdicional = $('#descuento_adicional').val();
        //     $('#monto_total, #monto_total_pagado').val(parseFloat(sumaTotal) - parseFloat(descuentoAdicional));

        // }

        // function mostrarCarritoVentas() {
        //     $('#tabla_detalles').toggle('show')
        // }

        // function calcularPrecioTotal() {
        //     let precio = $('#precio_venta').val();
        //     let cantidad = $('#cantidad_venta').val();
        //     let total = parseFloat(precio) * parseFloat(cantidad);

        //     // CALCULAMOS LAS CANTIDAD DE CAJAS Y PIEZAS
        //     let equivalente_unidadM2 = parseFloat($('#equivalente_unidad').val());
        //     let cantidad_por_caja = parseFloat($('#cantidad_por_caja').val());

        //     let cantidadTotalPiezas = parseFloat(cantidad) / equivalente_unidadM2;
        //     let cantidadTotalCajas = cantidadTotalPiezas / cantidad_por_caja;
        //     let cantidadTotalPiezasSueltas = cantidadTotalPiezas % cantidad_por_caja;

        //     if (Math.round(cantidadTotalPiezasSueltas) == cantidad_por_caja) {
        //         $('#nro_cajas').val(Math.floor(cantidadTotalCajas) + 1);
        //         $('#nro_piezas').val(0);
        //     } else {
        //         $('#nro_cajas').val(Math.floor(cantidadTotalCajas));
        //         $('#nro_piezas').val(cantidadTotalPiezasSueltas);
        //     }

        //     $('#total_venta').val(total.toFixed(2))
        // }

        // function modalAgregarCliente() {
        //     $('#modal_new_cliente').modal('show');
        // }

        // function guardarClienteEmpresa() {
        //     if ($("#formulario_new_cliente_empresa")[0].checkValidity()) {
        //         let datos = $('#formulario_new_cliente_empresa').serializeArray();
        //         $.ajax({
        //             url: "{{ url('cliente/guardarClienteFactura') }}",
        //             method: "POST",
        //             data: datos,
        //             success: function(data) {
        //                 if (data.estado) {
        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: "EXITO!",
        //                         text: "SE REGISTRO CON EXITO",
        //                     })

        //                     console.log(data.data.cliente);

        //                     $('#cliente_id_escogido').val(data.data.cliente.id);

        //                     let cedula = $('#cedula_cliente_new_usuaio_empresa').val();
        //                     let nombres = $('#nombres_cliente_new_usuaio_empresa').val();
        //                     let ap_paterno = $('#ap_paterno_cliente_new_usuaio_empresa').val();
        //                     let ap_materno = $('#ap_materno_cliente_new_usuaio_empresa').val();

        //                     let nombreusuario = cedula + " | " + nombres + " | " + ap_paterno + " | " +
        //                         ap_materno;
        //                     $('#nombre_cliente').text(nombreusuario)

        //                     $('#nit_factura').val($('#nit_cliente_new_usuaio_empresa').val());
        //                     $('#razon_factura').val($('#razon_social_cliente_new_usuaio_empresa').val());

        //                     $('#bloqueDatosFactura, #bloque_facturacion').show('toggle');

        //                     //ajaxListado();
        //                 } else if (data.estado === 'error') {
        //                     Swal.fire({
        //                         icon: 'warning',
        //                         title: "ALTO!",
        //                         text: data.text,
        //                     })
        //                 } else {

        //                 }
        //                 $('#modal_new_cliente').modal('hide');
        //             }
        //         })
        //     } else {
        //         $("#formulario_new_cliente_empresa")[0].reportValidity();
        //     }
        // }

        // function modalAgregarProducto() {
        //     $('#modal_new_servicio').modal('show');
        // }

        // function guardarNewServioEmpresa() {
        //     if ($("#formulario_new_servicio")[0].checkValidity()) {
        //         // let datos = $('#formulario_new_servicio').serializeArray();
        //         let formData = new FormData($("#formulario_new_servicio")[0]);
        //         $.ajax({
        //             url: "{{ url('empresa/guardarNewServioEmpresaFormularioFacturacion') }}",
        //             method: "POST",
        //             data: formData,
        //             processData: false,
        //             contentType: false,
        //             success: function(data) {
        //                 if (data.estado === 'success') {


        //                     Swal.fire({
        //                         icon: 'success',
        //                         title: "EXITO!",
        //                         text: "SE REGISTRO CON EXITO",
        //                     })

        //                     let nuevosServicios = data.servicio;

        //                     var select = $('#serivicio_id_venta');

        //                     // Vaciar el select
        //                     select.empty();

        //                     // Añadir la opción por defecto
        //                     select.append('<option value="">SELECCIONE</option>');

        //                     // Volver a llenar el select con las nuevas opciones
        //                     $.each(nuevosServicios, function(index, servicio) {
        //                         let d = JSON.stringify(servicio)
        //                         select.append('<option value=\'' + d + '\'>' + servicio.descripcion +
        //                             '</option>');
        //                     });

        //                     //ajaxListado();
        //                 } else if (data.estado === 'error') {
        //                     Swal.fire({
        //                         icon: 'warning',
        //                         title: "ALTO!",
        //                         text: data.text,
        //                     })
        //                 }
        //                 $('#modal_new_servicio').modal('hide');
        //             }
        //         })
        //     } else {
        //         $("#formulario_new_servicio")[0].reportValidity();
        //     }
        // }

        // function verificarExcepcion(tipo_documento, emision, uso_cafse) {
        //     if (emision === "offline") {
        //         if (tipo_documento == "5") { //VERIFICAMOS QUE SEA NIT
        //             $('#execpcion').prop('checked', true);
        //         } else {
        //             $('#execpcion').prop('checked', false);
        //         }
        //         $('#bloque_cafc').show('toggle')

        //         $.ajax({
        //             url: "{{ url('eventoSignificativo/sacarCufdsPorTipoEvento') }}",
        //             method: "POST",
        //             data: {},
        //             success: function(data) {
        //                 if (data.estado) {
        //                     // REMPLAZAR LOS CUFDS VIGENTES
        //                     $('#select_cufd_vigentes').html(data.data.select)
        //                     $('#bloque_cufd_offline').show('toggle');
        //                 } else {
        //                     $('#select_cufd_vigentes').html('')
        //                     $('#bloque_cufd_offline').hide('toggle');
        //                     Swal.fire({
        //                         icon: 'error',
        //                         title: "Error!",
        //                         text: data.text,
        //                     })
        //                 }
        //             }
        //         })
        //     }
        // }

        // {{--

        // function filtrarPorActividad(datos) {

        //     var productosServicios = @JSON($productoServicio);
        //     var codigo = $(datos).find(':selected').data('codigo');
        //     var listadoFiltrado = productosServicios.filter(pro => parseInt(pro.codigo_actividad) === codigo)

        //     //Llenar el segundo <select>
        //     var selectProducto = $("#producto_servicio_siat_id_new_servicio");
        //     selectProducto.empty().append('<option></option>'); // Limpiar y agregar opción vacía

        //     listadoFiltrado.forEach(pro => {
        //         selectProducto.append(`<option value="${pro.id}">${pro.descripcion_producto}</option>`);
        //     });

        //     // Refrescar el select2 si lo usas
        //     selectProducto.trigger('change');

        // }
        // --}}

        // function modalAperturaCaja() {
        //     // $('#nombre').val('')
        //     $('#monto_apertura').val(0)
        //     $('#descripcion').val('')
        //     $('#modalAperturaCaja').modal('show')
        // }

        // function guardarAperturaCaja() {
        //     if ($('#formularioAperturaCaja')[0].checkValidity()) {
        //         $('#boton_abrir_caja').attr('disabled', true);
        //         let datos = $('#formularioAperturaCaja').serializeArray();
        //         $.ajax({
        //             url: "{{ url('caja/guardarAperturaCaja') }}",
        //             method: "POST",
        //             data: datos,
        //             success: function(resultado) {
        //                 if (resultado.estado) {
        //                     Swal.fire({
        //                         title: "EL REGISTRO FUE EXITOSO.",
        //                         icon: "success",
        //                         timer: 3000, // Se cierra en 3 segundos
        //                         showConfirmButton: false
        //                     });

        //                     location.reload();
        //                 } else {

        //                 }
        //             },
        //             error: function(xhr) {
        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Error',
        //                     text: 'Ocurrió un error inesperado.' + xhr,
        //                 });
        //             }
        //         });
        //     } else {
        //         $('#formularioAperturaCaja')[0].reportValidity()
        //     }
        // }

        // function caluclarCambio(select) {

        //     let monto_total_pagado = parseFloat($('#monto_total_pagado').val())
        //     let monto_pagado = parseFloat(select.value)

        //     if (monto_pagado > monto_total_pagado) {
        //         $('#cambio_pagado').val(monto_pagado - monto_total_pagado)
        //     } else if (monto_pagado <= monto_total_pagado) {
        //         $('#cambio_pagado').val(0)
        //     }

        //     if (monto_pagado === 0) {
        //         $('#tipo_pago_pagado').prop('required', false)
        //         $('#realizo_pago').prop('required', false)
        //     } else {
        //         $('#tipo_pago_pagado').prop('required', true)
        //         $('#realizo_pago').prop('required', true)
        //     }

        // }

        // function modalCerrarCaja() {
        //     $('#modalCerrarCaja').modal('show')
        // }

        // function guardarCerrarCaja() {
        //     if ($('#formularioCerrarCaja')[0].checkValidity()) {
        //         $('#boton_cerrar_caja').attr('disabled', true);
        //         let datos = $('#formularioCerrarCaja').serializeArray();
        //         $.ajax({
        //             url: "{{ url('caja/guardarCerrarCaja') }}",
        //             method: "POST",
        //             data: datos,
        //             success: function(resultado) {
        //                 if (resultado.estado) {
        //                     Swal.fire({
        //                         title: "EL REGISTRO FUE EXITOSO.",
        //                         icon: "success",
        //                         timer: 3000, // Se cierra en 3 segundos
        //                         showConfirmButton: false
        //                     });

        //                     location.reload();
        //                 } else {

        //                 }
        //             },
        //             error: function(xhr) {

        //                 Swal.fire({
        //                     icon: 'error',
        //                     title: 'Error',
        //                     text: 'Ocurrió un error inesperado.' + xhr,
        //                 });

        //                 // limpiarErorres();

        //                 // if (xhr.status === 422) {
        //                 //     let errores = xhr.responseJSON.errors;

        //                 //     for (let campo in errores) {
        //                 //         let mensaje = errores[campo][0];

        //                 //         let input = $(`[name="${campo}"]`);
        //                 //         input.addClass("is-invalid");
        //                 //         input.after(`<div class="invalid-feedback">${mensaje}</div>`);
        //                 //     }
        //                 // } else {
        //                 //     Swal.fire({
        //                 //         icon: 'error',
        //                 //         title: 'Error',
        //                 //         text: 'Ocurrió un error inesperado.',
        //                 //     });
        //                 // }
        //             }
        //         });
        //     } else {
        //         $()[0].reportValidity();
        //     }
        // }

        // function validarCampos() {
        //     let tipo_pago = $('#tipo_pago_pagado').val()

        //     if (tipo_pago === 'EFECTIVO' || tipo_pago === 'TRANSFERENCIA' || tipo_pago === 'QR') {
        //         $('#realizo_pago').prop('required', true);
        //         $('#monto_pagado').prop('required', true);
        //         $('#cambio_pagado').prop('required', true);
        //         $('#realizo_pago').prop('checked', true);
        //         $('#monto_pagado').attr('min', 1);
        //     } else {
        //         $('#realizo_pago').prop('required', false);
        //         $('#monto_pagado').prop('required', false);
        //         $('#cambio_pagado').prop('required', false);
        //         $('#realizo_pago').prop('checked', false);
        //         $('#monto_pagado').attr('min', 0);
        //     }
        // }

        // function validarCamposRecibo() {
        //     let tipo_pago = $('#tipo_pago_pagado_recibo').val()

        //     if (tipo_pago === 'EFECTIVO' || tipo_pago === 'TRANSFERENCIA' || tipo_pago === 'QR') {
        //         $('#realizo_pago_recibo').prop('required', true);
        //         $('#monto_pagado_recibo').prop('required', true);
        //         $('#cambio_pagado_recibo').prop('required', true);
        //         $('#realizo_pago_recibo').prop('checked', true);
        //         $('#monto_pagado_recibo').attr('min', 1);
        //     } else {
        //         $('#realizo_pago_recibo').prop('required', false);
        //         $('#monto_pagado_recibo').prop('required', false);
        //         $('#cambio_pagado_recibo').prop('required', false);
        //         $('#realizo_pago_recibo').prop('checked', false);
        //         $('#monto_pagado_recibo').attr('min', 0);
        //     }
        // }

        // function caluclarCambioRecibo(select) {

        //     let monto_total_pagado = parseFloat($('#monto_total_pagado_recibo').val())
        //     let monto_pagado = parseFloat(select.value)

        //     if (monto_pagado > monto_total_pagado) {
        //         $('#cambio_pagado_recibo').val(monto_pagado - monto_total_pagado)
        //     } else if (monto_pagado <= monto_total_pagado) {
        //         $('#cambio_pagado_recibo').val(0)
        //     }

        //     if (monto_pagado === 0) {
        //         $('#tipo_pago_pagado_recibo').prop('required', false)
        //         $('#realizo_pago_recibo').prop('required', false)
        //     } else {
        //         $('#tipo_pago_pagado_recibo').prop('required', true)
        //         $('#realizo_pago_recibo').prop('required', true)
        //     }
        // }

        // function emitirRecibo() {
        //     if ($("#formularioGeneraRecibo")[0].checkValidity()) {

        //         if (arrayProductoCar.length > 0) {

        //             // // Obtén el botón y el icono de carga
        //             // var boton = $("#boton_enviar_recibo");
        //             // var iconoCarga = boton.find("i");
        //             // // Deshabilita el botón y muestra el icono de carga
        //             // boton.attr("disabled", true);
        //             // iconoCarga.show();

        //             $.ajax({
        //                 url: "{{ url('factura/emitirRecibo') }}",
        //                 method: "POST",
        //                 data: {
        //                     cliente_id: $('#cliente_id_escogido').val(),
        //                     carrito: arrayProductoCar,
        //                     carritoAccesorio: arrayProductosAccesorios,
        //                     nit_factura: $('#nit_factura').val(),
        //                     razon_factura: $('#razon_factura').val(),
        //                     descuento_adicional: $('#descuento_adicional').val(),
        //                     monto_total: $('#monto_total').val(),
        //                     tipo_pago_pagado: $('#tipo_pago_pagado_recibo').val(),
        //                     realizo_pago: $('#realizo_pago_recibo').is(':checked'),
        //                     monto_total_pagado: $('#monto_total_pagado_recibo').val(),
        //                     monto_pagado: $('#monto_pagado_recibo').val(),
        //                     cambio_pagado: $('#cambio_pagado_recibo').val()
        //                 },
        //                 success: function(data) {
        //                     if (data.estado) {
        //                         Swal.fire({
        //                             icon: 'success',
        //                             title: 'Excelente!',
        //                             text: 'EL TICKED FUE VALIDADA',
        //                             timer: 3000
        //                         })
        //                         if (data.numero != null && data.numero != '') {
        //                             window.open("{{ url('factura/generaPdfFacturaNewCv') }}/" + data.numero,
        //                                 "_blank", "width=800,height=600");
        //                             window.location.reload();
        //                         } else {
        //                             window.location.href = "{{ url('factura/listado') }}"
        //                         }
        //                     } else {
        //                         Swal.fire({
        //                             icon: 'error',
        //                             title: JSON.stringify(data),
        //                             text: 'EL RECIBO RECHAZADA',
        //                         })
        //                         // Habilita el botón y oculta el icono de carga después de completar
        //                         boton.attr("disabled", false);
        //                         iconoCarga.hide();
        //                     }
        //                 },
        //                 error: function(error) {

        //                 }
        //             })


        //         } else {
        //             Swal.fire({
        //                 icon: 'error',
        //                 title: 'Error!',
        //                 text: "Debe tener al menos un producto agregado al carrito!",
        //                 showConfirmButton: false,
        //                 timer: 5000,
        //                 timerProgressBar: true
        //             })
        //         }

        //     } else {
        //         $("#formularioGeneraRecibo")[0].reportValidity();
        //     }
        // }

        // function calcularCajasPiezas() {
        //     let nro_cajas = parseFloat($('#nro_cajas').val());
        //     let nro_piezas = parseFloat($('#nro_piezas').val());
        //     let cantidad_por_caja = parseFloat($('#cantidad_por_caja').val());
        //     let equivalente_unidad = parseFloat($('#equivalente_unidad').val());

        //     if (nro_piezas == cantidad_por_caja) {
        //         nro_piezas = 0;
        //         nro_cajas = nro_cajas + 1
        //         $('#nro_piezas').val(nro_piezas);
        //         $('#nro_cajas').val(nro_cajas);
        //     } else if (nro_piezas > cantidad_por_caja) {

        //         let calcula_nro_cajas = Math.floor(nro_piezas / cantidad_por_caja);
        //         let calcula_nro_piezas_sobrantes = nro_piezas % cantidad_por_caja;

        //         nro_piezas = calcula_nro_piezas_sobrantes;
        //         nro_cajas = nro_cajas + calcula_nro_cajas

        //         $('#nro_piezas').val(nro_piezas);
        //         $('#nro_cajas').val(nro_cajas);

        //     }

        //     let cantidad_piezas_totales = (cantidad_por_caja * nro_cajas) + nro_piezas;
        //     let cantidad_metro_cuadrado = cantidad_piezas_totales * equivalente_unidad;

        //     $('#cantidad_venta').val(cantidad_metro_cuadrado.toFixed(2));

        //     // PARA CALCULAR EL PRECIO
        //     let precio = $('#precio_venta').val();
        //     let cantidad = $('#cantidad_venta').val();
        //     let total = parseFloat(precio) * parseFloat(cantidad);
        //     $('#total_venta').val(total.toFixed(2))

        // }

        // function modalAgregarAccesorios(btn) {

        //     let id = btn.getAttribute("data-id");

        //     $.ajax({
        //         url: "{{ url('producto/ajaxListadoAccesorios') }}",
        //         method: "POST",
        //         data: {
        //             prodcuto: id
        //         },
        //         success: function(response) {
        //             if (response.estado) {


        //                 let nombre = decodeURIComponent(btn.getAttribute("data-nombre"));

        //                 $('#tabla_accesorios').html(response.data.listado);
        //                 $('#nombre_producto_agregar').val(nombre)
        //                 $('#modal_lista_accesorios').modal('show')
        //             }
        //         },
        //         error: function(error) {

        //         }
        //     })
        // }

        // function agregarAccesorio(producto_destino, producto, nombre) {
        //     if (!arrayProductosAccesorios[producto_destino]) {
        //         arrayProductosAccesorios[producto_destino] = [];
        //     }

        //     arrayProductosAccesorios[producto_destino].push({
        //         id: producto,
        //         nombre: nombre
        //     });

        //     let html = '';
        //     arrayProductosAccesorios[producto_destino].forEach(function(item) {
        //         html += '<span class="badge badge-primary mt-1">' + item.nombre +
        //             '<button type="button" class="btn-close btn-close-white btn-sm ms-1" aria-label="Close" onclick="eliminarBadge(this, ' +
        //             producto_destino + ', ' + producto + ')"></button></span> ';
        //     });

        //     $('#contenedor_accesorio_' + producto_destino).html(html);

        //     $('#modal_lista_accesorios').modal('hide');
        // }

        // function eliminarBadge(elem, producto_destino, producto) {
        //     elem.parentElement.remove();
        //     if (arrayProductosAccesorios[producto_destino]) {
        //         const index = arrayProductosAccesorios[producto_destino].findIndex(item => item.id == producto);
        //         if (index > -1) {
        //             arrayProductosAccesorios[producto_destino].splice(index, 1);
        //         }
        //     }
        // }
    </script>
@endsection
