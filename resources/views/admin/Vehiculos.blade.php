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
                                        <tbody>
                                          @if ($vehiculos["ok"])
                                            @foreach($vehiculos["data"] as $vehiculo)
                                              <tr>
                                                <td>
                                                  <img src="https://img.freepik.com/psd-gratis/coche-plata-sedan_53876-84522.jpg?w=1060&t=st=1729631274~exp=1729631874~hmac=075754f8fdad589dca9447536fae57345eaba4a4caba2d2d3e945cbb34d3f520" width="60" height="50">
                                                </td>
                                                <td>{{ $vehiculo->vehiculo }}</td>
                                                <td>{{ $vehiculo->marca }} ({{ $vehiculo->modelo }})</td>
                                                <td class="text-center">
                                                  {{ $vehiculo->no_operadores }} &nbsp;&nbsp;
                                                  <button class="btn btn-sm btn-success text-white" style="padding-bottom: 0px;">
                                                    <span class="ic--baseline-group-add"></span>
                                                  </button>
                                                </td>
                                                <td>{{ $vehiculo->activo == 1 ? 'En circulación' : 'No circulado' }}</td>
                                                <td class="text-center">
                                                  <button class="btn btn-sm btn-warning text-white" style="padding-bottom: 0px;">
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
    {{-- Modal --}}
    <button type="button" class="d-none btnModal" data-bs-toggle="modal" data-bs-target="#modalDestino"></button>
    <div class="modal fade" id="modalDestino" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close btnModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="row needs-validation" id="form-vehiculo" novalidate>
                      <div class="form-group col-12">
                        <label for="fotografia">Fotografia</label>
                        <div class="input-group mb-3">
                          <input type="text" class="form-control" placeholder="Ingrese URL o Adjunte" id="fotografia">
                          <input type="file" class="d-none" id="inpAdj" accept="image/png, image/jpeg, image/jpg">
                          <button class="btn btn-outline-secondary" type="button" id="btnAdj">Adjuntar</button>
                        </div>
                        <!-- <input type="text" class="form-control t-upper" id="fotografia" maxlength="500" placeholder="Ingrese el vehiculo"> -->
                      </div>
                      <div class="form-group col-lg-4 col-sm-12">
                        <label for="vehiculo">Vehiculo</label>
                        <input type="text" class="form-control t-upper" id="vehiculo" maxlength="200" placeholder="Ingrese el vehiculo" required>
                      </div>
                      <div class="form-group col-lg-4 col-sm-12">
                        <label for="marca">Marca</label>
                        <input type="text" class="form-control t-upper" id="marca" maxlength="200" placeholder="Ingrese la marca" required>
                      </div>
                      <div class="form-group col-lg-4 col-sm-12">
                        <label for="modelo">Modelo</label>
                        <input type="text" class="form-control t-upper" id="modelo" maxlength="150" placeholder="Ingrese el modelo" required>
                      </div>
                      <div class="form-group col-lg-3 col-sm-12">
                        <label for="placa">Placa</label>
                        <input type="text" class="form-control t-upper" id="placa" maxlength="10" placeholder="Ingrese la placa" required>
                      </div>
                      <div class="form-group col-lg-4 col-sm-12">
                        <label for="no_serie">No. de Serie</label>
                        <input type="text" class="form-control t-upper" id="no_serie" maxlength="150" placeholder="Ingrese el No. serie">
                      </div>
                      <div class="form-group col-lg-5 col-sm-12">
                        <label for="aseguradora">Aseguradora</label>
                        <input type="text" class="form-control t-upper" id="aseguradora" maxlength="150" placeholder="Ingresa la aseguradora">
                      </div>
                      <div class="form-group col-lg-4 col-sm-12">
                        <label for="poliza">Poliza</label>
                        <input type="text" class="form-control t-upper" id="poliza" maxlength="150" placeholder="Ingresa la poliza">
                      </div>
                      <div class="form-group col-lg-4 col-sm-12">
                        <label for="dtFinPoliza">Fin de poliza</label>
                        <input type="date" class="form-control" id="dtFinPoliza">
                      </div>
                      <div class="form-group col-12">
                        <label for="notas">Notas</label>
                        <textarea class="form-control t-upper" id="notas" maxlength="500" placeholder="Escriba su nota" row="3"></textarea>
                      </div>
                    </form>
                    <div class="row d-flex justify-content-end mt-4">
                      <button type="submit" class="btn btn-sm btn-success col-3" id="btnSave">
                          <i class="fa fa-floppy-o" aria-hidden="true"></i> &nbsp; Guardar
                      </button>
                    </div>
                    <div class="row mt-1 mb-0">
                      <div class="alert alert-danger w-100" role="alert" id="alert-form" style="display:none;">
                        This is a danger alert—check it out!
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <x-slot name="scripts">
        <script>
            window.routes = {
                'guardarVehiculo' : '{{ route('admin.api.guardarVehiculo') }}',
                'getVehiculoId' : '{{ route('admin.api.getVehiculoId') }}'
            }
        </script>
    </x-slot> 
</x-layout>
