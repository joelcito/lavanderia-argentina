@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>
        .tamanio_boton {
            font-size: 6px;
        }

        .bloque-proceso {
            border: 1px solid #9c00f7;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #fafafa;
        }

        .bloque-producto {
            border-bottom: 1px dashed #ff0000;
            padding-bottom: 10px;
            margin-bottom: 10px;
            /* background: #ff7878; */
        }
    </style>
@endsection
@section('metadatos')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')

    <!-- ============================= -->
    <!-- MODAL RECETA -->
    <!-- ============================= -->
    <div class="modal fade" id="modalReceta" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-dialog-centered" style="max-width: 95%">

            <div class="modal-content">

                <div class="modal-header">

                    <h3 class="fw-bold">
                        FORMULARIO DE RECETA
                    </h3>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body scroll-y">

                    <form id="formularioReceta">

                        <input type="hidden" id="receta_id" name="receta_id" value="0">

                        <div class="row">

                            <div class="col-md-3">

                                <div class="fv-row mb-7">

                                    <label class="required fw-semibold fs-6 mb-2">
                                        Peso Kg
                                    </label>

                                    <input type="number" class="form-control form-control-sm" id="peso_kg" name="peso_kg" min="0" step="0.00001"
                                        oninput="calcularPesoReceta()" required>

                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="fv-row mb-7">

                                    <label class="fw-semibold fs-6 mb-2">
                                        Peso Gr
                                    </label>

                                    <input type="number" class="form-control form-control-sm" id="peso_gr" name="peso_gr" step="0.00001"
                                        readonly>

                                </div>

                            </div>

                        </div>


                        <!-- ============================================= -->
                        <!-- DATOS GENERALES -->
                        <!-- ============================================= -->

                        <div class="row">

                            <div class="col-md-4">

                                <div class="fv-row mb-5">

                                    <label class="required fw-semibold fs-6 mb-2">
                                        Nombre de la Receta
                                    </label>

                                    <input type="text" class="form-control form-control-sm" id="nombre" name="nombre"
                                        required>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="fv-row mb-5">

                                    <label class="required fw-semibold fs-6 mb-2">
                                        Tipo Tela
                                    </label>

                                    <select class="form-select form-select-sm" id="tipo_tela_id" name="tipo_tela_id"
                                        required>

                                        <option value="">
                                            Seleccione
                                        </option>

                                        @foreach($tipoTelas as $tipoTela)

                                        <option value="{{ $tipoTela->id }}">
                                            {{ $tipoTela->nombre }}
                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="fv-row mb-5">

                                    <label class="required fw-semibold fs-6 mb-2">
                                        Color Tela
                                    </label>

                                    <select class="form-select form-select-sm" id="color_tela_id" name="color_tela_id"
                                        required>

                                        <option value="">
                                            Seleccione
                                        </option>

                                        @foreach($colorTelas as $colorTela)

                                        <option value="{{ $colorTela->id }}">
                                            {{ $colorTela->nombre }}
                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-3">

                                <div class="fv-row mb-5">

                                    <label class="fw-semibold fs-6 mb-2">
                                        Nombre Tela
                                    </label>

                                    <select class="form-select form-select-sm" id="nombre_tela_id" name="nombre_tela_id">

                                        <option value="">
                                            Seleccione
                                        </option>

                                        @foreach($nombreTelas as $nombreTela)

                                        <option value="{{ $nombreTela->id }}">
                                            {{ $nombreTela->nombre }}
                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="fv-row mb-5">

                                    <label class="fw-semibold fs-6 mb-2">
                                        Prelavado
                                    </label>

                                    <select class="form-select form-select-sm" id="prelavado_id" name="prelavado_id">

                                        <option value="">
                                            Seleccione
                                        </option>

                                        @foreach($prelavados as $prelavado)

                                        <option value="{{ $prelavado->id }}">
                                            {{ $prelavado->nombre }}
                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="fv-row mb-5">

                                    <label class="fw-semibold fs-6 mb-2">
                                        Focalizado
                                    </label>

                                    <select class="form-select form-select-sm" id="focalizado_id" name="focalizado_id">

                                        <option value="">
                                            Seleccione
                                        </option>

                                        @foreach($focalizados as $focalizado)

                                        <option value="{{ $focalizado->id }}">
                                            {{ $focalizado->nombre }}
                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            <div class="col-md-3">

                                <div class="fv-row mb-5">

                                    <label class="fw-semibold fs-6 mb-2">
                                        Nevado
                                    </label>

                                    <select class="form-select form-select-sm" id="nevado_id" name="nevado_id">

                                        <option value="">
                                            Seleccione
                                        </option>

                                        @foreach($nevados as $nevado)

                                        <option value="{{ $nevado->id }}">
                                            {{ $nevado->nombre }}
                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-4">

                                <div class="fv-row mb-5">

                                    <label class="fw-semibold fs-6 mb-2">
                                        Característica
                                    </label>

                                    <select class="form-select form-select-sm" id="caracteristica_id"
                                        name="caracteristica_id">

                                        <option value="">
                                            Seleccione
                                        </option>

                                        @foreach($caracteristicas as $caracteristica)

                                        <option value="{{ $caracteristica->id }}">
                                            {{ $caracteristica->nombre }}
                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="fv-row mb-5">

                                    <label class="fw-semibold fs-6 mb-2">
                                        Tipo Proceso Principal
                                    </label>

                                    <select class="form-select form-select-sm" id="tipo_proceso_id" name="tipo_proceso_id">

                                        <option value="">
                                            Seleccione
                                        </option>

                                        @foreach($tipoProcesos as $tipoProceso)

                                        <option value="{{ $tipoProceso->id }}">
                                            {{ $tipoProceso->nombre }}
                                        </option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="fv-row mb-5">

                                    <label class="fw-semibold fs-6 mb-2">
                                        Descripción General
                                    </label>

                                    <input type="text" class="form-control form-control-sm" id="descripcion"
                                        name="descripcion">

                                </div>

                            </div>

                        </div>


                        <hr>


                        <!-- ============================================= -->
                        <!-- PROCESOS Y PRODUCTOS -->
                        <!-- ============================================= -->

                        <h4 class="fw-bold mb-5">
                            Procesos de la Receta
                        </h4>


                        <div id="kt_docs_repeater_nested">


                            <!-- ========================================= -->
                            <!-- REPEATER PROCESOS -->
                            <!-- ========================================= -->

                            <div class="form-group">

                                <div data-repeater-list="procesos">


                                    <div data-repeater-item class="bloque-proceso">


                                        <!-- CABECERA PROCESO -->

                                        <div class="row">

                                            <div class="col-md-2">

                                                <label class="form-label">
                                                    Orden Proceso
                                                </label>

                                                <input type="number" name="orden_proceso"
                                                    class="form-control form-control-sm" min="1">

                                            </div>


                                            <div class="col-md-7">

                                                <label class="form-label">
                                                    Proceso
                                                </label>

                                                <select name="proceso_id" class="form-select form-select-sm">

                                                    <option value="">
                                                        Seleccione
                                                    </option>

                                                    @foreach($tipoProcesos as $tipoProceso)

                                                    <option value="{{ $tipoProceso->id }}">
                                                        {{ $tipoProceso->nombre }}
                                                    </option>

                                                    @endforeach

                                                </select>

                                            </div>


                                            <div class="col-md-3">

                                                <button type="button" data-repeater-delete
                                                    class="btn btn-sm btn-light-danger mt-8 w-100">

                                                    <i class="ki-duotone ki-trash fs-5"></i>

                                                    Eliminar Proceso

                                                </button>

                                            </div>

                                        </div>


                                        <hr>


                                        <!-- ========================================= -->
                                        <!-- PRODUCTOS DEL PROCESO -->
                                        <!-- ========================================= -->

                                        <div class="inner-repeater">


                                            <div data-repeater-list="productos" class="mb-5">


                                                <div data-repeater-item class="bloque-producto">


                                                    <!-- FILA 1 -->

                                                    <div class="row align-items-end">


                                                        <div class="col-md-1">

                                                            <label class="form-label">
                                                                Orden
                                                            </label>

                                                            <input type="number" name="orden_producto"
                                                                class="form-control form-control-sm" min="1">

                                                        </div>


                                                        <div class="col-md-3">

                                                            <label class="form-label">
                                                                Producto
                                                            </label>

                                                            <select name="producto_id"
                                                                class="form-select form-select-sm producto">

                                                                <option value="">
                                                                    Seleccione
                                                                </option>

                                                                @foreach($productos as $producto)

                                                                <option value="{{ $producto->id }}"
                                                                    data-producto='@json($producto)'
                                                                    data-ingreso='@json($producto->ultimoIngreso)'>

                                                                    {{-- {{ $producto->nombre }} --}}
                                                                    {{ $producto->nombre.(($producto->ultimoIngreso)
                                                                    ? ' |
                                                                    '.$producto->ultimoIngreso?->precio_compra_g :
                                                                    '') }}

                                                                </option>

                                                                @endforeach

                                                            </select>

                                                        </div>


                                                        <div class="col-md-2">

                                                            <label class="form-label">
                                                                %
                                                            </label>

                                                            <input type="number" name="porcentaje"
                                                                class="form-control form-control-sm porcentaje" min="0"
                                                                step="0.00001">

                                                        </div>


                                                        <div class="col-md-2">

                                                            <label class="form-label">
                                                                Cantidad
                                                            </label>

                                                            <input type="number" name="cantidad"
                                                                class="form-control form-control-sm cantidad" min="0"
                                                                step="0.00001">

                                                        </div>


                                                        <div class="col-md-2">

                                                            <label class="form-label">
                                                                Total
                                                            </label>

                                                            <input type="number" name="total"
                                                                class="form-control form-control-sm total" min="0"
                                                                step="0.00001">

                                                        </div>


                                                        <div class="col-md-2">

                                                            <button type="button" data-repeater-delete
                                                                class="btn btn-sm btn-light-danger w-100">

                                                                <i class="ki-duotone ki-trash fs-5"></i>

                                                                Eliminar

                                                            </button>

                                                        </div>

                                                    </div>


                                                    <!-- FILA 2 DATOS TECNICOS -->

                                                    <div class="row mt-4">


                                                        <div class="col-md-2">

                                                            <label class="form-label">
                                                                Tiempo
                                                            </label>

                                                            <div class="input-group input-group-sm">

                                                                <input type="number" name="tiempo"
                                                                    class="form-control tiempo" step="0.00001">

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

                                                                <input type="number" name="temperatura"
                                                                    class="form-control temperatura" step="0.00001">

                                                                <span class="input-group-text">
                                                                    °C
                                                                </span>

                                                            </div>

                                                        </div>


                                                        <div class="col-md-2">

                                                            <label class="form-label">
                                                                PH
                                                            </label>

                                                            <input type="number" name="ph"
                                                                class="form-control form-control-sm ph" step="0.00001">

                                                        </div>


                                                        <div class="col-md-2">

                                                            <label class="form-label">
                                                                RB
                                                            </label>

                                                            <input type="number" name="rb"
                                                                class="form-control form-control-sm rb" step="0.00001">

                                                        </div>


                                                        <div class="col-md-4">

                                                            <label class="form-label">
                                                                Descripción Técnica
                                                            </label>

                                                            <input type="text" name="descripcion"
                                                                class="form-control form-control-sm descripcion_detalle">

                                                        </div>

                                                    </div>


                                                </div>

                                            </div>


                                            <button type="button" data-repeater-create class="btn btn-sm btn-light-primary">

                                                <i class="ki-duotone ki-plus fs-5"></i>

                                                Agregar Producto

                                            </button>


                                        </div>

                                    </div>

                                </div>

                            </div>


                            <!-- ========================================= -->
                            <!-- AGREGAR PROCESO -->
                            <!-- ========================================= -->

                            <div class="form-group mt-5">

                                <button type="button" data-repeater-create class="btn btn-light-primary">

                                    <i class="ki-duotone ki-plus fs-3"></i>

                                    Agregar Proceso

                                </button>

                            </div>

                        </div>


                        <hr>


                        <div class="row">

                            <div class="col-md-12">

                                <button class="btn btn-success btn-sm w-100" type="button" id="boton-guardar-receta"
                                    onclick="guardarReceta()">

                                    <i class="fa fa-save"></i>

                                    Guardar Receta

                                </button>

                            </div>

                        </div>


                    </form>

                </div>

            </div>

        </div>

    </div>

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxlg">
                <div class="card shadow-sm">
                    <div class="card-header bg-light-info py-4 d-flex align-items-center justify-content-between">
                        <h3 class="card-title fw-bold">Listado de Receta</h3>
                        <div class="card-toolbar">
                            @can('recetas.crear')
                            <button type="button" class="btn btn-primary btn-sm" onclick="modalNuevaReceta()">
                                <i class="fa fa-plus"></i> Nuevo Receta
                            </button>
                            @endcan
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

        $.ajaxSetup({
            // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $(document).ready(function() {

            ajaxListado();

            // ======================================
            // REPEATER
            // ======================================

            $('#kt_docs_repeater_nested').repeater({

                repeaters: [

                    {

                        selector: '.inner-repeater',

                        show: function() {

                            $(this).slideDown();

                        },

                        hide: function(deleteElement) {

                            $(this).slideUp(
                                deleteElement
                            );

                        }

                    }

                ],


                show: function() {

                    $(this).slideDown();

                },


                hide: function(deleteElement) {

                    $(this).slideUp(
                        deleteElement
                    );

                }

            });

            $(document).on('input','#formularioReceta .porcentaje',function () {

                let fila =$(this).closest('[data-repeater-item]');
                let pesoGr = parseFloat($('#peso_gr').val()) || 0;
                let porcentaje = parseFloat($(this).val()) || 0;

                /*
                * Verificar producto
                */
                let productoId = fila.find('.producto').val();
                if (!productoId) {
                    fila.find('.cantidad').val(0);
                    fila.find('.total').val(0);
                    return;
                }

                /*
                * cantidad =
                * pesoGr * porcentaje / 100
                */

                let cantidad = ( pesoGr * porcentaje ) / 100;

                fila.find('.cantidad').val(cantidad.toFixed(2));

                /*
                * Actualizamos total
                */
                calcularTotalReceta(fila);

            });

            $(document).on('input','#formularioReceta .cantidad',function () {

                    let fila =
                        $(this).closest(
                            '[data-repeater-item]'
                        );


                    let pesoGr =
                        parseFloat(
                            $('#peso_gr').val()
                        ) || 0;


                    let cantidad =
                        parseFloat(
                            $(this).val()
                        ) || 0;


                    if (pesoGr <= 0) {

                        fila
                            .find('.porcentaje')
                            .val(0);

                        return;
                    }


                    /*
                    * porcentaje =
                    * cantidad / pesoGr * 100
                    */

                    let porcentaje =
                        (
                            cantidad /
                            pesoGr
                        )
                        *
                        100;


                    fila
                        .find('.porcentaje')
                        .val(
                            porcentaje.toFixed(2)
                        );


                    calcularTotalReceta(
                        fila
                    );
            });

            $(document).on('change','#formularioReceta .producto',function () {

                    let fila =
                        $(this).closest(
                            '[data-repeater-item]'
                        );

                    fila
                        .find('.porcentaje')
                        .trigger('input');
            });
        });


        function ajaxListado(){
            let datos = {};
            $.ajax({
                url: "{{ route('receta.ajaxListado') }}",
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

        // ============================================================
        // NUEVA RECETA
        // ============================================================
        function modalNuevaReceta() {
            $('#receta_id').val(0);
            $('#nombre').val('');
            $('#tipo_tela_id').val('');
            $('#color_tela_id').val('');
            $('#nombre_tela_id').val('');
            $('#tipo_proceso_id').val('');
            $('#prelavado_id').val('');
            $('#focalizado_id').val('');
            $('#caracteristica_id').val('');
            $('#nevado_id').val('');
            $('#descripcion').val('');
            $('#peso_kg').val('');
            $('#peso_gr').val('');

            limpiarErrores();

            // limpiar todos los procesos
            $('[data-repeater-list="procesos"]').empty();

            // crear primer proceso
            $('#kt_docs_repeater_nested > .form-group:last [data-repeater-create]').click();

            $('#modalReceta').modal('show');
        }

        // ============================================================
        // GUARDAR
        // ============================================================

        function guardarReceta() {

            if (!$('#formularioReceta')[0].checkValidity()) {
                $('#formularioReceta')[0].reportValidity();
                return;
            }

            limpiarErrores();

            let datos = $('#formularioReceta').serializeArray();

            console.log(datos);

            $('#boton-guardar-receta').prop('disabled', true);

            $.ajax({

                url: "{{ route('receta.guardar') }}",

                method: "POST",

                data: datos,


                success: function(resultado) {


                    $('#boton-guardar-receta')
                        .prop('disabled', false);


                    if (resultado.estado) {


                        Swal.fire({

                            title:
                                "EL REGISTRO FUE EXITOSO.",

                            icon:
                                "success",

                            timer:
                                2500,

                            showConfirmButton:
                                false

                        });


                        ajaxListado();


                        $('#modalReceta')
                            .modal('hide');


                    } else {


                        Swal.fire({

                            icon:
                                'warning',

                            title:
                                'Error',

                            text:
                                resultado.message
                                ??
                                'No se pudo guardar la receta.'

                        });

                    }

                },


                error: function(xhr) {


                    $('#boton-guardar-receta')
                        .prop('disabled', false);


                    limpiarErrores();


                    if (xhr.status === 422) {


                        let errores =
                            xhr.responseJSON.errors;


                        for (let campo in errores) {


                            let mensaje =
                                errores[campo][0];


                            /*
                            Como los repeater generan:
                            procesos[0][productos][0][cantidad]

                            buscamos por name exacto.
                            */

                            let input =
                                $(`[name="${campo}"]`);


                            input.addClass(
                                "is-invalid"
                            );


                            input.after(

                                `<div class="invalid-feedback">
                                    ${mensaje}
                                </div>`

                            );

                        }


                        Swal.fire({

                            icon:
                                'warning',

                            title:
                                'Verifique los datos',

                            text:
                                'Existen campos incorrectos.'

                        });


                    } else {


                        Swal.fire({

                            icon:
                                'error',

                            title:
                                'Error',

                            text:
                                'Ocurrió un error inesperado.'

                        });

                    }

                }

            });

        }

        // ============================================================
        // EDITAR RECETA
        // ============================================================

        function editarReceta(receta) {


            console.log(
                'RECETA:',
                receta
            );


            // =====================================
            // CABECERA
            // =====================================

            $('#receta_id')
                .val(receta.id);


            $('#nombre')
                .val(receta.nombre);


            $('#tipo_tela_id')
                .val(receta.tipo_tela_id);


            $('#color_tela_id')
                .val(receta.color_tela_id);


            $('#nombre_tela_id')
                .val(receta.nombre_tela_id);


            $('#tipo_proceso_id')
                .val(receta.tipo_proceso_id);


            $('#prelavado_id')
                .val(receta.prelavado_id);


            $('#focalizado_id')
                .val(receta.focalizado_id);


            $('#caracteristica_id')
                .val(receta.caracteristica_id);


            $('#nevado_id')
                .val(receta.nevado_id);


            $('#descripcion')
                .val(receta.descripcion);

            $('#peso_kg').val(receta.peso_kg ?? 0);
            $('#peso_gr').val(receta.peso_gr ?? 0);

            // =====================================
            // AGRUPAR DETALLES
            // =====================================

            let procesos = [];


            /*
                * Primero ordenamos:
                *
                * 1. orden_proceso
                * 2. orden_producto
                */

            let detallesOrdenados =
                [...receta.detalles]
                .sort(function(a, b) {


                    let ordenProcesoA =
                        parseInt(
                            a.orden_proceso
                            ??
                            999999
                        );


                    let ordenProcesoB =
                        parseInt(
                            b.orden_proceso
                            ??
                            999999
                        );


                    if (
                        ordenProcesoA
                        !==
                        ordenProcesoB
                    ) {

                        return (
                            ordenProcesoA
                            -
                            ordenProcesoB
                        );

                    }


                    let ordenProductoA =
                        parseInt(
                            a.orden_producto
                            ??
                            999999
                        );


                    let ordenProductoB =
                        parseInt(
                            b.orden_producto
                            ??
                            999999
                        );


                    return (
                        ordenProductoA
                        -
                        ordenProductoB
                    );

                });



            // =====================================
            // AGRUPAMOS POR PROCESO
            // =====================================

            detallesOrdenados.forEach(
                function(d) {


                    let proceso =
                        procesos.find(
                            function(p) {

                                return (
                                    p.tipo_proceso_id
                                    ==
                                    d.tipo_proceso_id
                                );

                            }
                        );


                    // si proceso no existe
                    if (!proceso) {


                        proceso = {

                            tipo_proceso_id:
                                d.tipo_proceso_id,

                            orden_proceso:
                                d.orden_proceso,

                            productos:
                                []

                        };


                        procesos.push(
                            proceso
                        );

                    }


                    // producto
                    proceso.productos.push({

                        producto_id:
                            d.producto_id,

                        orden_producto:
                            d.orden_producto,

                        porcentaje:
                            d.porcentaje,

                        cantidad:
                            d.cantidad,

                        total:
                            d.total,

                        tiempo:
                            d.tiempo,

                        temperatura:
                            d.temperatura,

                        ph:
                            d.ph,

                        rb:
                            d.rb,

                        descripcion:
                            d.descripcion

                    });

                }
            );


            console.log(
                'PROCESOS:',
                procesos
            );


            // =====================================
            // LIMPIAR REPEATER
            // =====================================

            $('[data-repeater-list="procesos"]')
                .empty();



            // =====================================
            // CREAR PROCESOS
            // =====================================

            procesos.forEach(
                function(proceso) {


                    // crear proceso
                    $('#kt_docs_repeater_nested > .form-group:last [data-repeater-create]')
                        .click();


                    let procesoRow =
                        $('[data-repeater-list="procesos"] > [data-repeater-item]')
                        .last();



                    // =================================
                    // ORDEN PROCESO
                    // =================================

                    procesoRow
                        .find(
                            'input[name^="procesos"][name$="[orden_proceso]"], input[name="orden_proceso"]'
                        )
                        .val(
                            proceso.orden_proceso
                        );



                    // =================================
                    // PROCESO
                    // =================================

                    procesoRow
                        .find(
                            'select[name^="procesos"][name$="[proceso_id]"], select[name="proceso_id"]'
                        )
                        .val(
                            proceso.tipo_proceso_id
                        )
                        .trigger(
                            'change'
                        );



                    // =================================
                    // SUB REPEATER PRODUCTOS
                    // =================================

                    let subRepeaterList =
                        procesoRow.find(
                            '[data-repeater-list="productos"]'
                        );


                    // quitar producto inicial
                    subRepeaterList.empty();



                    // ordenar productos
                    proceso.productos.sort(
                        function(a, b) {

                            return (
                                parseInt(
                                    a.orden_producto
                                    ??
                                    999999
                                )
                                -
                                parseInt(
                                    b.orden_producto
                                    ??
                                    999999
                                )
                            );

                        }
                    );



                    // =================================
                    // CREAR PRODUCTOS
                    // =================================

                    proceso.productos.forEach(
                        function(d) {


                            procesoRow
                                .find(
                                    '.inner-repeater [data-repeater-create]'
                                )
                                .click();


                            let productoRow =
                                subRepeaterList
                                .find(
                                    '[data-repeater-item]'
                                )
                                .last();



                            // ORDEN PRODUCTO

                            productoRow
                                .find(
                                    'input[name*="[orden_producto]"], input[name="orden_producto"]'
                                )
                                .val(
                                    d.orden_producto
                                );



                            // PRODUCTO

                            productoRow
                                .find(
                                    'select[name*="[producto_id]"], select[name="producto_id"]'
                                )
                                .val(
                                    d.producto_id
                                );



                            // PORCENTAJE

                            productoRow
                                .find(
                                    'input[name*="[porcentaje]"], input[name="porcentaje"]'
                                )
                                .val(
                                    d.porcentaje
                                );



                            // CANTIDAD

                            productoRow
                                .find(
                                    'input[name*="[cantidad]"], input[name="cantidad"]'
                                )
                                .val(
                                    d.cantidad
                                );



                            // TOTAL

                            productoRow
                                .find(
                                    'input[name*="[total]"], input[name="total"]'
                                )
                                .val(
                                    d.total
                                );



                            // TIEMPO

                            productoRow
                                .find(
                                    'input[name*="[tiempo]"], input[name="tiempo"]'
                                )
                                .val(
                                    d.tiempo
                                );



                            // TEMPERATURA

                            productoRow
                                .find(
                                    'input[name*="[temperatura]"], input[name="temperatura"]'
                                )
                                .val(
                                    d.temperatura
                                );



                            // PH

                            productoRow
                                .find(
                                    'input[name*="[ph]"], input[name="ph"]'
                                )
                                .val(
                                    d.ph
                                );



                            // RB

                            productoRow
                                .find(
                                    'input[name*="[rb]"], input[name="rb"]'
                                )
                                .val(
                                    d.rb
                                );



                            // DESCRIPCION

                            productoRow
                                .find(
                                    'input[name*="[descripcion]"], input[name="descripcion"]'
                                )
                                .val(
                                    d.descripcion
                                );


                        }
                    );

                }
            );


            // =====================================
            // MOSTRAR MODAL
            // =====================================

            $('#modalReceta')
                .modal('show');

        }

        // ============================================================
        // ELIMINAR RECETA
        // ============================================================
        function eliminarReceta(receta) {


            Swal.fire({

                title:
                    "¿Quieres eliminar esta receta?",

                text:
                    "¡No podrás recuperarla!",

                icon:
                    "warning",

                showCancelButton:
                    true,

                confirmButtonColor:
                    '#3085d6',

                cancelButtonColor:
                    '#d33',

                confirmButtonText:
                    "Sí, borrar",

                cancelButtonText:
                    "No, cancelar",

                reverseButtons:
                    true


            }).then((result) => {


                if (result.isConfirmed) {


                    $.ajax({

                        url:
                            "{{ route('receta.eliminar') }}",

                        method:
                            "POST",

                        data: {

                            receta:
                                receta

                        },


                        success: function(resultado) {


                            if (resultado.estado) {


                                ajaxListado();


                                Swal.fire(

                                    'Eliminado!',

                                    'La receta fue eliminada correctamente.',

                                    'success'

                                );


                            } else {


                                Swal.fire(

                                    'Error',

                                    resultado.message
                                    ??
                                    'No se pudo eliminar la receta.',

                                    'error'

                                );

                            }

                        },


                        error: function(xhr) {


                            Swal.fire({

                                icon:
                                    'error',

                                title:
                                    'Error',

                                text:
                                    'Ocurrió un error inesperado.'

                            });

                        }

                    });


                }

            });

        }


        // ============================================================
        // LIMPIAR ERRORES
        // ============================================================
        function limpiarErrores() {

            $('.is-invalid')
                .removeClass(
                    'is-invalid'
                );


            $('.invalid-feedback')
                .remove();

        }

        function imprimirReceta(receta_id) {
            let url = "{{ route('receta.pdf', ':id') }}";
            url = url.replace(':id',receta_id);
            window.open(url,'_blank');
        }

        function calcularPesoReceta() {

            let pesoKg =
                parseFloat(
                    $('#peso_kg').val()
                ) || 0;

            let pesoGr =
                pesoKg * 1000;

            $('#peso_gr').val(
                pesoGr.toFixed(2)
            );


            /*
            * Si cambia el peso,
            * recalculamos todas las cantidades
            * usando el porcentaje existente.
            */
            $('#formularioReceta')
                .find('.porcentaje')
                .each(function () {

                    $(this).trigger('input');

                });
        }

        function calcularTotalReceta(fila) {

            let dataIngreso =
                fila
                    .find(
                        '.producto option:selected'
                    )
                    .attr(
                        'data-ingreso'
                    );


            if (!dataIngreso) {

                fila
                    .find('.total')
                    .val(0);

                return;
            }


            let ingreso = null;


            try {

                ingreso =
                    JSON.parse(
                        dataIngreso
                    );

            } catch (error) {

                ingreso =
                    null;
            }


            if (
                ingreso != null
                &&
                ingreso.precio_compra_g != null
            ) {

                let cantidad =
                    parseFloat(
                        fila
                            .find('.cantidad')
                            .val()
                    ) || 0;


                let precioGr =
                    parseFloat(
                        ingreso.precio_compra_g
                    ) || 0;


                let total =
                    cantidad *
                    precioGr;


                fila
                    .find('.total')
                    .val(
                        total.toFixed(2)
                    );

            } else {

                fila
                    .find('.total')
                    .val(0);
            }
        }

    </script>
@endsection
