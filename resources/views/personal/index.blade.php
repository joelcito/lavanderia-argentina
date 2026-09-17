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

    <div class="container">
        <h2>Control de Personal</h2>
        <div id="tabla-personal">
            <p>Cargando...</p><label class="form-label">Descuento</label>
        </div>
    </div>

    <div class="modal fade" id="modalPersonal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body" id="contenido-modal">
                    Cargando...
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="modalProduccion">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 id="tituloProduccion">Pago Producción</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="user_id">
                    <input type="hidden" id="tipo_produccion">

                    <div class="row">
                        <div class="col">
                            <label>Fecha inicio</label>
                            <input type="date" id="inicio" class="form-control">
                        </div>

                        <div class="col">
                            <label>Fecha fin</label>
                            <input type="date" id="fin" class="form-control">
                        </div>
                    </div>
                    <br>
                    <button class="btn btn-primary" id="btnConsultarProduccion">
                        Consultar
                    </button>
                    <hr>
                    <div id="resultadoProduccion"></div>

                </div>

            </div>
        </div>
    </div>

@endsection
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $(document).ready(function () {
            cargarTabla();
        });

        function cargarTabla() {
            $.ajax({
                url: "{{ route('personal.ajaxListado') }}",
                method: "GET",
                success: function (data) {
                    $('#tabla-personal').html(data);
                }
            });
        }

        $(document).on('click', '.btn-ver', function () {
            let userId = $(this).data('id');
            $('#contenido-modal').html('Cargando...');
            $('#modalPersonal').modal('show');
            $.get(`/control-personal/${userId}`, function (data) {
                $('#contenido-modal').html(data);
                setTimeout(() => {
                    $('.tab-link').first().click();
                }, 200);
            });
        });

        function guardarConfiguracion(formId = '#form-config') {
            let datos = $(formId).serialize();
            $.ajax({
                url: "{{ route('personal.config.update') }}",
                method: "POST",
                data: datos,
                success: function () {

                    Swal.fire({
                        icon: 'success',
                        title: 'Guardado correctamente',
                        timer: 1500,
                        showConfirmButton: false
                    });

                    cargarTabla();
                    $('#modalPersonal').modal('hide');

                },
                error: function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al guardar'
                    });
                }
            });
        }

        function guardarAsistencia() {
            let fecha = $('#fecha').val();
            let horaEntrada = $('#hora_entrada').val();
            let horaSalida = $('#hora_salida').val();


            if (!fecha || !horaEntrada || !horaSalida) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Completa todos los campos'
                });
                return;
            }

            if (horaSalida <= horaEntrada) {
                Swal.fire({
                    icon: 'error',
                    title: 'Hora inválida',
                    text: 'La salida debe ser mayor que la entrada'
                });
                return;
            }

            let datos = {
                _token: $('meta[name="csrf-token"]').attr('content'),
                id: window.ASISTENCIA_ID ?? null,
                user_id: window.USER_ID,
                fecha: fecha,
                hora_entrada: horaEntrada,
                hora_salida: horaSalida
            };

            $.post('/asistencias/store', datos, function (res) {

                if (!res.ok) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message || 'No se pudo guardar'
                    });
                    return;
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Guardado correctamente',
                    timer: 1200,
                    showConfirmButton: false
                });

                cargarAsistencias();

            }).fail(function (xhr) {

                let mensaje = 'Error al guardar';
                if (xhr.responseJSON) {
                    if (xhr.responseJSON.message) {
                        mensaje = xhr.responseJSON.message;
                    }
                    if (xhr.responseJSON.errors) {
                        mensaje = Object.values(xhr.responseJSON.errors)
                            .flat()
                            .join('\n');
                    }
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje
                });

            });
        }

        function editarAsistencia(data) {

            $('#fecha').val(data.fecha);
            $('#hora_entrada').val(data.entrada);
            $('#hora_salida').val(data.salida);
            window.ASISTENCIA_ID = data.id;
        }

        function eliminarAsistencia(id) {
            Swal.fire({
                title: '¿Eliminar?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post('/asistencias/delete', {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: id
                    }, function () {
                        $('.tab-link[data-tab="asistencias"]').click();
                        Swal.fire('Eliminado', '', 'success');
                        cargarAsistencias();
                    });
                }
            });
        }

        function formatearFecha(fecha) {
            let dias = ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'];

            let partes = fecha.split('-');
            let y = parseInt(partes[0]);
            let m = parseInt(partes[1]) - 1;
            let d = parseInt(partes[2]);
            let f = new Date(y, m, d);

            let dia = dias[f.getDay()];

            return `${dia} ${String(d).padStart(2, '0')}/${String(m + 1).padStart(2, '0')}/${y}`;
        }

        //resumen

        function cargarResumen() {

            let inicio = $('#fecha_inicio').val();
            let fin = $('#fecha_fin').val();


            if (!inicio || !fin) {
                Swal.fire('Error', 'Selecciona ambas fechas', 'warning');
                return;
            }
            $.get(`/control-personal/resumen-fechas/${window.USER_ID}`, {
                inicio: inicio,
                fin: fin
            }, function (data) {
                let bloqueado = data.pago_realizado || data.total_final <= 0;

                let horas = Math.floor(data.total_minutos / 60);
                let minutos = Math.floor(data.total_minutos % 60);
                let tiempoTexto = `${horas}h ${minutos}m`;
                let html = `
                                            <table class="table table-sm table-bordered text-center align-middle">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>Horas</th>
                                                        <th>Días</th>
                                                        <th>Pago/H</th>
                                                        <th>Pago/Min</th>
                                                        <th>Total</th>
                                                        <th>Adelantos</th>
                                                        <th>Desc</th>

                                                        <th>Final</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td>${data.total_horas_texto}</td>
                                                        <td>${data.dias}</td>
                                                        <td>Bs ${data.pago_hora}</td>
                                                        <td>Bs ${data.pago_minuto}</td>
                                                        <td class="text-success"><b>Bs ${data.pago_total}</b></td>
                                                        <td>Bs ${data.adelantos}</td>
                                                        <td class="text-danger">Bs ${data.descuentos}</td>

                                                        <td class="text-primary"><b>Bs ${data.total_final}</b></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div class="row g-2 align-items-end" mb-3>

                                            <div class="col-md-2">
                                                <label class="form-label">Adelanto</label>
                                                <input type="number" id="adelanto_monto" class="form-control form-control-sm" ${bloqueado ? 'disabled' : ''}>
                                            </div>
                                            <div class="col-md-2">
                                            <label class="form-label">Descripción</label>
                                                <textarea id="adelanto_desc" class="form-control" rows="1"
                                                        ${bloqueado ? 'disabled' : ''}></textarea>
                                            </div>

                                            <div class="col-md-2">
                                                <button id="btn-guardar-adelanto" class="btn btn-success btn-sm w-100" ${bloqueado ? 'disabled' : ''}>
                                                    Guardar
                                                </button>
                                            </div>

                                            <div class="col-md-2">
                                                <label class="form-label">Descuento</label>
                                                <select id="deuda_id"
                                                class="form-control form-control-sm">

                                                <option value="">
                                                    Seleccionar deuda
                                                </option>

                                            </select>
                                                <input type="number" id="descuento_monto" class="form-control form-control-sm" ${bloqueado ? 'disabled' : ''}>
                                            </div>


                                            <div class="col-md-2">
                                                <label class="form-label">
                                                    Saldo pendiente
                                                </label>
                                                <input
                                                    type="text"
                                                    id="saldo_deuda"
                                                    class="form-control form-control-sm"
                                                    readonly>
                                            </div>

                                            <div class="col-md-2">
                                                <button id="btn-guardar-descuento" class="btn btn-warning btn-sm w-100" ${bloqueado ? 'disabled' : ''}>
                                                    Guardar
                                                </button>
                                            </div>
                                        </div>
                                        <hr/>

                                    ${data.pago_realizado ? `
                                        <div class="alert alert-success text-center" mb-3>
                                            ✅ Este periodo ya fue pagado <br>
                                            <strong>Bs ${data.pago_info.monto}</strong>
                                        </div>
                                    ` : ''}

                                    <div class="text-center mb-3">
                                        ${data.pago_realizado
                        ? `<button class="btn btn-secondary" disabled>YA PAGADO</button>`
                        : `<button class="btn btn-success" id="btn-pagar">PAGAR TOTAL</button>`
                    }
                                        </div>
                                                <h5 class="mt-4">Asistencia</h5>
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Día</th>
                                                            <th>Horas</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                `;

                // ASISTENCIA
                data.detalle.forEach(d => {
                    html += `
                        <tr>
                            <td>${d.fecha}</td>
                            <td>${d.dia}</td>
                            <td>${d.horas_texto}</td>
                        </tr>
                    `;
                });

                html += `</tbody></table>`;

                // TABLA AJUSTES
                html += `
                                    <h5 class="mt-4">Movimientos (Adelantos / Descuentos)</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Tipo</th>
                                                <th>Descripcion</th>
                                                <th>Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                    `;

                data.ajustes.forEach(a => {

                    let color = a.tipo_pago === 'adelanto' ? 'danger' : 'warning';

                    html += `
                                        <tr>
                                            <td>${a.fecha}</td>
                                            <td><span class="badge bg-${color}">${a.tipo_pago}</span></td>
                                            <td>${a.descripcion ?? '-'}</td>
                                            <td class="text-${color}">- Bs ${a.monto}</td>
                                        </tr>
                                    `;
                });

                html += `</tbody></table>`;
                $('#resultado-resumen').html(html);
                cargarDeudasUsuario();
            });

        }



        function cargarDeudasUsuario() {

            $.get(`/deudas/${window.USER_ID}`, function (data) {

                let html = `
                                    <option value="">
                                        Seleccionar deuda
                                    </option>
                                `;

                data.forEach(d => {

                    if (d.estado === 'PENDIENTE') {

                        html += `

                                            <option
                                                value="${d.id}"
                                                data-saldo="${d.saldo_pendiente}">

                                                ${d.concepto}
                                                - Debe Bs ${d.saldo_pendiente}

                                            </option>
                                        `;
                    }
                });

                $('#deuda_id').html(html);

            });
        }





        // PAGAR

        $(document).on('click', '#btn-guardar-adelanto', function () {

            let monto = $('#adelanto_monto').val();
            let inicio = $('#fecha_inicio').val();
            let fin = $('#fecha_fin').val();

            if (!monto || monto <= 0) {
                Swal.fire('Error', 'El total es 0', 'warning');
                return;
            }
            $.get(`/control-personal/resumen-fechas/${window.USER_ID}`, {
                inicio, fin
            }, function (data) {

                let descripcion = $('#adelanto_desc').val() || 'Adelanto';

                $.post('/control-personal/pagos-personal/store', {
                    _token: '{{ csrf_token() }}',
                    user_id: window.USER_ID,
                    monto: monto,
                    tipo_pago: 'adelanto',
                    descripcion: descripcion,

                    fecha_inicio: inicio,
                    fecha_fin: fin,

                    total_horas: data.total_horas,
                    total_minutos: data.total_minutos,
                    monto_calculado: data.pago_total,

                    pago_diario_usado: data.pago_diario,
                    horas_base_usado: data.horas_base,

                    fecha: new Date().toISOString().slice(0, 10)

                }).done(function () {

                    Swal.fire('OK', 'Adelanto guardado', 'success');
                    cargarResumen();
                    cargarDeudasUsuario();

                    $('#adelanto_monto').val('');
                    $('#descuento_monto').val('');

                });
            });
        });


        $(document).on('click', '#btn-guardar-descuento', function () {

            let monto = $('#descuento_monto').val();
            let inicio = $('#fecha_inicio').val();
            let fin = $('#fecha_fin').val();


            let deuda_id = $('#deuda_id').val();

            if (!deuda_id) {

                Swal.fire(
                    'Error',
                    'Selecciona una deuda',
                    'warning'
                );

                return;
            }

            if (!monto || monto <= 0) {
                Swal.fire('Error', 'El total es 0', 'warning');
                return;
            }

            $.get(`/control-personal/resumen-fechas/${window.USER_ID}`, {
                inicio, fin
            }, function (data) {

                let descripcion = $('#deuda_id option:selected').text();

                $.post('/control-personal/pagos-personal/store', {
                    _token: '{{ csrf_token() }}',
                    user_id: window.USER_ID,
                    deuda_id: $('#deuda_id').val(),
                    monto: monto,
                    tipo_pago: 'descuento',
                    descripcion: descripcion,

                    fecha_inicio: inicio,
                    fecha_fin: fin,

                    total_horas: data.total_horas,
                    total_minutos: data.total_minutos,
                    monto_calculado: data.pago_total,

                    pago_diario_usado: data.pago_diario,
                    horas_base_usado: data.horas_base,

                    fecha: new Date().toISOString().slice(0, 10)

                }).done(function () {

                    Swal.fire('OK', 'Descuento guardado', 'success');
                    cargarResumen();
                    cargarDeudasUsuario();
                    $('#adelanto_monto').val('');
                    $('#descuento_monto').val('');

                });
            });
        });

        $(document).on('click', '#btn-pagar', function () {

            let inicio = document.getElementById('fecha_inicio')?.value;
            let fin = document.getElementById('fecha_fin')?.value;

            if (!inicio || !fin) {
                Swal.fire('Error', 'Selecciona fechas', 'warning');
                return;
            }

            $.get(`/control-personal/resumen-fechas/${window.USER_ID}`, {
                inicio: inicio,
                fin: fin
            }, function (data) {

                if (data.total_final <= 0) {
                    Swal.fire('Error', 'El total es 0', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Confirmar pago',
                    text: `Se pagará Bs ${data.total_final}`,
                    icon: 'question',
                    showCancelButton: true
                }).then(result => {

                    if (result.isConfirmed) {

                        $.post('/control-personal/pagos-personal/store', {
                            _token: '{{ csrf_token() }}',
                            user_id: window.USER_ID,
                            monto: data.total_final,
                            tipo_pago: 'salario',
                            descripcion: 'Pago de sueldo',

                            fecha_inicio: inicio,
                            fecha_fin: fin,

                            total_horas: data.total_horas,
                            total_minutos: data.total_minutos,
                            monto_calculado: data.pago_total,
                            total_descuentos: data.adelantos + data.descuentos,

                            pago_diario_usado: data.pago_diario,
                            horas_base_usado: data.horas_base,

                            fecha: new Date().toISOString().slice(0, 10)

                        }).done(function () {
                            Swal.fire({
                                icon: 'success',
                                title: 'Pago registrado',
                                timer: 1500,
                                showConfirmButton: false
                            }).then(() => {

                                $('#modalPersonal').modal('hide');
                                cargarTabla();
                            });
                        }).fail(function (err) {

                            let msg = err.responseJSON?.error || 'Error al pagar';
                            Swal.fire('Error', msg, 'error');
                        });
                    }
                });
            });
        });


        function abrirModalResumen() {

            let inicio = $('#fecha_inicio').val();
            let fin = $('#fecha_fin').val();

            $.get(`/control-personal/resumen-fechas/${window.USER_ID}`, {
                inicio, fin
            }, function (data) {

                let html = `
                                    <div>
                                        <p><b>Total:</b> Bs ${data.pago_total}</p>
                                        <p><b>Adelantos:</b> Bs ${data.adelantos}</p>
                                        <p><b>Descuentos:</b> Bs ${data.descuentos}</p>
                                        <hr>
                                        <h4>Total Final: Bs ${data.total_final}</h4>

                                        <button class="btn btn-danger" onclick="agregarAjuste('adelanto')">+ Adelanto</button>
                                        <button class="btn btn-secondary" onclick="agregarAjuste('descuento')">+ Descuento</button>
                                    </div>
                                `;

                Swal.fire({
                    title: 'Resumen de Pago',
                    html: html,
                    showConfirmButton: false
                });

            });
        }

        function agregarAjuste(tipo) {

            Swal.fire({
                title: 'Monto',
                input: 'number',
                showCancelButton: true
            }).then(result => {

                if (result.isConfirmed) {

                    $.post('/control-personal/pagos-personal/store', {
                        _token: '{{ csrf_token() }}',
                        user_id: window.USER_ID,
                        monto: result.value,
                        tipo_pago: tipo,
                        descripcion: tipo,
                        fecha: new Date().toISOString().slice(0, 10)
                    }, function () {
                        abrirModalResumen();
                    });
                }
            });
        }

        function refrescarResumen() {

            let inicio = $('#fecha_inicio').val();
            let fin = $('#fecha_fin').val();

            $.get(`/control-personal/resumen-fechas/${window.USER_ID}`, {
                inicio, fin
            }, function (data) {

                $('#txt-adelantos').text('Bs ' + data.adelantos);
                $('#txt-descuentos').text('Bs ' + data.descuentos);
                $('#txt-total-final').text('Bs ' + data.total_final);
                let html = '';

                data.ajustes.forEach(a => {
                    let color = a.tipo_pago === 'adelanto' ? 'danger' : 'warning';

                    html += `
                                    <tr>
                                        <td>${a.fecha}</td>
                                        <td><span class="badge bg-${color}">${a.tipo_pago}</span></td>
                                        <td>Bs ${a.monto}</td>
                                        <td>${a.descripcion}</td>
                                    </tr>
                                `;
                });

                $('#tabla-ajustes').html(html);

            });
        }

        //botones
        $(document).on('click', '.btn-config', function () {

            let userId = $(this).data('id');
            window.USER_ID = userId;

            $('#contenido-modal').html('Cargando...');

            $.get(`/control-personal/user/${window.USER_ID}`, function (data) {
                let html = `
                            <div class="modal-header">
                            <h5 class="modal-title">Configuración</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                        <form id="form-config" onsubmit="event.preventDefault(); guardarConfiguracion();">
                            <input type="hidden" name="user_id" value="${data.id}">

                            <label>Pago diario</label>
                            <input type="number"
                                name="pago_diario"
                                step="0.01"
                                class="form-control mb-2"
                                value="${data.pago_diario ? parseFloat(data.pago_diario).toFixed(2) : '0.00'}">

                            <label>Horas base</label>
                            <input type="number"
                                name="horas_base"
                                step="0.01"
                                class="form-control mb-2"
                                value="${data.horas_base ? parseFloat(data.horas_base).toFixed(2) : '0.00'}">

                            <button class="btn btn-success w-100">Guardar</button>
                        </form>
                    </div>
                        `;

                $('#contenido-modal').html(html);
                $('#modalPersonal').modal('show');
            });

        })

        $(document).on('click', '.btn-asistencia', function () {

            let userId = $(this).data('id');
            window.USER_ID = userId;

            let html = `
                            <div class="modal-header">
                                <h5 class="modal-title">Asistencia</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div id="contenido-asistencia">
                            <div class="mb-2">
                                <label>Fecha</label>
                                <input type="date" id="fecha" class="form-control">
                            </div>
                            <div class="mb-2">
                                <label>Hora Entrada</label>
                                <input type="time" id="hora_entrada" class="form-control" step="1">
                            </div>
                            <div class="mb-2">
                                <label>Hora Salida</label>
                                <input type="time" id="hora_salida" class="form-control" step="1">
                            </div>
                            <button class="btn btn-success" onclick="guardarAsistencia()">
                                Guardar
                            </button>
                            <hr>

                            <div id="tabla-asistencia"></div>
                            </div>
                            </div>

                        `;

            $('#contenido-modal').html(html);
            let modal = new bootstrap.Modal(document.getElementById('modalPersonal'));
            modal.show();
            cargarAsistencias();
        });


        function cargarAsistencias() {

            $.get(`/asistencias/listado/${window.USER_ID}`, function (data) {

                let html = `

                                <table class="table" id="tabla_asistencias">
                                    <thead>
                                        <tr>
                                            <th>Fecha</th>
                                            <th>Entrada</th>
                                            <th>Salida</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                            `;

                data.forEach(a => {
                    let entrada = a.hora_entrada || '-';
                    let salida = a.hora_salida || '-';
                    html += `
                                            <tr>
                                                <td>${formatearFecha(a.fecha)}</td>
                                                <td>${entrada}</td>
                                                <td>${salida}</td>
                                                <td>
                                                    <button class="btn btn-warning btn-sm"
                                                        onclick='editarAsistencia(${JSON.stringify({
                        id: a.id,
                        fecha: a.fecha,
                        entrada: entrada,
                        salida: salida
                    })})'>
                                                Editar
                                            </button>

                                            <button class="btn btn-danger btn-sm"
                                                onclick="eliminarAsistencia(${a.id})">
                                                Eliminar
                                            </button>
                                        </td>
                                    </tr>
                                `;
                });

                html += `</tbody></table>`;
                $('#tabla-asistencia').html(html);
                $('#tabla_asistencias').DataTable({
                    destroy: true,
                    lengthMenu: [10, 25, 50],
                    language: {
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_",
                        info: "Mostrando _START_ a _END_ de _TOTAL_",
                        paginate: {
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    }
                });
            });
        }



        $(document).on('click', '.btn-pagos', function () {

            let userId = $(this).data('id');
            window.USER_ID = userId;

            let html = `
                                    <div class="modal-header">
                                        <h5 class="modal-title">Pagos</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">
                                    <div class="row mb-3">
                                        <div class="col-md-5">
                                            <label>Fecha inicio</label>
                                            <input type="date" id="fecha_inicio" class="form-control">
                                        </div>

                                        <div class="col-md-5">
                                            <label>Fecha fin</label>
                                            <input type="date" id="fecha_fin" class="form-control">
                                        </div>

                                        <div class="col-md-2 d-flex align-items-end">
                                            <button class="btn btn-primary w-100" onclick="cargarResumen()">
                                                Consultar
                                            </button>
                                        </div>
                                    </div>

                                    <div id="resultado-resumen"></div>

                                    </div>
                                `;

            $('#contenido-modal').html(html);
            $('#modalPersonal').modal('show');
        });


        //pago focalizar planchar
        $(document).on('click', '.btn-produccion', function () {

            let user_id = $(this).data('id');
            let tipo = $(this).data('tipo');

            $('#user_id').val(user_id);
            $('#tipo_produccion').val(tipo);

            if (tipo === 'focalizador') {
                $('#tituloProduccion').text('Pago Focalizador');
            } else {
                $('#tituloProduccion').text('Pago Planchador');
            }

            $('#modalProduccion').modal('show');
        });

        $('#btnConsultarProduccion').on('click', function () {

            let user_id = $('#user_id').val();
            let tipo = $('#tipo_produccion').val();
            let inicio = $('#inicio').val();
            let fin = $('#fin').val();

            if (!inicio || !fin) {
                Swal.fire('Error', 'Selecciona fechas', 'warning');
                return;
            }

            $.post('/produccion-pago/resumen', {
                user_id,
                tipo,
                inicio,
                fin,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function (data) {

                let bloqueado = data.pago_realizado || data.total_final <= 0;

                let html = `
                                <table class="table table-bordered text-center">
                                    <tr>
                                        <th>Prendas</th>
                                        <th>Total</th>
                                        <th>Adelantos</th>
                                        <th>Descuentos</th>
                                        <th>Total Final</th>
                                    </tr>
                                    <tr>
                                        <td>${data.total_prendas} </td>
                                        <td>Bs ${data.total}</td>
                                        <td>Bs ${data.adelantos}</td>
                                        <td>Bs ${data.descuentos}</td>

                                        <td><b>Bs ${data.total_final}</b></td>
                                    </tr>
                                </table>
                        `;

                let facturasDetalle = Array.isArray(data.facturas_detalle) ? data.facturas_detalle : [];


                html += `<div class="card mt-4 p-3">
                                        <h5>Detalle de Producción</h5>
                                    `;

                if (facturasDetalle.length) {

                    facturasDetalle.forEach(f => {
                        html += `
                                        <div class="mb-2">
                                        <b><i class="bi bi-receipt"></i> Factura:</b> ${f.factura} <br>
                                        <b><i class="bi bi-person"></i> Cliente:</b> ${f.cliente} <br>
                                        <b><i class="bi bi-hash"></i> OTs:</b> ${f.ots.length ? f.ots.join(', ') : '—'} <br>
                                        <b><i class="bi bi-basket"></i> Prendas:</b> ${f.prendas}
                                        <hr>
                                    </div>
                                    `;
                    });

                } else {
                    html += `<p>Sin detalle</p>`;
                }

                html += `</div>`;


                if (data.pago_realizado) {
                    html += `
                                    <div class="alert alert-success text-center mt-2">
                                        ✅ Este periodo ya fue pagado <br>
                                        <strong>Bs ${data.total_final}</strong>
                                    </div>
                                `;
                }

                html += `
                        <div class="row mt-3">
                            <div class="col-md-2">
                                <input type="number" id="adelanto_monto" class="form-control"
                                    placeholder="Adelanto" ${bloqueado ? 'disabled' : ''}>
                            </div>
                            <div class="col-md-2">
                                <textarea id="adelanto_desc" class="form-control" rows="1"
                                        ${bloqueado ? 'disabled' : ''}></textarea>
                            </div>

                            <div class="col-md-2">
                                <button id="btn-adelanto" class="btn btn-success w-100"
                                    ${bloqueado ? 'disabled' : ''}>
                                    Guardar
                                </button>
                            </div>

                            <div class="col-md-2">

                                    <label class="form-label">
                                        Deuda
                                    </label>

                                    <select id="deuda_id"
                                        class="form-control form-control-sm">

                                        <option value="">
                                            Seleccionar deuda
                                        </option>

                                    </select>
                                    <input type="number" id="descuento_monto" class="form-control"
                                    placeholder="Descuento" ${bloqueado ? 'disabled' : ''}>
                            </div>
                            <div class="col-md-2">

                                <label class="form-label">
                                    Saldo pendiente
                                </label>

                                <input
                                    type="text"
                                    id="saldo_deuda"
                                    class="form-control"
                                    readonly>

                            </div>

                            <div class="col-md-2">
                                <button id="btn-descuento" class="btn btn-warning w-100"
                                    ${bloqueado ? 'disabled' : ''}>
                                    Guardar
                                </button>
                            </div>
                        </div>
                    `;


                html += `
                                    <div class="text-center mt-3">
                                        ${data.pago_realizado
                        ? `<button class="btn btn-secondary" disabled>YA PAGADO</button>`
                        : `<button class="btn btn-primary" id="btn-pagar-produccion">PAGAR TOTAL</button>`
                    }
                                    </div>
                                `;


                html += `
                                    <h5 class="mt-4">Movimientos</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Fecha</th>
                                                <th>Tipo</th>
                                                <th>Descripcion</th>
                                                <th>Monto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                `;

                data.ajustes.forEach(a => {

                    let color = a.tipo_pago === 'adelanto' ? 'danger' : 'warning';

                    html += `
                                            <tr>
                                                <td>${a.fecha}</td>
                                                <td><span class="badge bg-${color}">${a.tipo_pago}</span></td>
                                                <td>${a.descripcion ?? '-'}</td>
                                                <td class="text-${color}">Bs ${Math.abs(a.monto)}</td>
                                            </tr>
                                        `;
                });

                html += `</tbody></table>`;


                $('#resultadoProduccion').html(html);
                cargarDeudasUsuario();
            });

        });




        $(document).on('click', '#btn-pagar-produccion', function () {

            let user_id = $('#user_id').val();
            let tipo = $('#tipo_produccion').val();
            let inicio = $('#inicio').val();
            let fin = $('#fin').val();


            if (!inicio || !fin) {
                Swal.fire('Error', 'Selecciona fechas', 'warning');
                return;
            }
            $.post('/produccion-pago/resumen', {
                user_id,
                tipo,
                inicio,
                fin,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function (data) {


                if (data.pago_realizado) {
                    Swal.fire('Info', 'Este periodo ya fue pagado', 'info');
                    return;
                }
                if (data.total_final <= 0) {
                    Swal.fire('Error', 'El total es 0', 'warning');
                    return;
                }

                Swal.fire({
                    title: 'Confirmar pago',
                    text: `Se pagará Bs ${data.total_final}`,
                    icon: 'question',
                    showCancelButton: true
                }).then(result => {

                    if (result.isConfirmed) {

                        $.post('/produccion-pago/pagar', {
                            user_id,
                            tipo_pago: 'salario_produccion',
                            descripcion: 'Pago producción',

                            fecha_inicio: inicio,
                            fecha_fin: fin,
                            monto: data.total_final,
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }).done(function () {

                            Swal.fire('OK', 'Pago registrado', 'success');
                            $('#modalProduccion').modal('hide');

                        }).fail(function (err) {

                            Swal.fire('Error', err.responseJSON?.error, 'error');
                        });
                    }
                });

            });

        });

        $('#modalProduccion').on('hidden.bs.modal', function () {

            $('#inicio').val('');
            $('#fin').val('');
            $('#resultadoProduccion').html('');

            $('#user_id').val('');
            $('#tipo_produccion').val('');

        });

        $(document).on('click', '#btn-adelanto', function () {

            let monto = $('#adelanto_monto').val();
            let user_id = $('#user_id').val();
            let inicio = $('#inicio').val();
            let fin = $('#fin').val();

            let descripcion = $('#adelanto_desc').val() || 'Adelanto producción';

            $.post('/produccion-pago/pagar', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id,
                monto,
                tipo_pago: 'adelanto',
                descripcion: descripcion,
                fecha_inicio: inicio,
                fecha_fin: fin
            }, function () {
                Swal.fire('OK', 'Adelanto guardado', 'success');
                $('#btnConsultarProduccion').click();
            });

        });

        $(document).on('click', '#btn-descuento', function () {

            let monto = $('#descuento_monto').val();
            let user_id = $('#user_id').val();
            let inicio = $('#inicio').val();
            let fin = $('#fin').val();
            let deuda_id = $('#deuda_id').val();

            if (!deuda_id) {

                Swal.fire(
                    'Error',
                    'Selecciona una deuda',
                    'warning'
                );

                return;
            }
            let descripcion = $('#descuento_desc').val() || 'Descuento producción';

            $.post('/produccion-pago/pagar', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id,
                deuda_id,
                monto,
                tipo_pago: 'descuento',
                descripcion: descripcion,
                fecha_inicio: inicio,
                fecha_fin: fin
            }, function () {
                Swal.fire('OK', 'Descuento guardado', 'success');
                $('#btnConsultarProduccion').click();
            });

        });


        //deudas
        $(document).on('click', '.btn-deudas', function () {

            let userId = $(this).data('id');
            window.USER_ID = userId;
            let html = `
                                            <div class="modal-header">
                                                <h5 class="modal-title">
                                                    Descuentos Acumulados
                                                </h5>
                                                <button type="button"
                                                    class="btn-close"
                                                    data-bs-dismiss="modal">
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="mb-2">
                                                    <label>Concepto</label>
                                                    <input type="text" id="concepto"class="form-control">
                                                </div>
                                                <div class="mb-2">
                                                    <label>Descripción</label>
                                                    <textarea id="descripcion" class="form-control">
                                                    </textarea>
                                                </div>

                                                <div class="mb-2">
                                                    <label>Monto Total</label>
                                                    <input type="number" id="monto_total" class="form-control">
                                                </div>

                                                <div class="mb-2">
                                                    <label>Fecha</label>

                                                    <input type="date" id="fecha" class="form-control">
                                                </div>

                                                <button class="btn btn-success" onclick="guardarDeuda()"> Guardar Deuda
                                                </button>

                                                <hr>
                                                <div id="tabla-deudas"></div>
                                            </div>
                                        `;

            $('#contenido-modal').html(html);
            let modal = new bootstrap.Modal(
                document.getElementById('modalPersonal')
            );
            modal.show();
            cargarDeudas();
        });


        function guardarDeuda() {

            $.post('/deudas/store', {
                _token: $('meta[name="csrf-token"]').attr('content'),
                user_id: window.USER_ID,
                concepto: $('#concepto').val(),
                descripcion: $('#descripcion').val(),
                monto_total: $('#monto_total').val(),
                fecha: $('#fecha').val()
            }, function () {
                Swal.fire(
                    'OK',
                    'Deuda registrada',
                    'success'
                );

                cargarDeudas();
                $('#concepto').val('');
                $('#descripcion').val('');
                $('#monto_total').val('');
                $('#fecha').val('');
            });
        }

        function cargarDeudas() {

            $.get(`/deudas/${window.USER_ID}`, function (data) {
                let html = `
                                                <table class="table table-bordered"
                                                    id="tabla_deudas">
                                                    <thead>
                                                        <tr>
                                                            <th>Concepto</th>
                                                            <th>Total</th>
                                                            <th>Pagado</th>
                                                            <th>Saldo</th>
                                                            <th>Estado</th>
                                                            <th>Acciones</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                            `;
                data.forEach(d => {
                    html += `
                                                    <tr>
                                                        <td>${d.concepto ?? '-'}</td>
                                                        <td>
                                                            ${parseFloat(d.monto_total).toFixed(2)}
                                                        </td>
                                                        <td>
                                                            ${parseFloat(d.monto_pagado).toFixed(2)}
                                                        </td>
                                                        <td>
                                                            ${parseFloat(d.saldo_pendiente).toFixed(2)}
                                                        </td>
                                                        <td>
                                                            ${d.estado == 'PAGADO'
                            ?
                            `<span class="badge bg-success">
                                                                    PAGADO
                                                                </span>`
                            :
                            `<span class="badge bg-danger">
                                                                    PENDIENTE
                                                                </span>`
                        }
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-primary btn-sm"  onclick="verMovimientos(${d.id})">  Movimientos </button>

                                                            <button class="btn btn-danger btn-sm"
                                                                onclick="imprimirDeuda(${d.id})">
                                                                Imprimir
                                                            </button>
                                                        </td>


                                                    </tr>
                                                `;
                });
                html += `
                                                    </tbody>
                                                </table>
                                            `;

                $('#tabla-deudas').html(html);
                $('#tabla_deudas').DataTable({
                    destroy: true,
                    lengthMenu: [10, 25, 50],
                    language: {
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_",
                        info:
                            "Mostrando _START_ a _END_ de _TOTAL_",
                        paginate: {
                            next: "Siguiente",
                            previous: "Anterior"
                        }
                    }
                });
            });
        }


        function imprimirDeuda(deudaId) {
            window.open(`/deudas/reporte/${deudaId}`, '_blank');
        }

        function verMovimientos(deudaId) {
            $.get(`/deudas/movimientos/${deudaId}`, function (data) {
                let html = `
                                                <table class="table table-bordered">
                                                    <thead>
                                                        <tr>
                                                            <th>Fecha</th>
                                                            <th>Tipo</th>
                                                            <th>Monto</th>
                                                            <th>Descripción</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                            `;

                data.forEach(m => {
                    html += `
                                                    <tr>
                                                        <td>${m.fecha ?? '-'}</td>
                                                        <td>
                                                            ${m.tipo_movimiento == 'INGRESO'
                            ?
                            `<span class="badge bg-danger">
                                                                    INGRESO
                                                                </span>`
                            :
                            `<span class="badge bg-success">
                                                                    SALIDA
                                                                </span>`
                        }
                                                        </td>
                                                        <td>
                                                            ${parseFloat(m.monto).toFixed(2)}
                                                        </td>
                                                        <td>
                                                            ${m.descripcion ?? '-'}
                                                        </td>
                                                    </tr>
                                                `;
                });

                html += `
                                                    </tbody>
                                                </table>
                                            `;

                Swal.fire({
                    title: 'Movimientos de deuda',
                    width: 900,
                    html: html
                });
            });
        }


        $(document).on('change', '#deuda_id', function () {

            let deudaId = $(this).val();

            if (!deudaId) {

                $('#saldo_deuda').val('');
                return;
            }

            let saldo = $(this)
                .find(':selected')
                .data('saldo');

            if (!saldo) {
                saldo = 0;
            }

            $('#saldo_deuda').val(
                'Bs ' + parseFloat(saldo).toFixed(2)
            );

        });

    </script>
@endsection
