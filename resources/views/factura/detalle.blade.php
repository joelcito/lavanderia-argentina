@extends('layouts.app')
@section('css')
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/jquery.orgchart.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css" />
    <style>
    </style>
@endsection
@section('metadatos')
    <meta name="csrf-token" content="{{ csrf_token() }}" />
@endsection
@section('content')

<!--begin::Content wrapper-->
<div class="d-flex flex-column flex-column-fluid">
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-xxlg">
            <!--begin::Card-->
            <div class="card">
                <div class="card-body py-4">
                     <!--begin::Details-->
                    <div class="d-flex mb-9">
                        <!--begin: Pic-->
                        <div class="flex-shrink-0 mr-7 mt-lg-0 mt-3">
                            @if (0 > 0)
                                <div style="width: 200px; height: 200px;">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                                                <div class="carousel-inner">
                                                    {{-- @foreach ( $imagenes as $key => $imagen)
                                                        <div class="carousel-item  {{ $key==0? 'active': '' }}">
                                                            <img src="{{ asset($imagen->ruta) }}" width="100%" height="100%">
                                                        </div>
                                                    @endforeach --}}
                                                </div>
                                                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Previous</span>
                                                </button>
                                                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                                    <span class="visually-hidden">Next</span>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <img src="{{ asset("/storage/imagenes/LLAMA/3180859.png") }}" height="110" alt="image">
                            @endif
                        </div>
                        <!--end::Pic-->
                        <!--begin::Info-->
                        <div class="flex-grow-1" style="margin-left: 10px;">
                            <!--begin::Title-->
                            <div class="d-flex justify-content-between flex-wrap mt-1">
                                <div class="d-flex mr-3">
                                    <h2><span class="text-primary">NOMBRE: </span> {{ $cliente->nombres }}</h2>
                                </div>
                            </div>

                            <hr />
                            <!--end::Title-->
                            <!--begin::Content-->
                            <div class="row">
                                <div class="col-md-8">
                                    {{-- <h6><span class="text-primary">FENOTIPO: </span> {{ $ejemplar->fenotipo->nombre }}</h6> --}}
                                </div>
                            </div>

                            <hr />

                            <div class="row">
                                <div class="col-md-3">
                                    <h6><span class="text-primary">PADRE: </span>
                                        {{-- {{ ($ejemplar->padre)? $ejemplar->padre->nombre : '' }} --}}
                                    </h6>
                                </div>

                                <div class="col-md-3">
                                    <h6><span class="text-primary">MADRE: </span>
                                        {{-- {{ ($ejemplar->madre)? $ejemplar->madre->nombre : '' }} --}}
                                    </h6>
                                </div>

                                <div class="col-md-6">
                                    {{-- <h6><span class="text-primary">PROPIETARIO: </span>{{ ($ejemplar->criadero)? (($ejemplar->criadero->propietario)? ($ejemplar->criadero->propietario->name) : '') : ''  }} </h6> --}}
                                </div>
                            </div>

                            <hr />

                            <div class="row">
                                <div class="col-md-3">
                                    <h6><span class="text-primary">SEXO: </span> </h6>
                                </div>

                                <div class="col-md-3">
                                    <h6><span class="text-primary">AFIJO: </span> </h6>
                                </div>

                                <div class="col-md-3">
                                    <h6><span class="text-primary">COLOR: </span> </h6>
                                </div>
                            </div>

                            <!--end::Content-->
                        </div>
                        <!--end::Info-->
                    </div>
                    <!--end::Details-->
                    <!--end::Details-->
                    <div class="separator separator-solid"></div>
                    <!--begin::Items-->
                    <div class="d-flex align-items-center flex-wrap mt-8">
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span class="mr-4">
                                <i class="fa-solid fa-horse-head" style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">NUM. REG.</span>
                                {{-- <h5>{{ $ejemplar->numero_registro }}</h5> --}}
                            </div>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span>
                                <i class="fas fa-barcode"  style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">MICRO CHIP</span>
                                {{-- <h5>{{ $ejemplar->microchip }}</h5> --}}
                            </div>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span class="mr-4">
                                <i class="fas fa-democrat"  style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">NUM. ARETE</span>
                                {{-- <h5>{{ $ejemplar->arete }}</h5> --}}
                            </div>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span class="mr-4">
                                <i class="fas fa-list-alt" style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">TIPO</span>
                                {{-- <h5>{{ $ejemplar->tipo }}</h5> --}}
                            </div>
                        </div>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <div class="d-flex align-items-center flex-lg-fill mr-5 mb-2">
                            <span class="mr-4">
                                <i class="fas fa-calendar-day" style="font-size: 30px; margin-right: 5px;"></i>
                            </span>
                            <div class="d-flex flex-column text-dark-75">
                                <span class="font-weight-bolder font-size-sm text-primary">F. NACIMIENTO</span>
                                {{-- <h5>{{ $ejemplar->fecha_nacimiento }}</h5> --}}
                            </div>
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--begin::Items-->
                </div>
            </div>
            <!--end::Card-->
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
</div>
<!--end::Content wrapper-->
<hr>

