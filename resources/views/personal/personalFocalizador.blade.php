<div class="modal-header">
    <h5 class="modal-title">REPORTE PAGO FOCALIZADOR</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

    <form action="{{ route('reporte.personal.focalizador.pdf') }}" method="POST" target="_blank">
        @csrf

        <div class="row">

            <div class="col-md-3">
                <label class="required fw-semibold fs-6 mb-2">Sucursal</label>
                <select name="sucursal_id" class="form-select form-select-sm" required>
                    <option value="">-- Seleccione Sucursal --</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                    @endforeach
                </select>

            </div>
            <div class="col-md-3">
                <label class="required fw-semibold fs-6 mb-2">Focalizador</label>
                <select name="user_id" id="user_id" class="form-select form-select-sm">
                    <option value="">-- Seleccionar focalizador --</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}">
                            {{ $u->nombres }} {{ $u->ap_paterno }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="required fw-semibold fs-6 mb-2">Fecha Inicio</label>
                <input type="date" name="fecha_inicio" class="form-control form-control-sm" required>
            </div>

            <div class="col-md-2">
                <label class="required fw-semibold fs-6 mb-2">Fecha Fin</label>
                <input type="date" name="fecha_fin" class="form-control form-control-sm" required>
            </div>

            <div class="col-md-2 d-flex align-items-end">
                <button class="btn btn-success btn-sm w-100">
                    <i class="fa fa-file-pdf"></i> PDF
                </button>
            </div>

        </div>
    </form>

</div>