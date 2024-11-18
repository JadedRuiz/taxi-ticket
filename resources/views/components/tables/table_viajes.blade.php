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
            @if($reservacion->status == "Pending" || $reservacion->status == "Cobrado")
                <td class="text-center"><span class="badge rounded-pill bg-primary">Sin asginación</span></td>
            @endif
            @if($reservacion->status == "Cancelado")
                <td class="text-center"><span class="badge rounded-pill bg-warning">Cancelado</span></td>
            @endif
            @if($reservacion->status == "En servicio")
                <td class="text-center">
                    <span class="badge rounded-pill bg-success">Asignado</span>
                    <br>
                    {{ $reservacion->nombres }} {{ $reservacion->apellidos }}
                </td>
            @endif
        @endif
        <td class="text-center cp" title="{{ $reservacion->tipo_pago }}">{{ "$". number_format($reservacion->precio,2) }}</td>
        <td>{{ date('d-m-Y H:i',strtotime($reservacion->date_creacion)) }}</td>
        @if($caja_id != 0)
            <td>
                @if(isset($reservacion->status))
                    @if($reservacion->status == "Pending" || $reservacion->status == "Cobrado")
                        <button class="btn btn-sm btn-secondary text-white btnAsignarOperador" data-attr="{{ $reservacion->id_viaje }}"  title="Asignar Operador">
                            <i class="fa fa-check-square" aria-hidden="true"></i>
                        </button>
                    @endif
                    @if($reservacion->status == "En servicio")
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
        @if($caja_id == 0)
            <td>
                <div class="dropdown">
                    <button class="btn btn-sm btn-info text-white {{$reservacion->status == "Cancelado" ? 'disabled' : ''}}" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item cp btnAsignarOperadorAdmin"data-attr="{{ $reservacion->id_viaje }}">Asignar viaje</a></li>
                    <li><a class="dropdown-item cp btnTicket {{$reservacion->status == "Pending" ? 'disabled' : ''}}" data-attr="{{ $reservacion->id_viaje }}">Generar ticket</a></li>
                    <li><a class="dropdown-item cp btnCancelar" data-attr="{{ $reservacion->id_viaje }}">Cancelar Viaje</a></li>
                    </ul>
                </div>
            </td>
        @endif
    </tr>                                            
@endforeach