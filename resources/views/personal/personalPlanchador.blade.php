<div class="modal-header">
    <h5 class="modal-title">REPORTE PAGO PLANCHADOR</h5>
    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
</div>

<div class="modal-body">

    <form action="{{ route('reporte.personal.planchador.pdf') }}" method="POST" target="_blank">
        @csrf

        <div class="row">


            <div class="col-md-4">
                <label class="fw-semibold fs-6 mb-2">Sucursal</label>
                <select name="sucursal_id" id="sucursal_id" class="form-select form-select-sm">
                    <option value="">-- Todas --</option>
                    @foreach($sucursales as $sucursal)
                        <option value="{{ $sucursal->id }}">
                            {{ $sucursal->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-4">
                <label class="fw-semibold fs-6 mb-2">Planchador</label>
                <select name="user_id" id="user_id" class="form-select form-select-sm">
                    <option value="">-- Seleccionar planchador --</option>
                    @foreach($usuarios as $u)
                        <option value="{{ $u->id }}">
                            {{ $u->nombres }} {{ $u->ap_paterno }}
                        </option>
                    @endforeach
                </select>
            </div>


            <div class="col-md-2">
                <label class="fw-semibold fs-6 mb-2">Inicio</label>
                <input type="date" name="fecha_inicio" class="form-control form-control-sm" required>
            </div>


            <div class="col-md-2">
                <label class="fw-semibold fs-6 mb-2">Fin</label>
                <input type="date" name="fecha_fin" class="form-control form-control-sm" required>
            </div>

            <div class="col-md-12 mt-3">
                <button class="btn btn-success btn-sm w-100">
                    Generar PDF
                </button>
            </div>

        </div>
    </form>

</div>