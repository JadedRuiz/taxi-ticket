<x-layout :user-data="$user"  :entries="$entries">
    <div class="page-content">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Página Reportes</h4>
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
                            <h4 class="card-title">Reportes</h4>
                        </div>
                        <p class="card-title-desc my-0">Generá los reportes de tus ventas desglosadas por operadores o cajeros.</p>
                        <form class="filtros d-flex align-items-end mt-3" id="aplicarFiltros">
                            <div class="form-group">
                                <label class="lblInp">Desglose por:</label>
                                <select class="form-select" id="desglose" name="tipo_filtro">
                                    <option value="1" selected>General</option>
                                    <option value="2">Caja</option>
                                    <option value="3">Operador</option>
                                </select>
                                <!-- <input type="text" class="form-control" placeholder="Folio" id="folio" name="folio" maxlength="50"> -->
                            </div>
                            <div class="form-group caja-filtro d-none">
                                <label class="lblInp">Seleccione la caja:</label>
                                <select class="form-select" id="caja" name="filtro_caja">
                                    <option value="1" selected>Caja Uno</option>
                                    <option value="2">Caja Dos</option>
                                </select>
                            </div>
                            <div class="form-group operador-filtro d-none">
                                <label class="lblInp">Seleccione el operador:</label>
                                <select class="form-select" id="operador" name="filtro_operador">
                                    @if($data_view["operadores"]["ok"])
                                        @foreach($data_view["operadores"]["data"] as $operador)
                                            <option value='{{$operador->id_operador}}' selected>{{ $operador->nombres ." ".$operador->apellidos }}</option>
                                        @endforeach
                                    @else
                                        <option value='0' selected>No se han encontrado operadores</option>
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="lblInp" for="fechas">Seleccione las fechas:</label>
                                <div class="fechas d-flex align-items-center" id="fechas">
                                    <input type="date" class="form-control " name="dt_inicio" value="" id="user" placeholder="dd/mm/yyyy">
                                    <span class="mx-1">a</span>
                                    <input type="date" class="form-control " name="dt_fin" value="" id="user" placeholder="dd/mm/yyyy">
                                </div>
                            </div>
                            <div class="form-group d-flex flex-column">
                                <label class="lblInp" for="select-columns">Mostrar:</label>
                                <select class="form-select" id="select-columns" multiple style="min-width: 180px;" name="columnas">
                                    <option selected value="-1" id="prete">Predeterminado</option>
                                    <optgroup label="Columnas" id="columns-dipo">
                                    </optgroup>
                                </select>
                            </div>
                            <button class="btn btn-success" type="submit">Enviar</button>
                        </form>
                        <div class="row mt-3">
                            <div class="col-12 table-responsive">
                                <table id="datatable" class="table" style="width: 100%;">
                                    <thead style="background-color: #e0e0e0">
                                        <tr role="row">
                                            <th class="text-start">Folio</th>
                                            <th class="">Contacto</th>
                                            <th class="">Itinerario</th>
                                            <th class="text-center">Status</th>
                                            <th class="text-center">Precio</th>
                                            <th class="text-center">Fecha Reserva</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($data_view["viajes"]["ok"])
                                            @foreach($data_view["viajes"]["data"] as $viaje) 
                                                <tr>
                                                    <th class="text-start">{{$viaje->folio}}</th>
                                                    <td>{{$viaje->nombre}} <br> {{$viaje->correo}}</td>
                                                    <td>
                                                        1. {{$viaje->origen}}
                                                        <br>
                                                        2. {{$viaje->destino}}
                                                    </td>
                                                    <td class="text-center">{{$viaje->status}}</td>
                                                    <td class="text-center">{{$viaje->precio}}</td>
                                                    <td class="text-center">{{$viaje->date_creacion}}</td>
                                                </tr>
                                            @endforeach
                                        @endif
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
                'generarConsulta' : '{{ route('admin.api.generarConsulta') }}'
            }
            window.user = @json($user);
        </script>
    </x-slot> 
</x-layout>  
