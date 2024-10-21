<x-layout :user-data="$user" :entries="$entries">
    <div class="page-content">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Página Destinos</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item active">Destinos</li>
                </ol>
            </div>    
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Administrador de destinos</h4>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <p class="card-title-desc">En este módulo podrás agregar o modificar un destino.</p>
                            <button class="btn btn-sm btn-success btnAdd" style="padding-bottom: 0px;">
                                <span class="line-md--map-marker-plus"></span>
                            </button>
                        </div>                        
                        <div id="datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="datatable" class="table table-striped dataTable display" style="width: 100%;">
                                        <thead>
                                            <tr role="row">
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 200px;">Destino</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 200px;">Dirección</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 125px; vertical-align: middle" >Precio</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 100px;">Distancia</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 100px;">Duracion</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 125px;">Estatus</th>
                                                <th class="sorting_asc" tabindex="0" aria-controls="datatable" rowspan="1" colspan="1" style="width: 167px;">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if ($destinos["ok"])
                                                @foreach($destinos["data"] as $destino)
                                                    <tr>
                                                        <td>{{ strtoupper($destino->sNombre) }}</td>
                                                        <td>
                                                            {{ $destino->sDireccion != "" ? strtoupper($destino->sDireccion) : "Sin asignar" }}
                                                        </td>
                                                        <td class="text-center">
                                                            {{ "$".number_format(floatval($destino->precio),2) }} 
                                                        </td>
                                                        <td>{{ $destino->distancia != "" ? $destino->distancia." Km" : "Sin aginar" }}</td>
                                                        <td>{{ $destino->duracion != "" ? $destino->duracion : "Sin asignar" }}</td>
                                                        <td>{{ $destino->activo ? "Habilitado" : "Deshabilitado" }}</td>
                                                        <td>
                                                            <button class="btn btn-sm btn-warning text-white btnEdit" data-attr="{{ $destino->iIdDestino }}"><i class="fa fa-pencil" aria-hidden="true"></i></button>
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
    <button type="button" class="d-none btnModal" data-bs-toggle="modal" data-bs-target="#modalDestino"></button>
    <div class="modal fade" id="modalDestino" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close btnModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form class="form-info d-none">
                        <div class="row">
                            <div class="col-12">
                                <label for="destino" class="form-label">Destino</label>
                                <input type="text" class="form-control" id="destino" style="text-transform: uppercase;">
                                <div id="emailHelp" class="form-text">Este será el nombre del titulo.</div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="precio" class="form-label">Precio</label>
                                <input type="number" class="form-control" id="precio">
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="duracion" class="form-label">Duración</label>
                                <div class="d-flex">
                                    <input type="text" class="form-control" id="duracion" placeholder="hh:mm">
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="distancia" class="form-label">Distancia</label>
                                <input type="number" class="form-control" id="distancia">
                            </div>
                        </div>
                        
                        <p class="my-2" style="font-size: 18px; color: #212529">Dirección</p>

                        <div class="row">
                            <div class="col-12">
                                <label for="colonia" class="form-label">Colonia</label>
                                <input type="text" class="form-control" id="colonia">
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <label for="calle" class="form-label">Calle</label>
                                <input type="text" class="form-control" id="calle">
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <label for="ciudad" class="form-label">Ciudad</label>
                                <input type="text" class="form-control" id="ciudad">
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="noint" class="form-label">No. int</label>
                                <input type="text" class="form-control" id="noint">
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="noext" class="form-label">No. ext</label>
                                <input type="text" class="form-control" id="noext">
                            </div>
                            <div class="col-lg-4 col-sm-12">
                                <label for="cp" class="form-label">C.P</label>
                                <input type="text" class="form-control" id="cp">
                            </div>
                        </div>
                        <div class="row d-flex justify-content-end mt-4">
                            <button type="submit" class="btn btn-sm btn-success col-3" id="btnSave">
                                <i class="fa fa-floppy-o" aria-hidden="true"></i> &nbsp; Guardar
                            </button>
                        </div>                        
                    </form>
                    <div class="loading-form d-flex flex-column justify-content-center align-items-center mt-2" style="height: 150px">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2" style="font-size: 18px">Cargando formulario</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>