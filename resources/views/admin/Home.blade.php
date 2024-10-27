
<x-layout :user-data="$user"  :entries="$entries">
    <div class="page-content">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Página Inicio</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active">Inicio</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Reservaciones</h4>
                        <p class="card-title-desc my-0">Esta tabla muestra la información de ultimas 100 reservaciones realizadas.</p>
                        <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="datatable" class="table table-striped dataTable display" style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 100px;">Folio</th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 300px;">Contacto</th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 320px;" >Itinerario</th>
                                            @if(count($reservaciones) > 0 && isset($reservaciones[0]->status))
                                                <th class="sorting_asc text-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 98px;">Status/Operador</th>
                                            @endif
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 98px;">Precio</th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 183px;">Fecha Reserva</th>
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 167px;">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($reservaciones as $reservacion)
                                            <tr>
                                                <td>{{ $reservacion->folio }}</td>
                                                <td>
                                                    {{ strtoupper($reservacion->nombre) }}
                                                    <br>
                                                    {{ $reservacion->telefono }}
                                                </td>
                                                <td>
                                                    1. {{ $reservacion->origen }} 
                                                    <br>
                                                    2. {{ $reservacion->destino }}
                                                </td>
                                                @if(isset($reservacion->status))
                                                    @if($reservacion->status == "Pending")
                                                        <td class="text-center"><span class="badge rounded-pill bg-primary">Sin asginación</span></td>
                                                    @endif
                                                    @if($reservacion->status == "En servicio")
                                                        <td class="text-center">
                                                            <span class="badge rounded-pill bg-success">Asignado</span>
                                                            <br>
                                                            {{ $reservacion->nombres }} {{ $reservacion->apellidos }}
                                                        </td>
                                                    @endif
                                                @endif
                                                <td>{{ "$". number_format($reservacion->precio,2) }}</td>
                                                <td>{{ date('d-m-Y H:m',strtotime($reservacion->date_creacion)) }}</td>
                                                <td>
                                                    @if(isset($reservacion->status))
                                                        @if($reservacion->status == "Pending")
                                                            <button class="btn btn-sm btn-info text-white btnTicket" data-attr="{{ $reservacion->id_viaje }}" disabled="true">
                                                                <i class="fa fa-print" aria-hidden="true" title="Generar Ticket"></i>
                                                            </button>
                                                        @else
                                                            <button class="btn btn-sm btn-info text-white btnTicket" data-attr="{{ $reservacion->id_viaje }}">
                                                                <i class="fa fa-print" aria-hidden="true" title="Generar Ticket"></i>
                                                            </button>
                                                        @endif
                                                        
                                                        <button class="btn btn-sm btn-secondary text-white btnAsignar" data-attr="{{ $reservacion->id_viaje }}"  title="Asignar Operador">
                                                            <i class="fa fa-check-square" aria-hidden="true"></i>
                                                        </button>
                                                    @else
                                                        <button class="btn btn-sm btn-info text-white btnTicket" data-attr="{{ $reservacion->id_viaje }}">
                                                            <i class="fa fa-print" aria-hidden="true" title="Generar Ticket"></i>
                                                        </button>
                                                    @endif
                                                </td>
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
    <button type="button" class="d-none btnModal" data-bs-toggle="modal" data-bs-target="#modalTicket"></button>
    <div class="modal fade" id="modalTicket" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Previsulización de ticket</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <object id="pdfShow" data="" width="100%" height="600px"/></object>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Operadores -->
    @include('components.modales.modal_asignar_vehiculo', $vehiculos)

    <x-slot name="scripts">
        <script>
            window.routes = {
                'generarTicket' : '{{ route('admin.api.generar') }}',
                'asignarVehiculoOperador' : '{{ route('admin.api.asignarVehiculoOperador') }}'
            }
        </script>
    </x-slot> 
</x-layout>  