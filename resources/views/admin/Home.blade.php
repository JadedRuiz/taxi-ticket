
<x-layout :user-data="$user"  :entries="$entries">
    <div class="page-content">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Página Inicio</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active" id="prueba">Inicio</li>
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
                        <div class="row mt-3">
                            @if(isset($turnos)) 
                                <div class="col-3">
                                    <p class="py-1 my-1 list-title">Vehiculos en turno</p>
                                    <ul class="list-group list-turnos">
                                        <li class="list-group-item row px-0 mx-0 d-flex list-item-header">
                                            <div class="col-3 px-0 border-orden">Orden</div>
                                            <div class="col-9 d-flex justify-content-between">
                                                Vehiculo & Operador
                                                @if(in_array($user->permisos->perfil, ["Operador","Administrador"]))
                                                    <button class="btn btn-sm btn-success btnAgregarTurno" style="padding-bottom: 0px;">
                                                        <span class="mdi--truck-plus"></span>
                                                    </button>
                                                @endif                                                
                                            </div>
                                        </li>
                                        @if($turnos["ok"] && count($turnos["data"]) > 0)
                                            <div class="lstTurnos">
                                                @foreach($turnos["data"] as $index => $turno)
                                                    <li class="list-group-item row px-0 mx-0 d-flex">
                                                        <div class="col-3 px-0 border-orden">{{ $index+1 }}</div>
                                                        <div class="col-9 d-flex flex-column">
                                                            <p class="py-0 my-0 lstTitulo">{{ substr($turno->nombres." ".$turno->apellidos,0,25)."..."  }}</p>
                                                            <small class="lstTurnoSmall text-danger">{{ $turno->vehiculo."- $turno->marca ($turno->modelo)" }}</small>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </div>                                      
                                        @else
                                            <div class="lstTurnos">
                                                <li class="list-group-item text-center">Aún no hay vehiculos en turno</li>
                                            </div>
                                        @endif
                                        
                                    </ul>
                                </div>
                            @endif
                            <div class="{{ isset($turnos) ? 'col-9' : 'col-12' }}">
                                <table id="datatable" class="table table-striped dataTable display" style="width: 100%;">
                                    <thead>
                                        <tr role="row">
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 100px;">Folio</th>
                                            @if(in_array($user->permisos->perfil, ["Cajera","Administrador"]))
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 300px;">Contacto</th>
                                            @endif
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 320px;" >Itinerario</th>
                                            @if(count($reservaciones) > 0 && isset($reservaciones[0]->status))
                                                <th class="sorting_asc text-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 98px;">Status/Operador</th>
                                            @endif
                                            @if(in_array($user->permisos->perfil, ["Cajera","Administrador"]))
                                                <th class="sorting_asc text-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 98px;">Precio</th>
                                            @endif
                                            <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 183px;">Fecha Reserva</th>
                                            @if(in_array($user->permisos->perfil, ["Cajera","Administrador"]))
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 167px;">Acciones</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody id="viajes">
                                        @foreach($reservaciones as $reservacion)
                                            <tr>
                                                <td>{{ $reservacion->folio }}</td>
                                                @if(in_array($user->permisos->perfil, ["Cajera","Administrador"]))
                                                    <td>
                                                        {{ strtoupper($reservacion->nombre) }}
                                                        <br>
                                                        {{ $reservacion->telefono }}
                                                    </td>
                                                @endif
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
                                                @if(in_array($user->permisos->perfil, ["Cajera","Administrador"]))
                                                    <td class="text-center cp" title="{{ $reservacion->tipo_pago }}">{{ "$". number_format($reservacion->precio,2) }}</td>
                                                @endif
                                                <td>{{ date('d-m-Y H:m',strtotime($reservacion->date_creacion)) }}</td>
                                                @if(in_array($user->permisos->perfil, ["Cajera","Administrador"]))
                                                    <td>
                                                        @if(isset($reservacion->status))
                                                            @if($reservacion->status == "Pending")
                                                                <button class="btn btn-sm btn-info text-white btnTicket" data-attr="{{ $reservacion->id_viaje }}" disabled="true">
                                                                    <i class="fa fa-print" aria-hidden="true" title="Generar Ticket"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-secondary text-white btnAsignarOperador" data-attr="{{ $reservacion->id_viaje }}"  title="Asignar Operador">
                                                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                                                </button>
                                                            @else
                                                                <button class="btn btn-sm btn-info text-white btnTicket" data-attr="{{ $reservacion->id_viaje }}">
                                                                    <i class="fa fa-print" aria-hidden="true" title="Generar Ticket"></i>
                                                                </button>
                                                            @endif
                                                        @else
                                                            <button class="btn btn-sm btn-info text-white btnTicket" data-attr="{{ $reservacion->id_viaje }}">
                                                                <i class="fa fa-print" aria-hidden="true" title="Generar Ticket"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                @endif
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
    @if(isset($vehiculos)) 
        @include('components.modales.modal_asignar_vehiculo', $vehiculos)
    @endif
    

    <x-slot name="scripts">
        <script>
            window.routes = {
                'generarTicket' : '{{ route('admin.api.generar') }}',
                'agregarNuevoTurno' : '{{ route('admin.api.agregarNuevoTurno') }}',
                'obtenerTurnosAsync' : '{{ route('admin.api.obtenerTurnosAsync') }}',
                'asignarOperadorAViaje' : '{{ route('admin.api.asignarOperadorAViaje') }}'
            }
            window.user = @json($user);
        </script>
    </x-slot> 
</x-layout>  