@extends('layouts.app')

@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('metadatos')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection

@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxlg">
                <div class="card shadow-sm">
                    <div class="card-header bg-light-info py-4">
                        <h3 class="card-title fw-bold">REPORTE STOCK HISTÓRICO POR COMPRA</h3>
                    </div>

                    <div class="card-body py-4">
                        <form action="{{ route('reporte.stockCompra.pdf') }}" method="POST" target="_blank">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <label class="required fw-semibold fs-6 mb-2">Sucursal</label>
                                    <select name="sucursal_id" class="form-select form-select-sm" required>
                                        <option value="">-- Seleccione Sucursal --</option>
                                        @foreach($sucursales as $sucursal)
                                            <option value="{{ $sucursal->id }}">{{ $sucursal->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="required fw-semibold fs-6 mb-2">Código de Compra</label>
                                    <select name="codigo_compra" class="form-select form-select-sm" required>
                                        <option value="">-- Seleccione Código --</option>
                                        @foreach($codigosCompra as $codigo)
                                            <option value="{{ $codigo->codigo_compra }}">
                                                {{ $codigo->codigo_compra }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-3">
                                    <label class="required fw-semibold fs-6 mb-2">Fecha Inicio</label>
                                    <input type="date" name="fecha_inicio" class="form-control form-control-sm" required>
                                </div>

                                <div class="col-md-3">
                                    <label class="required fw-semibold fs-6 mb-2">Fecha Fin</label>
                                    <input type="date" name="fecha_fin" class="form-control form-control-sm" required>
                                </div>

                                <div class="col-md-2 d-flex align-items-end">
                                    <button class="btn btn-success btn-sm w-100">
                                        <i class="fa fa-file-pdf"></i> Generar PDF
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection