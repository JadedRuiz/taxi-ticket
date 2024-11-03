<x-layout :user-data="$user" :entries="$entries">
    <div class="page-content">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Página Vehiculos</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active">Vehiculos</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Administrador de vehiculos</h4>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <p class="card-title-desc">En este módulo podrás agregar o modificar un vehiculo y sus operadores.</p>
                            <button class="btn btn-sm btn-success btnAdd" style="padding-bottom: 0px;">
                                <span class="mdi--truck-plus"></span>
                            </button>
                        </div>
                        <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-striped dataTable display" style="width: 100%;">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 200px;">Fotografia</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 200px;">Vehiculo</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 125px; vertical-align: middle" >Marca & Modelo</th>
                                                <th class="sorting_asc text-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 100px;">No. Operadores</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 125px;">Estatus</th>
                                                <th class="sorting_asc text-center" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 167px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-vehiculos">
                                          @if ($vehiculos["ok"])
                                            @foreach($vehiculos["data"] as $vehiculo)
                                              <tr>
                                                <td>
                                                  @if($vehiculo->namespace == "image") 
                                                    <img src="{{ asset($vehiculo->path) }}" width="60" height="50">
                                                  @elseif($vehiculo->namespace == "url")
                                                    <img src="{{ $vehiculo->path }}" width="60" height="50">
                                                  @else
                                                    <img src="{{ asset('/img/Image_not_available.png') }}" width="80" height="50">
                                                  @endif
                                                </td>
                                                <td>{{ $vehiculo->vehiculo }}</td>
                                                <td>{{ $vehiculo->marca }} ({{ $vehiculo->modelo }})</td>
                                                <td class="text-center">
                                                  {{ $vehiculo->no_operadores }} &nbsp;&nbsp;
                                                  <button class="btn btn-sm btn-success text-white" style="padding-bottom: 0px;" id="btnOperador" data-attr="{{ $vehiculo->id_vehiculo }}">
                                                    <span class="ic--baseline-group-add"></span>
                                                  </button>
                                                </td>
                                                <td>{{ $vehiculo->activo == 1 ? 'En circulación' : 'No circulado' }}</td>
                                                <td class="text-center">
                                                  <button class="btn btn-sm btn-warning text-white editVehiculo" style="padding-bottom: 0px;" data-attr="{{ $vehiculo->id_vehiculo }}">
                                                    <span class="ic--baseline-edit"></span>
                                                  </button>
                                                </td>
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
    </div>

    <!-- Modal Operadores -->
    @include('components.modales.modal_vehiculo')

    <!-- Modal Operador -->
     @include('components.modales.modal_operador');

    <x-slot name="scripts">
        <script>
            window.routes = {
                'guardarVehiculo' : '{{ route('admin.api.guardarVehiculo') }}',
                'getVehiculoId' : '{{ route('admin.api.getVehiculoId') }}',
                'guardarOperador' : '{{ route('admin.api.guardarOperador') }}',
                'getVehiculoOperadores' : '{{ route('admin.api.getVehiculoOperadores') }}',
                'asignarOperadorVehiculo' : '{{ route('admin.api.asignarOperadorVehiculo') }}',
                'getOperadorId' : '{{ route('admin.api.getOperadorId') }}'
            }
        </script>
    </x-slot> 
</x-layout>
