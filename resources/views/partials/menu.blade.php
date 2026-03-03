<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
        <!--begin::Scroll wrapper-->
        <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 mx-3" data-kt-scroll="true"
            data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <!--begin::Menu-->
            <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">
                <!--begin:Menu item-->
                <div class="menu-item pt-5">
                    <!--begin:Menu content-->
                    <div class="menu-content">
                        {{-- <span class="menu-heading fw-bold text-uppercase fs-7">MENUS</span> --}}
                        <span class="fs-7 text-white fw-boldn">MENUS</span>
                    </div>
                    <!--end:Menu content-->
                </div>
                <!--end:Menu item-->
                @if (
                        Auth::user()->isAdmin() ||
                        Auth::user()->isLavador() ||
                        Auth::user()->isEncargadoAlmacen() ||
                        Auth::user()->isPlanchador() ||
                        // Auth::user()->isFocalizador() ||
                        Auth::user()->isAyudanteLavado() ||
                        Auth::user()->isAuxuliarOficina()
                    )
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('usuario/*', 'rol/*', 'proveedor/*', 'unidadMedida/*', 'puntoVenta/*', 'productoServicio/*', 'producto/*', 'cliente/*', 'urlApiServicio/*', 'pago/*', 'cotizacion/*') ? 'show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-university"></i>
                            </span>
                            <span class="menu-title text-white">ADMINISTRACION</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'usuario.listado' ? 'active' : '' }}"
                                    href="{{ route('user.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Usuarios</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'rol.listado' ? 'active' : '' }}"
                                    href="{{ route('rol.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Roles</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'cliente.listado' ? 'active' : '' }}"
                                    href="{{ route('cliente.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Clientes</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'proveedor.listado' ? 'active' : '' }}"
                                    href="{{ route('proveedor.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Proveedores</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'prenda.listado' ? 'active' : '' }}"
                                    href="{{ route('prenda.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Prendas</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'tipo_tela.listado' ? 'active' : '' }}"
                                    href="{{ route('tipo_tela.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Tipos de Telas</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'color_tela.listado' ? 'active' : '' }}"
                                    href="{{ route('color_tela.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Colores de Telas</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'nombre_tela.listado' ? 'active' : '' }}"
                                    href="{{ route('nombre_tela.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Nombres de Telas</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'tipo_proceso.listado' ? 'active' : '' }}"
                                    href="{{ route('tipo_proceso.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Tipos de Proceso</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'prelavado.listado' ? 'active' : '' }}"
                                    href="{{ route('prelavado.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Prelavados</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'focalizado.listado' ? 'active' : '' }}"
                                    href="{{ route('focalizado.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Focalizados</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'caracteristica.listado' ? 'active' : '' }}"
                                    href="{{ route('caracteristica.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Caracteristicas</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'sucursal.listado' ? 'active' : '' }}"
                                    href="{{ route('sucursal.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Sucursales</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'maquinaria.listado' ? 'active' : '' }}"
                                    href="{{ route('maquinaria.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Maquinarias</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'producto.listado' ? 'active' : '' }}"
                                    href="{{ route('producto.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Productos</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ url('pago/listadoDeuda') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Cuentas por Cobrar</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ url('solicitudes/listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Aprobaciòn de solicitudes</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ url('nevado/listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Nevados</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'rol' ? 'active' : '' }}"
                                    href="{{ route('order-trabajo.rol') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">
                                        Planchador/Focalizador
                                    </span>
                                </a>
                            </div>



                            <!-- @php

                                    $userRol = strtolower(auth()->user()->rol); // minúscula para comparar
                                @endphp -->

                            <!-- @if(in_array($userRol, ['Planchador', 'Focalizador'])) -->
                            <div class="menu-item">
                                <!-- <a class="menu-link {{ Route::currentRouteName() == 'order-trabajo.rol' ? 'active' : '' }}"
                                        href="{{ route('order-trabajo.rol') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span> -->
                                <span class="menu-title text-white">
                                    Planchador/Focalizador
                                </span>
                                <!-- </a> -->
                            </div>
                            <!-- @endif -->


                        </div>
                    </div>

                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('sincronizacion/*', 'eventoSignificativo/*') ? 'show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-university"></i>
                            </span>
                            <span class="menu-title text-white">VENTAS</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link " href="{{ route('factura.formulario') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Recepcion</span>
                                </a>
                            </div>
                        </div>
                        <!--end:Menu link-->
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link " href="{{ route('factura.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Listado Venta</span>
                                </a>
                            </div>
                        </div>

                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'pago.listado' ? 'active' : '' }}"
                                    href="{{ route('pago.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Ventas del Dia</span>
                                </a>
                            </div>
                        </div>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'procesos.listado' ? 'active' : '' }}"
                                    href="{{ route('procesos.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Procesos</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('sincronizacion/*', 'eventoSignificativo/*') ? 'show' : '' }}">
                        <!--begin:Menu link-->
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-university"></i>
                            </span>
                            <span class="menu-title text-white">REPORTES</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <!--end:Menu link-->
                        <div class="menu-sub menu-sub-accordion">
                            <!-- <div class="menu-item">
                                    <a class="menu-link" href="{{ route('reporte.formulario') }}">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title text-white">Reporte Productos</span>
                                    </a>
                                </div> -->

                            <!-- Reporte Procesos -->
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('reporte.proceso.formulario') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Reporte Procesos</span>
                                </a>
                            </div>

                            <!-- NUEVO: Stock Histórico -->
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('reporte.stock.formulario') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Stock Histórico</span>
                                </a>
                            </div>
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('reporte.stockCompra.formulario') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Stock por compra</span>
                                </a>
                            </div>
                        </div>
                        <!--end:Menu link-->
                        {{-- <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link " href="{{ route('factura.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Listado Venta</span>
                                </a>
                            </div>
                        </div>

                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link {{ Route::currentRouteName() == 'pago.listado' ? 'active' : '' }}"
                                    href="{{ route('pago.listado') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Ventas del Dia</span>
                                </a>
                            </div>
                        </div> --}}
                    </div>
                @endif

                @if (Auth::user()->isFocalizador())
                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ Request::is('procesos/*') ? 'show' : '' }}">
                    <span class="menu-link">
                        <span class="menu-icon">
                            <i class="fa fa-university"></i>
                        </span>
                        <span class="menu-title text-white">FOCALIZADO</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link" href="{{ route('procesos.focalizadoListado') }}">
                                <span class="menu-bullet">
                                    <span class="bullet bullet-dot"></span>
                                </span>
                                <span class="menu-title text-white">Lista Para Focalizar</span>
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if (Auth::user()->isCliente())
                    <div data-kt-menu-trigger="click"
                        class="menu-item menu-accordion {{ Request::is('sincronizacion/*', 'eventoSignificativo/*') ? 'show' : '' }}">
                        <span class="menu-link">
                            <span class="menu-icon">
                                <i class="fa fa-university"></i>
                            </span>
                            <span class="menu-title text-white">SEGUIMIENTO</span>
                            <span class="menu-arrow"></span>
                        </span>
                        <div class="menu-sub menu-sub-accordion">
                            <div class="menu-item">
                                <a class="menu-link" href="{{ route('factura.listadoFacturaCliente') }}">
                                    <span class="menu-bullet">
                                        <span class="bullet bullet-dot"></span>
                                    </span>
                                    <span class="menu-title text-white">Lista Notas Recepcion</span>
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!--end:Menu item-->
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Scroll wrapper-->
    </div>
    <!--end::Menu wrapper-->
</div>
