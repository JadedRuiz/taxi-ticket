<thead style="background-color: #e0e0e0">
    <tr role="row">
        @foreach($columnas as $columna)
            @if($columna == 0) <th class="text-start">Folio</th> @endif
            @if($columna == 1) <th class="text-start">Contacto</th> @endif
            @if($columna == 2) <th class="text-start">Itinerario</th> @endif
            @if($columna == 3) <th class="text-center">Estatus</th> @endif
            @if($columna == 4) <th class="text-start">Precio</th> @endif
            @if($columna == 5) <th class="text-start">Distancia</th> @endif
            @if($columna == 6) <th class="text-start">Duracion</th> @endif
            @if($columna == 7) <th class="text-center">Fecha Reserva</th> @endif
            @if($columna == 8) <th class="text-start">Vehiculo</th> @endif
            @if($columna == 9) <th class="text-start">Tipo pago</th> @endif
            @if($columna == 10) <th class="text-start">Operador</th> @endif
            @if($columna == 11) <th class="text-center">No. Caja</th> @endif
        @endforeach
    </tr>
</thead>
<tbody>
    @if(count($turnos) > 0)
        @foreach($turnos as $turno)
            <tr class="bg-secondary">
                <th colspan="{{count($columnas)}}">Inicio de operacion: {{$turno->dt_inicio_operacion}}</th>
            </tr>
            @if(count($turno->viajes) > 0)
                @foreach($turno->viajes as $viaje) 
                    <tr>
                        @foreach($columnas as $columna)
                            @if($columna == 0) <th class="text-start">{{$viaje->folio}}</th> @endif
                            @if($columna == 1) <td class="text-start">{{$viaje->nombre}} <br> {{$viaje->correo}}</td> @endif
                            @if($columna == 2) <td class="text-start">1. {{$viaje->origen}} <br> 2. {{$viaje->destino}}</td> @endif
                            @if($columna == 3) <td class="text-center">{{$viaje->status}}</td> @endif
                            @if($columna == 4) <td class="text-start">{{$viaje->precio}}</td> @endif
                            @if($columna == 5) <td class="text-start">{{$viaje->distancia}}</td> @endif
                            @if($columna == 6) <td class="text-start">{{$viaje->duracion}}</td> @endif
                            @if($columna == 7) <td class="text-center">{{$viaje->fecha_viaje}}</td> @endif
                            @if($columna == 8) <td class="text-start">{{$viaje->vehiculo}} ({{$viaje->marca}}-{{$viaje->modelo}}) - {{$viaje->placa}}</td> @endif
                            @if($columna == 9) <td class="text-start">{{$viaje->tipo_pago}}</td> @endif
                            @if($columna == 10) <td class="text-start">{{$viaje->nombres}} {{$viaje->apellidos}}</td> @endif
                            @if($columna == 11) <td class="text-center">Caja No.{{$viaje->no_caja}}</td> @endif
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr><td colspan="{{count($columnas)}}" class="text-center">No se han encontrado viajes</td></tr>
            @endif
            <tr class="bg-secondary">
                <th colspan="{{count($columnas)}}">
                    <div class="info-operacion d-flex justify-content-between"">
                        Fin de operacion: {{$turno->dt_fin_operacion}}, Total Viajes: {{$turno->no_ventas}}
                        <div class="totales">
                           Total Efectivo: ${{number_format($turno->total_efectivo,2,'.',',')}}, Total Tarjeta: ${{number_format($turno->total_tarjeta,2,'.',',')}}, Total: ${{number_format($turno->total_venta,2,'.',',')}}
                        </div>
                    </div>                    
                </th>
            </tr>
        @endforeach
    @else
        <tr><td colspan="{{count($columnas)}}">No se han encontrado resultados</td></tr>
    @endif
</tbody>