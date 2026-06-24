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
        <div class="modal-dialog modal-dialog-centered" style="max-width: 70%">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">FORMULARIO DE COTIZACION</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formularioRol">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Cedula</label>
                                    <input type="number" class="form-control form-control-sm" id="cedula" name="cedula" onchange="buscarCliente()">
                                    <input type="text" id="cliente_id" name="cliente_id">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Nombre</label>
                                    <input type="text" class="form-control form-control-sm" id="nombre" name="nombre">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Ap Paterno</label>
                                    <input type="text" class="form-control form-control-sm" id="ap_paterno" name="ap_paterno">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Ap Materno</label>
                                    <input type="text" class="form-control form-control-sm" id="ap_materno" name="ap_materno">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Cantidad Prendas</label>
                                    <input type="text" class="form-control form-control-sm" id="cantidad_prenda" name="cantidad_prenda">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Peso Kg</label>
                                    <input type="text" class="form-control form-control-sm" id="peso_kg" name="peso_kg" onchange="calcularPesoGramo()">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Peso Gr</label>
                                    <input type="text" class="form-control form-control-sm" id="peso_gr" name="peso_gr" readonly>
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
                                                                    <select name="producto_id" class="form-control form-control-sm producto">
                                                                        <option value="">Seleccione</option>
                                                                        @foreach ($productos as $producto)
                                                                            <option value="{{ $producto->id }}" data-producto='@json($producto)' data-ingreso='@json($producto->ultimoIngreso)'>
                                                                                {{ $producto->nombre.(($producto->ultimoIngreso) ? ' | '.$producto->ultimoIngreso?->precio : '') }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <label class="form-label">Porcentaje</label>
                                                                    <input type="number"
                                                                        name="peso"
                                                                        class="form-control form-control-sm porcentaje"
                                                                        min="0"
                                                                        step="0.01">
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <label class="form-label">Cantidad</label>
                                                                    <input type="number"
                                                                        name="cantidad"
                                                                        class="form-control form-control-sm cantidad"
                                                                        min="0"
                                                                        step="0.01">
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <label class="form-label">Total</label>
                                                                    <input type="number"
                                                                        name="cantidad"
                                                                        class="form-control form-control-sm total"
                                                                        min="0"
                                                                        step="0.01">
                                                                </div>

                                                                <div class="col-md-2">
                                                                    <button class="border border-secondary btn btn-icon btn-flex btn-light-danger" data-repeater-delete type="button">
                                                                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
                                                                    </button>
                                                                </div>

                                                            </div>

                                                        </div>

                                                    </div>

                                                    <button type="button"
                                                        data-repeater-create
                                                        class="btn btn-sm btn-flex btn-light-primary btn-sm">
                                                        <i class="ki-duotone ki-plus fs-5"></i>
                                                        Agregar Producto
                                                    </button>

                                                </div>

                                            </div>

                                            <div class="col-md-2">
                                                <button type="button"
                                                    data-repeater-delete
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
                                <button type="button"
                                    data-repeater-create
                                    class="btn btn-flex btn-light-primary">
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
                                    <label class="required fw-semibold fs-6 mb-2">Proceso</label>
                                    <input type="text" class="form-control form-control-sm" id="proceso_focalizado" name="proceso_focalizado" value="FOCALIZADO" readonly>
                                    <input type="hidden" id="proceso_id_focalizado" name="proceso_id_focalizado" value="4">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Precio</label>
                                    <input type="text" class="form-control form-control-sm" id="precio_focalizado" name="precio_focalizado" value="0" onchange="actualizarPRecioIndividual('precio_focalizado', 'total_focalizado')">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Total</label>
                                    <input type="text" class="form-control form-control-sm" id="total_focalizado" name="total_focalizado" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Proceso</label>
                                    <input type="text" class="form-control form-control-sm" id="proceso_planchado" name="proceso_planchado" value="PLANCHADO" readonly>
                                    <input type="hidden" id="proceso_id_planchado" name="proceso_id_planchado" value="20">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Precio</label>
                                    <input type="text" class="form-control form-control-sm" id="precio_planchado" name="precio_planchado" value="0" onchange="actualizarPRecioIndividual('precio_planchado', 'total_planchado')">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Total</label>
                                    <input type="text" class="form-control form-control-sm" id="total_planchado" name="total_planchado" value="0">
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Mano Obra</label>
                                    <input type="text" class="form-control form-control-sm" id="mano_obra" name="mano_obra" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Servicio Basico</label>
                                    <input type="text" class="form-control form-control-sm" id="servicio_basico" name="servicio_basico" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Mantenimiento</label>
                                    <input type="text" class="form-control form-control-sm" id="mantenimiento" name="mantenimiento" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Interes Bancario</label>
                                    <input type="text" class="form-control form-control-sm" id="interes_bancario" name="interes_bancario" value="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-info w-100 btn-sm" type="button" onclick="calcularCostos()">Calcular</button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <input type="text" class="form-control form-control-sm" value="COSTO" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Frost</label>
                                    <input type="text" class="form-control form-control-sm" id="costo_frost" name="costo_frost" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Frost + Foc</label>
                                    <input type="text" class="form-control form-control-sm" id="costo_frost_foc" name="costo_frost_foc" value="0">
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Frost + Foc + Cont</label>
                                    <input type="text" class="form-control form-control-sm" id="costo_frost_foc_cont" name="costo_frost_foc_cont" value="0">
                                </div>
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <input type="text" class="form-control form-control-sm" value="PRECIO" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Frost</label>
                                    <input type="text" class="form-control form-control-sm" id="precio_frost" name="precio_frost" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Frost + Foc</label>
                                    <input type="text" class="form-control form-control-sm" id="precio_frost_foc" name="precio_frost_foc" value="0">
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Frost + Foc + Cont</label>
                                    <input type="text" class="form-control form-control-sm" id="precio_frost_foc_cont" name="precio_frost_foc_cont" value="0">
                                </div>
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <input type="text" class="form-control form-control-sm" value="UTILIDAD" readonly>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Frost</label>
                                    <input type="text" class="form-control form-control-sm" id="utilidad_frost" name="utilidad_frost" value="0">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Frost + Foc</label>
                                    <input type="text" class="form-control form-control-sm" id="utilidad_frost_foc" name="utilidad_frost_foc" value="0">
                                </div>
                            </div>
                            {{-- <div class="col-md-3">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Frost + Foc + Cont</label>
                                    <input type="text" class="form-control form-control-sm" id="utilidad_frost_foc_cont" name="utilidad_frost_foc_cont" value="0">
                                </div>
                            </div> --}}
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-success w-100 btn-sm" type="button" onclick="guardar()">Guardar</button>
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


            $(document).on('input', '.porcentaje', function () {

                let fila = $(this).closest('[data-repeater-item]');
                let peso_gr = $('#peso_gr').val();

                let porcentaje = parseFloat($(this).val()) || 0;

                let producto = JSON.parse(fila.find('.producto option:selected').attr('data-producto'));

                if (!producto) {
                    return;
                }

                // Ejemplo: peso total del producto
                let pesoBase = parseFloat(producto.peso) || 0;

                // let cantidad = (pesoBase * porcentaje) / 100;
                let cantidad = (peso_gr * porcentaje) / 100;

                // fila.find('.cantidad').val(cantidad.toFixed(2));
                fila.find('.cantidad').val(cantidad.toFixed(2));

                calcularTotal(fila);

            });

            $(document).on('change', '.producto', function () {

                let fila = $(this).closest('[data-repeater-item]');

                fila.find('.porcentaje').trigger('input');

            });

        });

        function calcularTotal(fila) {
            let ingreso = JSON.parse(fila.find('.producto option:selected').attr('data-ingreso'));
            let cantidad = parseFloat(fila.find('.cantidad').val()) || 0;
            let precioConvertido = ingreso.precio / 1000;
            let total = precioConvertido * cantidad;
            fila.find('.total').val(total.toFixed(2));
        }

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
            $('#cliente_id').val(0);
            $('#nombre').val(null);
            $('#ap_paterno').val(null);
            $('#ap_materno').val(null);
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

                        let porcentaje = parseFloat(fila.find('.porcentaje').val()) || 0;
                        let cantidad   = parseFloat(fila.find('.cantidad').val()) || 0;

                        let total = porcentaje * cantidad;

                        fila.find('.total').val(total.toFixed(2));

                        totalGeneral += total;
                    });

            });

            // ===================== INPUTS BASE =====================
            let cantidadPrendas = parseFloat($('#cantidad_prenda').val()) || 0;

            let manoObra        = parseFloat($('#mano_obra').val()) || 0;
            let servicioBasico  = parseFloat($('#servicio_basico').val()) || 0;
            let mantenimiento   = parseFloat($('#mantenimiento').val()) || 0;
            let interesBancario = parseFloat($('#interes_bancario').val()) || 0;

            let totalPlanchado  = parseFloat($('#total_planchado').val()) || 0;
            let totalFocalizado = parseFloat($('#total_focalizado').val()) || 0;

            // ===================== VALIDACIÓN BASE =====================
            if (cantidadPrendas <= 0) {
                alert("La cantidad de prendas debe ser mayor a 0");
                return;
            }

            // ===================== COSTO FROSTEADO =====================
            let costoBase = totalGeneral / cantidadPrendas;

            let totalMasFrosteado =
                costoBase +
                manoObra +
                servicioBasico +
                mantenimiento +
                interesBancario +
                totalPlanchado;

            $('#costo_frost').val(totalMasFrosteado.toFixed(2));

            // ===================== PRECIO / UTILIDAD FROST =====================
            let precioFrost = totalMasFrosteado / 0.6;
            let utilidadFrost = precioFrost - totalMasFrosteado;

            $('#precio_frost').val(precioFrost.toFixed(2));
            $('#utilidad_frost').val(utilidadFrost.toFixed(2));

            // ===================== FROST + FOC =====================
            let costoFrostFoc = totalMasFrosteado + totalFocalizado;

            let precioFrostFoc = costoFrostFoc / 0.6;
            let utilidadFrostFoc = precioFrostFoc - costoFrostFoc;

            $('#costo_frost_foc').val(costoFrostFoc.toFixed(2));
            $('#precio_frost_foc').val(precioFrostFoc.toFixed(2));
            $('#utilidad_frost_foc').val(utilidadFrostFoc.toFixed(2));
        }

        function guardar(){

        }

        function actualizarPRecioIndividual(input, tipo){
            let cantidadPrenda = $('#cantidad_prenda').val();
            let precio = $('#'+input).val();
            let h = cantidadPrenda * precio;
            $('#'+tipo).val(h)
        }

    </script>
@endsection
