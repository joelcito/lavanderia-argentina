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
                        <h3 class="card-title fw-bold">REPORTE ESTRUCUTRA DE COSTOS</h3>
                    </div>

                    <div class="card-body py-4">

                        <form action="{{ route('reporte.estructuraCostos.pdf') }}" method="POST" target="_blank">
                            @csrf

                            <div class="row">




                                <!-- OTs -->
                                <div class="col-md-4 mb-3">
                                    <label class="fw-semibold fs-6 mb-2">Solicitud</label>

                                    <select name="solicitud_id" id="solicitud_id" class="form-select form-select-sm"
                                        required>
                                        <option value="">Seleccione solicitud</option>

                                        @foreach($solicitudes as $sol)
                                            <option value="{{ $sol->id }}">
                                                Solicitud {{ $sol->id }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- BOTÓN -->
                                <div class="col-md-12 mt-3 d-flex justify-content-end">
                                    <button class="btn btn-success btn-sm px-5">
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