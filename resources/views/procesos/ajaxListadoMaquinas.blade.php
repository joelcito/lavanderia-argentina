<div class="d-flex flex-wrap mb-4">
    @foreach ($maquinarias as $m)
        <div class="maquina-container" style="border:2px solid {{ $m->estado_maquina == 'DISPONIBLE' ? '#28a745' : '#dc3545' }};" >
            <div onclick="modalNuevaLavanderiaConMaquinaria({{ $m->id }})">
                <div class="fw-bold">{{ ucfirst($m->tipo) }}</div>
                <div class="text-muted small">
                    Equipo N° {{ $m->numero}}
                </div>
                <!-- Estado -->
                <span class="badge {{ $m->estado_maquina == 'DISPONIBLE' ? 'bg-success' : 'bg-danger' }}">
                    {{ $m->estado_maquina }}
                </span>
                <br>
                @if ($m->tipo == 'lavadora')
                    <img src="{{ asset('assets/img/lavadora.jpg') }}" alt="Lavadora">
                @else
                    <img src="{{ asset('assets/img/secadora.png') }}" alt="Secadora">
                @endif
            </div>
            @if ($m->estado_maquina == "EN PROCESO")
                <center>
                    <div class="row">
                        <div class="col-md-12">
                            <button type="button" class="btn btn-icon btn-sm btn-info" onclick="verProcesoEnMarchaMaquina({{$m->id}})"><i class="fa fa-eye"></i></button>
                        </div>
                        {{-- <div class="col-md-6">
                            <button class="btn btn-icon btn-sm btn-info"><i class="fa fa-eye"></i></button>
                        </div> --}}
                    </div>
                </center>
            @endif
        </div>
    @endforeach
</div>
