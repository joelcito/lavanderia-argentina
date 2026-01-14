@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-light-info">
                <h3 class="card-title fw-bold">REPORTE PROCESO POR OT</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('reporte.proceso.pdf') }}" method="POST" target="_blank">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <label for="order_trabajo_id" class="fw-semibold">Seleccione OT</label>
                            <select name="order_trabajo_id" class="form-select">
                                @foreach($ots as $ot)
                                    <option value="{{ $ot->ot_id }}">{{ $ot->nro_ot }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-success mt-8">Generar PDF</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection