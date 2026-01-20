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
    <div class="modal fade" id="modalProducto" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">FORMULARIO DE PRODUCTO <span class="text-info" id="nombre_busqueda"></span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formularioProducto">
                        <input type="hidden" name="id" id="id" value="0">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Proveedor</label>
                                    <select class="form-control form-control-sm" id="proveedor_id" name="proveedor_id"
                                        required>
                                        <option value="">Seleccione un proveedor</option>
                                        @forelse($proveedores as $proveedor)
                                            <option value="{{ $proveedor->id }}">{{ $proveedor->razon_social }}</option>
                                        @empty
                                            <h4 class="text-danger">No hay proveedores registrado</h4>
                                        @endforelse
                                    </select>
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Nombre</label>
                                    <input type="text" class="form-control form-control-sm" id="nombre"
                                        name="nombre">
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Tipo</label>
                                    {{-- <input type="text" class="form-control form-control-sm" id="tipo" name="tipo"
                                        maxlength="13"> --}}
                                        <select class="form-select form-select-sm" name="tipo" id="tipo">
                                            <option value="LITRO">LITRO</option>
                                            <option value="KILOGRAMO">KILOGRAMO</option>
                                        </select>
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Codigo</label>
                                    <input type="text" class="form-control form-control-sm" id="codigo"
                                        name="codigo">
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Minimo Stock</label>
                                    <input type="text" class="form-control form-control-sm" id="minimo_stock"
                                        name="minimo_stock" maxlength="8">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm w-100 btn-success" onclick="guardarProducto()">Guardar</button>
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
    <div class="modal fade" id="modalStockSucursal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">STOCK POR SUCUASALES <span class="text-info" id="nombreProductoModal"></span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <div id="tabla_stock"></div>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">

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
    <div class="modal fade" id="modalIngreso" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">INGRESO DE STOCK:  <span class="text-info" id="nombreProductoModal-1"></span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formularioIngreso">
                        <input type="hidden" name="id" id="id" value="0">
                        <input type="hidden" name="idProd" id="idProd" >
                        <input type="hidden" name="idSuc" id="idSuc" >
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Sucursal</label>
                                    <input type="text" class="form-control form-control-sm" id="sucursal"
                                        name="sucursal" readonly>
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Ingrese la Cantidad</label>
                                    <input type="number" class="form-control form-control-sm" id="cantidad_ingreso"
                                        name="cantidad_ingreso">
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Fecha de Ingreso</label>
                                    <input type="text" class="form-control form-control-sm" id="fecha_ingreso"
                                        name="fecha_ingreso" value="{{ date('Y-m-d') }}" readonly>
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Descripcion</label>
                                    <textarea class="form-control form-control-sm" id="descripcion"
                                        name="descripcion"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm w-100 btn-success" onclick="guardarIngreso()">Guardar</button>
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
    <div class="modal fade" id="modalSalida" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header" id="kt_modal_add_user_header">
                    <h3 class="fw-bold">SALIDA DE STOCK:  <span class="text-info" id="nombreProductoModal-2"></span></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body scroll-y">
                    <form id="formularioSalida">
                        <input type="hidden" name="id" id="id" value="0">
                        <input type="hidden" name="idProds" id="idProds" >
                        <input type="hidden" name="idSucs" id="idSucs" >
                        <div class="row">
                            <div class="col-md-12">
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Sucursal</label>
                                    <input type="text" class="form-control form-control-sm" id="sucursales"
                                        name="sucursales" readonly>
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Salida la Cantidad</label>
                                    <input type="number" class="form-control form-control-sm" id="cantidad_salida"
                                        name="cantidad_salida">
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Fecha de Salida</label>
                                    <input type="text" class="form-control form-control-sm" id="fecha_salida"
                                        name="fecha_salida" value="{{ date('Y-m-d') }}" readonly>
                                </div>
                                <div class="fv-row mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">Descripcion</label>
                                    <textarea class="form-control form-control-sm" id="descripcion"
                                        name="descripcion"></textarea>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-md-12">
                            <button class="btn btn-sm w-100 btn-success" onclick="guardarSalida()">Guardar</button>
                        </div>
                    </div>
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
                        <h3 class="card-title fw-bold">Listado de Producto</h3>
                        <div class="card-toolbar">
                            <button type="button" class="btn btn-primary btn-sm" onclick="modalNuevoProducto()">
                                <i class="fa fa-plus"></i> Nuevo Producto
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
        });


        function ajaxListado() {
            let datos = {};
            $.ajax({
                url: "{{ route('producto.ajaxListado') }}",
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

        function modalNuevoProducto() {
            $('#minimo_stock').val('')
            $('#codigo').val('')
            $('#tipo').val('')
            $('#nombre').val('')
            $('#proveedor_id').val('')
            $('#id').val(0)
            $('#modalProducto').modal('show')
        }

        function guardarProducto() {
            let datos = $('#formularioProducto').serializeArray();

            $.ajax({
                url: "{{ route('producto.guardarProducto') }}",
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
                        $('#modalProducto').modal('hide');
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
        }

        function editarProducto(producto) {

            $('#minimo_stock').val(producto.minimo_stock)
            $('#codigo').val(producto.codigo)
            $('#tipo').val(producto.tipo)
            $('#nombre').val(producto.nombre)
            $('#proveedor_id').val(producto.proveedor_id)
            $('#id').val(producto.id)
            $('#modalProducto').modal('show')

        }

        function abrirStock(productoId, nombre) {

            document.getElementById('nombreProductoModal').textContent = nombre;
            document.getElementById('nombreProductoModal-1').textContent = nombre;
            document.getElementById('nombreProductoModal-2').textContent = nombre;

            $.ajax({
                url: "{{ route('movimiento.ajaxListado') }}", // Ruta que veremos después
                type: 'GET',
                data: { productoId: productoId,
                    nombre: nombre
                },
                success: function(res) {

                    if (res.estado) {
                        $('#tabla_stock').html(res.data
                            .stock); // Donde 'stock' es el HTML renderizado por AJAX
                        $('#modalStockSucursal').modal('show'); // Abrir modal
                    } else {
                        Swal.fire('Error', 'No se pudo obtener el stock', 'error');
                    }
                },
                error: function() {
                    Swal.fire('Error', 'Ocurrió un error al obtener el stock', 'error');
                }
            });
        }

        function modalIngreso(productoId, sucursalId, nombreSuc) {

            document.getElementById('sucursal').value = nombreSuc;
            document.getElementById('idSuc').value = sucursalId;
            document.getElementById('idProd').value = productoId;

            $('#descripcion').val('')
            $('#cantidad_ingreso').val('')
            $('#id').val(0)
            $('#modalIngreso').modal('show')
        }

        function guardarIngreso() {
            let datos = $('#formularioIngreso').serializeArray();

            $.ajax({
                url: "{{ route('movimiento.guardarIngreso') }}",
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
                        $('#modalIngreso').modal('hide');
                        $('#modalStockSucursal').modal('hide');
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
        }

        function modalSalida(productoId, sucursalId, nombreSuc) {

            document.getElementById('sucursales').value = nombreSuc;
            document.getElementById('idSucs').value = sucursalId;
            document.getElementById('idProds').value = productoId;

            $('#descripcion').val('')
            $('#cantidad_salida').val('')
            $('#id').val(0)
            $('#modalSalida').modal('show')
        }

        function guardarSalida() {
            let datos = $('#formularioSalida').serializeArray();

            $.ajax({
                url: "{{ route('movimiento.guardarSalida') }}",
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
                        $('#modalSalida').modal('hide');
                        $('#modalStockSucursal').modal('hide');
                    } else {
                        Swal.fire({
                            title: "LA SALIDA ES MAYOR AL STOCK",
                            icon: "error",
                            timer: 3000, // Se cierra en 3 segundos
                            showConfirmButton: false
                        });
                        ajaxListado();
                        $('#modalSalida').modal('hide');
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

        function eliminarProducto(producto, nombre) {
            Swal.fire({
                title: "¿Quieres eliminar " + nombre + "?",
                text: "¡No podrás recuperarlo!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: "Sí, borrar",
                cancelButtonText: "No, cancelar",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    $.ajax({
                        url: "{{ route('producto.eliminarProducto') }}",
                        method: "POST",
                        data: {
                            producto: producto
                        },
                        success: function(resultado) {
                            if (resultado.estado) {
                                ajaxListado(); // resga el listado
                                Swal.fire(
                                    'Eliminado!',
                                    'La producto ha sido eliminado correctamente.',
                                    'success'
                                );
                            } else {
                                Swal.fire(
                                    'Error',
                                    resultado.message || 'No se pudo eliminar la producto.',
                                    'error'
                                );
                            }
                        },
                        error: function(xhr) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Ocurrió un error inesperado.'
                            });
                        }
                    });


                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire(
                        'Cancelado',
                        'La operación fue cancelada',
                        'info'
                    );
                }
            });
        }
    </script>
@endsection
