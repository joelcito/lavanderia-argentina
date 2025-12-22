<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>OT</th>
            <th>Cantidad de solicitudes</th>
            <th>Acción</th>
        </tr>
    </thead>
    <tbody>
        @php
            $ots = $solicitudes->groupBy('orden_trabajo_id');
        @endphp
        @foreach($ots as $otId => $sols)
            <tr>
                <td>{{ $otId }}</td>
                <td>{{ count($sols) }}</td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="abrirModalOT({{ $otId }})">
                        Aprobar OT
                    </button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>