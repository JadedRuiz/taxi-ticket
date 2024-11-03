<button type="button" class="d-none btnModalAsigViaje" data-bs-toggle="modal" data-bs-target="#modalAsigViaje"></button>
<div class="modal fade" id="modalAsigViaje" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Nuevo turno
                    <br>
                    <p class="card-title-desc">Aquí podrás agregar un vehiculo con su operador a la lista de turnos</p>
                </h1>
                <button type="button" class="btn-close btnModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row my-0">                    
                    <p class="col-12 my-0 txtTitle">Mis vehiculos</p>
                </div>
                <div class="lstVehiculos">
                    @if($vehiculos["ok"] && count($vehiculos["data"]) > 0)
                        @foreach($vehiculos["data"] as $vehiculo)
                            <div class="card col-12 mt-1 just mt-2">
                                <div class="row g-0">
                                    <div class="col-md-3 cont-img">
                                        <img src="{{ asset($vehiculo->path) }}" class="rounded-start img-fluid img-defualt">                               
                                    </div>
                                    <div class="col-md-9">
                                        <div class="card-body">
                                            <h5 class="card-title py-0 my-0">{{ $vehiculo->vehiculo }}</h5>
                                            <p class="card-text py-0 my-0">{{ $vehiculo->marca }} ({{ $vehiculo->modelo }})</p>
                                            <div class="d-flex flex-column">
                                                <p class="card-text my-0">Operadores: </p>
                                                    @if(count($vehiculo->operadores) > 0)
                                                        <div class="d-flex justify-content-between">
                                                            <select name="operadores" style="font-size: 12px" class="slcOperadores form-select">
                                                                <option value="0" selected>Seleccione un operador</option>
                                                                @foreach($vehiculo->operadores as $operador) 
                                                                    <option value="{{ $operador->id_vehiculo_operador }}">
                                                                        {{ $operador->nombres }} {{ $operador->apellidos }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <button class="btn btn-sm btn-success btnNuevoTurno">
                                                                <i class="fa fa-floppy-o mx-2" aria-hidden="true"></i>
                                                            </button>
                                                        </div>                                                        
                                                    @else
                                                        <small class="text-danger">Este vehiculo no cuenta con operadores</small>
                                                    @endif
                                            </div>                        
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="card col-12 mt-1">
                            <div class="card-body text-center">
                                <p class="card-text">Aún no se han agregado vehiculos</p>
                            </div>
                        </div>
                    @endif
                </div>
                
            </div>
        </div>
    </div>
</div>