<div class="row">
    <div class="col-md-12">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxlg">
                    <!--begin::Card-->
                    <div class="card">
                        <div class="card-header flex-wrap bg-light py-4">
                            <div id="kt_app_toolbar_container" class="app-container container-xxlg d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Registros Biometricos</h1>
                                    <!--end::Title-->
                                </div>
                                <!--end::Page title-->
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div id="tabla_biometrias"></div>
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxlg">
                    <!--begin::Card-->
                    <div class="card">
                        <div class="card-header flex-wrap bg-light py-4">
                            <div id="kt_app_toolbar_container" class="app-container container-xxlg d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Registros Morfologicos</h1>
                                    <!--end::Title-->
                                </div>
                                <!--end::Page title-->
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div id="tabla_morfilogicos"></div>
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxlg">
                    <!--begin::Card-->
                    <div class="card">
                        <div class="card-header flex-wrap bg-light py-4">
                            <div id="kt_app_toolbar_container" class="app-container container-xxlg d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Registros Fibras</h1>
                                    <!--end::Title-->
                                </div>
                                <!--end::Page title-->
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div id="tabla_analisis_fibras"></div>
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <!--begin::Content wrapper-->
        <div class="d-flex flex-column flex-column-fluid">
            <div id="kt_app_content" class="app-content flex-column-fluid">
                <!--begin::Content container-->
                <div id="kt_app_content_container" class="app-container container-xxlg">
                    <!--begin::Card-->
                    <div class="card">
                        <div class="card-header flex-wrap bg-light py-4">
                            <div id="kt_app_toolbar_container" class="app-container container-xxlg d-flex flex-stack">
                                <!--begin::Page title-->
                                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                                    <!--begin::Title-->
                                    <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">GENERACIONES DEL EJEMPLAR</h1>
                                    <!--end::Title-->
                                </div>
                                <!--end::Page title-->
                            </div>
                        </div>
                        <div class="card-body py-4">
                            <div id="chart-container"></div>
                        </div>
                    </div>
                    <!--end::Card-->
                </div>
                <!--end::Content container-->
            </div>
            <!--end::Content-->
        </div>
        <!--end::Content wrapper-->
    </div>
</div>

@stop()

@section('js')
    <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.orgchart.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script>
        $.ajaxSetup({
            // definimos cabecera donde estarra el token y poder hacer nuestras operaciones de put,post...
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $(document).ready(function() {
            // cargarArbolGenealogicoVista();
            // ajaxListadoBiometriaDetalle();
            // ajaxListadoMorfologicoDetalle();
            // ajaxListadoFibrasDetalle();
        });

   </script>
@endsection
