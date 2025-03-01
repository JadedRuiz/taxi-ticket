<x-layout :user-data="$user"  :entries="$entries">
    <div class="page-content">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Página de Gastos</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active" id="prueba">Reportes</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="title-cardbody d-flex justify-content-between">
                            <h4 class="card-title">Mis Gastos</h4>
                        </div>
                        <p class="card-title-desc my-0 d-flex">
                            Administra tus gastos generados. 
                        </p>
                        <div class="row mt-2">
                            <div class="col-xxl-5 col-xl-6 col-lg-6 col-sm-12">
                                <div class="tabs">
                                    <div class="tab-item active" id="tab-gastos">
                                        Agregar/Editar Gastos
                                    </div>
                                    <div class="tab-item" id="tab-reporte">
                                        Generar Reporte
                                    </div>
                                </div>
                                <div class="Gastos">
                                    <form class="form row g-2" id="form_gastos">                                    
                                        <div class="col-lg-12 col-sm-12 status">
                                            <span class="badge bg-primary status-item active">Activo</span>
                                            <span class="badge bg-warning mx-2 status-item">Suspendido</span>
                                            <span class="badge bg-danger status-item">Cancelado</span>
                                        </div>
                                        <div class="form-group col-lg-3 col-sm-12">
                                            <label for="folio" class="lblInp">Folio:</label>
                                            <input type="text" name="folio" id="folio" class="form-control" value="{{$data_view["folio"]}}" readonly disabled>
                                        </div>
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="concepto" class="lblInp">Clasificación del Gasto:</label>
                                            <input type="text" name="concepto_gasto" id="concepto" class="form-control concepto_auto" placeholder="Busca el concepto del gasto" style="text-transform: uppercase;">
                                        </div>
                                        <div class="form-group col-lg-3 col-sm-12">
                                            <label for="fecha" class="lblInp">Fecha:</label>
                                            <input type="date" name="fecha" id="fecha" class="form-control">
                                        </div>
                                        <div class="form-group col-lg-4 col-sm-12">
                                            <label for="importe" class="lblInp">Importe prestamos:</label>
                                            <input type="text" name="importe" id="importe" class="form-control" placeholder="$ 0.00">
                                        </div>
                                        <div class="form-group col-lg-4 col-sm-12">
                                            <label for="tipo_comprobante" class="lblInp">Tipo comprobante:</label>
                                            <select class="form-select" id="tipo_comprobante" name="tipo_comprobante">
                                                @foreach($data_view["tipo_comprobante"] as $index => $tipo)
                                                    @if($index == 0) 
                                                        <option value="{{$tipo}}" selected>{{$tipo}}</option> 
                                                    @else
                                                        <option value="{{$tipo}}">{{$tipo}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group col-lg-4 col-sm-12">
                                            <label for="folio_comprobante" class="lblInp">Folio comprobante:</label>
                                            <input type="text" name="folio_comprobante" id="folio_comprobante" class="form-control">
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="descripcion" class="lblInp">Descripción del gasto:</label>
                                            <textarea class="form-control" placeholder="Ingresa una breve descripción" id="descripcion" maxlength="350" style="text-transform: uppercase;" name="descripcion"></textarea>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="admin_gastos_id" class="lblInp">Entrega efectivo:</label>
                                            <select class="form-select" id="admin_gastos_id" name="admin_gastos_id">
                                                @if(count($data_view["encargados"]) > 0 ) 
                                                    @foreach($data_view["encargados"] as $index => $encargado)
                                                        @if($index == 0) 
                                                            <option value="{{$encargado->id_admin_gastos}}" selected>{{$encargado->nombre}}</option> 
                                                        @else
                                                            <option value="{{$encargado->id_admin_gastos}}">{{$encargado->nombre}}</option>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <option value="-1" selected>NO SE ENCONTRARON RESPONSABLES</option> 
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="recibe_efectivo" class="lblInp">Recibe efectivo:</label>
                                            <input type="text" name="recibe_efectivo" id="recibe_efectivo" class="form-control" style="text-transform: uppercase;">
                                        </div>
                                    </form>
                                    <div class="row d-flex justify-content-end mt-4 w-100 px-0 mx-0">
                                        <a class="btn btn-sm btn-dark col-2 text-white d-none" id="reporte_gasto"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> &nbsp; Reporte</a>
                                        <a class="btn btn-sm btn-success col-3 mx-2 text-white d-none" id="nuevo_gasto"><i class="fa fa-plus" aria-hidden="true"></i> &nbsp; Nuevo gasto</a>
                                        <a class="btn btn-sm btn-success col-2 d-flex justify-content-center align-items-center" id="btnSave">
                                            <div class="spinner-border text-light mx-2 d-none spiner-loading" role="status"></div>
                                            <i class="fa fa-floppy-o mx-2" aria-hidden="true"></i>
                                            <div class="loading-text">Guardar</div>
                                        </a>
                                        <a class="btn btn-sm btn-secondary col-2 d-flex justify-content-center align-items-center d-none text-white" id="btnEdit">
                                            <div class="spinner-border text-light mx-2 d-none spiner-loading" role="status"></div>
                                            <i class="fa fa-floppy-o mx-2" aria-hidden="true"></i>
                                            <div class="loading-text">Editar</div>
                                        </a>
                                    </div>
                                </div>
                                <div class="Reportes d-none">
                                    <form class="form row g-2" id="form-reportes">
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="concepto_search" class="lblInp">Clasificación del Gasto:</label>
                                            <input type="text" name="concepto_gasto" id="concepto_search" class="form-control concepto_auto" placeholder="Busca el concepto del gasto" style="text-transform: uppercase;">
                                        </div>
                                        <div class="form-group col-lg-6 col-sm-12">
                                            <label for="fechas" class="lblInp">Rango de fechas:</label>
                                            <div id="fechas">
                                                <input type="date" class="form-control" name="fecha_inicial" style="width: 150px">
                                                <input type="date" class="form-control" name="fecha_final" style="width: 150px">
                                            </div>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="admin_gastos_id_search" class="lblInp">Entrega efectivo:</label>
                                            <select class="form-select" id="admin_gastos_id_search" name="admin_gastos_id">
                                                @if(count($data_view["encargados"]) > 0 )
                                                    <option value="" selected>SELECCIONA A UN ENCARGADO</option> 
                                                    @foreach($data_view["encargados"] as $index => $encargado)
                                                        <option value="{{$encargado->id_admin_gastos}}">{{$encargado->nombre}}</option>
                                                    @endforeach
                                                @else
                                                    <option value="-1" selected>NO SE ENCONTRARON RESPONSABLES</option> 
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group col-sm-12">
                                            <label for="recibe_efectivo_search" class="lblInp">Recibe efectivo:</label>
                                            <input type="text" name="recibe_efectivo" id="recibe_efectivo_search" class="form-control" style="text-transform: uppercase;" placeholder="INGRESA EL NOMBRE COMPLETO O PARCIAL">
                                        </div>
                                    </form>
                                    <div class="row d-flex justify-content-end align-items-center mt-4 w-100 px-0 mx-0">
                                        <div class="col d-flex align-items-center px-0">
                                            <input class="form-check-input" type="checkbox" value="" id="bInfoDeta" style="width: 18px; height: 18px;margin-top: 0px !important;">
                                            <label class="lblInp" for="bInfoDeta" style="font-size: 15px;">
                                              &nbsp;Información detallada
                                            </label>
                                        </div>
                                        <a class="btn btn-sm btn-secondary col-2 text-white" id="cleanSearch"><i class="fa fa-eraser" aria-hidden="true"></i> &nbsp; Limpiar</a>
                                        <a class="btn btn-sm btn-dark col-2 d-flex justify-content-center align-items-center text-white mx-2" id="btnReport">
                                            <div class="spinner-border text-light mx-2 d-none spiner-loading btnReport" role="status"></div>
                                            <i class="fa fa-file-pdf-o mx-2" aria-hidden="true"></i>
                                            <div class="loading-text">Reporte</div>
                                        </a>
                                        <a class="btn btn-sm btn-success col-2 d-flex justify-content-center align-items-center" id="btnSearch">
                                            <div class="spinner-border text-light d-none spiner-loading btnSearch" role="status"></div>
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                            <div class="loading-text">&nbsp; Buscar</div>
                                        </a>
                                    </div>
                                </div>
                                <div class="row mt-1 mb-0">
                                    <div class="alert col-12 my-0 py-1" role="alert" id="alert-form" style="display:none;"></div>
                                </div>
                            </div>
                            <div class="col-xxl-7 col-xl-6 col-lg-6 col-sm-12">
                                <table id="datatable" class="table table-striped dataTable display" style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Folio</th>
                                            <th class="text-start">Gasto</th>
                                            <th class="text-center">Importe</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data_view["gastos"] as $gasto)
                                            <tr class="tr-active" data-attr="{{$gasto->id_gasto}}">
                                                <td class="text-start folio-row">{{$gasto->folio}}</td>
                                                <td class="text-start gasto-row">{{$gasto->concepto_gasto}}</td>
                                                <td class="text-center importe-row">{{$gasto->importe}}</td>
                                                <td class="text-center status-row">{{$gasto->status}}</td>
                                                <td class="text-center fecha-row">{{$gasto->fecha}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="scripts">
        <script>
            window.routes = {
                'obtenerConceptos' : '{{ route('admin.api.obtenerConceptos') }}',
                'obtenerUltimoFolio' : '{{ route('admin.api.obtenerUltimoFolio') }}',
                'agregarGasto' : '{{ route('admin.api.agregarGasto') }}',
                'actualizarGasto' : '{{ route('admin.api.actualizarGasto') }}',
                'obtenerGastoId' : '{{ route('admin.api.obtenerGastoId') }}',
                'generarFichaGasto' : '{{ route('admin.api.generarFichaGasto') }}',
                'buscarFiltros' : '{{ route('admin.api.buscarFiltros') }}',
                'generarReporte' : '{{ route('admin.api.generarReporte') }}'
                
            }
            // window.user = @json($user);
        </script>
    </x-slot> 
</x-layout>
