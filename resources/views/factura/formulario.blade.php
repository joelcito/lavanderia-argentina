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
                                                <label class="fs-6 fw-semibold form-label mb-2 required">Seleccionar
                                                    Cliente</label>
                                                <select name="cliente_seleccionado" id="cliente_seleccionado"
                                                    class="form-select form-select-sm" onchange="seleccionarCliente()"
                                                    required>
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
                                                <label class="fs-6 fw-semibold form-label mb-2">Telefono</label>
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
                                                    id="prioridad_cliente" required>
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
                                            name="fecha_recepcion_cliente" id="fecha_recepcion_cliente" required>
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

                        </div>
                    </div>

                    <div id="tabla_clientes">
                    </div>
                    <hr>
                    <div id="tabla_ventas">
                        <form id="formulario_venta">
                            <div class="row">
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
                                    <input type="text" style="width: 100%" id="numero_ojales" name="numero_ojales" required onchange="cuantificarOjales()" onclick="this.select()" autocomplete="off">
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
                                    <label class="required fw-semibold fs-6 mb-2">C. Tela</label>
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
                                    <label class="fw-semibold fs-6 mb-2 required">Peso</label>
                                    <input type="number" id="peso" name="peso" min="0" step="0.01" style="width: 100%"  required>
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
                                <div class="col-md">
                                    <label class="fw-semibold fs-6 mb-2">Muest</label>
                                    <input type="checkbox" id="con_muestra" name="con_muestra" style="width: 100%">
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
                                                <th>Muest</th>
                                                <th>Acción</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-gray-600 fw-semibold">
                                            <!-- Aquí se agregarán las filas del carrito -->
                                        </tbody>
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
                                                        class="form-control form-control-sm"
                                                        onchange="validarCamposRecibo()">
                                                        <option value="">Seleccione</option>
                                                        <option value="EFECTIVO">EFECTIVO</option>
                                                        <option value="TRANSFERENCIA">TRANSFERENCIA</option>
                                                        <option value="QR">QR</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-3">
                                                    <label class="required">Realizara algun Pago?</label>
                                                    <div class="d-flex align-items-center mt-3">
                                                        <label
                                                            class="form-check form-check-custom form-check-solid me-3">
                                                            <input class="form-check-input h-20px w-20px"
                                                                type="checkbox" name="realizo_pago_recibo" value="pago"
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
    {{--
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script> --}}
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    {{--
    <script src="{{ asset('assets/plugins/custom/formrepeater/formrepeater.bundle.js') }}"></script> --}}
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        var table;
        var arrayProductos = [];
        var contadorTable = 0;

        $(document).ready(function () {

            // $("#prenda_id, #tela_id, #prelavado_id, #nevado_id, #focalizado_id, #tipo_tela_id, #color_tela_id, #caracteristica_tela_id")
            //     .select2();

            // Inicializa el DataTable
            table = $('#carrito').DataTable({
                lengthMenu: [10, 25, 50, 100],
                dom: '<"dt-head row"><"clear">t',
                language: {
                    paginate: { first: 'Primero', last: 'Último', next: 'Siguiente', previous: 'Anterior' },
                    search: 'Buscar:',
                    lengthMenu: 'Mostrar _MENU_ registros por página',
                    info: 'Mostrando _START_ a _END_ de _TOTAL_ registros',
                    emptyTable: 'No hay datos disponibles'
                },
                order: [],
                responsive: true
            });

            let debounceTimer;
            $('.buscar-persona').on('keyup', function () {

                //ajaxListadoClientes();
                clearTimeout(debounceTimer);
                debounceTimer = setTimeout(function () {
                    ajaxListadoClientes();
                }, 300); // Espera 300 ms antes de ejecutar la función
            });


            $('input[name="uso_cafc"]').on('change', function () {
                verificarRadioSeleccionado();
            });

            $('#nombre_cliente').text('SELECCIONAR CLIENTE')

        });

        function seleccionarCliente() {
            let cliente = JSON.parse($('#cliente_seleccionado').val());

            console.log(cliente);

            $('#cliente_id_escogido').val(cliente.id);
            $('#cliente_seleccionado_id').val(cliente.id);
            $('#telefono_cliente').val(cliente.celular);
            $('#direccion_cliente').val(cliente.direccion);

            // Verifica si hay imagen
            if (cliente.imagen && cliente.imagen !== '') {
                let rutaBase = "{{ asset('storage/imagenesClientes') }}/";
                $('#imagen_cliente').attr('src', rutaBase + cliente.imagen);
            } else {
                // Imagen por defecto si no tiene
                $('#imagen_cliente').attr('src', "{{ asset('assets/img/default.jpg') }}");
            }

        }

        function calcularsubTotal() {
            let cantidad = parseFloat($('#cantidad_venta').val())
            let precio = parseFloat($('#precio_venta').val())

            $('#sub_total').val(cantidad * precio);
        }

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
                let nro_ojales             = $('#nro_ojales').val();
                let precio_ojales          = $('#precio_ojales').val();
                let total_ojales           = $('#total_ojales').val();
                let con_muestra            = $('#con_muestra').is(':checked');

                contadorTable++;

                let servicio = {
                    contadorTable         : contadorTable,
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
                    nro_ot                : nro_ot,
                    nro_ojales            : nro_ojales,
                    precio_ojales         : precio_ojales,
                    total_ojales          : total_ojales,
                    con_muestra           : con_muestra
                }


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
                    (con_muestra? 'SI': 'NO'),
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
                $('#con_muestra').prop('checked', false);

                // ESTO ES PARA LOS PRECIO DE OJALES
                $('#nro_ojales').val(0);
                $('#total_ojales').val(0);
                $('#bloque-ojales').hide('toggle')

            } else {
                $("#formulario_venta")[0].reportValidity();
            }

        }

        function recepcionar() {
            if ($('#formularioGeneraRecibo')[0].checkValidity() && $('#formulario_recepcion')[0].checkValidity()) {
                if(arrayProductos.length > 0){

                    var boton = $("#boton_enviar_recibo");
                    var iconoCarga = boton.find("i");
                    // Deshabilita el botón y muestra el icono de carga
                    boton.attr("disabled", true);
                    iconoCarga.show();

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

                            tipo_pago_pagado_recibo  : $('#tipo_pago_pagado_recibo').val(),
                            realizo_pago_recibo      : $('#realizo_pago_recibo').is(':checked'),
                            monto_total_pagado_recibo: $('#monto_total_pagado_recibo').val(),
                            monto_pagado_recibo      : $('#monto_pagado_recibo').val(),
                            cambio_pagado_recibo     : $('#cambio_pagado_recibo').val(),
                        },
                        // data: datos,
                        success: function (data) {
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
                    Swal.fire({
                        icon: 'error',
                        title: "Error!",
                        text: "Debe agregar productos al carro de ventas",
                        timer: 6000
                    })
                }
            } else {
                $('#formularioGeneraRecibo')[0].reportValidity()
                $('#formulario_recepcion')[0].reportValidity()
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

        function recalularPrecioOjales() {
            let nro_ojales = $('#nro_ojales').val()
            let precio = $('#precio_ojales').val()
            let total = parseFloat(nro_ojales) * parseFloat(precio);
            $('#total_ojales').val(total.toFixed(2));
        }

        function eliminarItem(id) {

            var fila = table.row("#producto-" + id);

            table.row(fila).remove().draw(false);

            // Elimina el producto del array
            arrayProductos = arrayProductos.filter(s => s.contadorTable !== id);


            // Actualizar el monto total
            let sumaTotal = arrayProductos.reduce((sum, current) => sum + current.sub_total, 0);
            let descuentoAdicional = $('#descuento_adicional').val();
            $('#monto_total_pagado_recibo').val(parseFloat(sumaTotal));

        }
    </script>
@endsection
