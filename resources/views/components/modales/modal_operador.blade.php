<button type="button" class="d-none btnModalOperador" data-bs-toggle="modal" data-bs-target="#modalOperador"></button>
<div class="modal fade" id="modalOperador" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Asignación de Operadores
                    <br>
                    <p class="card-title-desc">Aquí podrás administrar tus operadores o seleccionar un nuevo operador al vehiculo seleccionado</p>
                </h1>
                <button type="button" class="btn-close btnModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row align-items-end">
                    <div class="col-10">
                        <div class="form-group">
                            <label for="inpSearchOpe">Buscador</label>
                            <input type="search" id="inpSearchOpe" class="form-control" placeholder="Ingrese el Nombre o No. del Empleado">
                        </div>
                    </div>
                    <div class="col-2">
                        <button type="button" class="btn btn-success" style="padding-bottom: 0px;">
                            <span class="flowbite--user-add-solid"></span>
                        </button>
                    </div>
                </div>
                <div class="row mt-3">
                    <form id="form-operador" enctype="multipart/form-data" class="row needs-validation" novalidate>
                        <p class="col-12 mb-1 txtTitle">Nuevo operador</p>
                        <input type="hidden" id="id_operador" value="0">
                        <input type="hidden" id="id_direccion" value="0">
                        <div class="form-group col-12">
                        <label for="fotografia">Fotografia</label>
                        <div class="input-group">
                            <input type="hidden" id="fotografia_id" value="0">
                            <input type="text" class="form-control" placeholder="Ingrese URL o Adjunte" id="fotografia">
                            <input type="file" class="d-none" id="inpAdj" accept="image/png, image/jpeg, image/jpg">
                            <button class="btn btn-outline-secondary" type="button" id="btnAdj">Adjuntar</button>
                            </div>
                            <small class="text-warning">Tamaño máximo aceptado (5Mb)</small>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="nombres">Nombre(s)</label>
                            <input type="text" class="form-control t-upper" id="nombres" maxlength="200" placeholder="Ingrese el nombre" required>
                        </div>
                        <div class="form-group col-lg-6 col-sm-12">
                            <label for="apellidos">Apellido(s)</label>
                            <input type="text" class="form-control t-upper" id="apellidos" maxlength="200" placeholder="Ingrese el apellido" required>
                        </div>
                        <div class="form-group col-lg-7 col-sm-12">
                            <label for="correo">Correo</label>
                            <input type="text" class="form-control t-upper" id="correo" maxlength="200" placeholder="Ingrese el correo" required>
                        </div>
                        <div class="form-group col-lg-5 col-sm-12">
                            <label for="telefono">Telefono</label>
                            <input type="text" class="form-control t-upper" id="telefono" maxlength="200" placeholder="Ingrese el numero" required>
                        </div>
                        <div class="form-group col-lg-7 col-sm-12">
                            <label for="curp">CURP</label>
                            <input type="text" class="form-control t-upper" id="curp" maxlength="200" placeholder="Ingrese el correo" required>
                        </div>
                        <div class="form-group col-lg-5 col-sm-12">
                            <label for="edad">Edad</label>
                            <input type="text" class="form-control t-upper" id="edad" maxlength="200" placeholder="Ingrese el numero" required>
                        </div>
                        <div class="form-group col-lg-4 col-sm-12">
                            <label for="dtNacimiento">Fecha Nacimiento</label>
                            <input type="date" class="form-control t-upper" id="dtNacimiento" maxlength="200" placeholder="Ingrese el numero" required>
                        </div>
                        <div class="form-group col-lg-4 col-sm-12">
                            <label for="dtIngreso">Fecha Ingreso</label>
                            <input type="date" class="form-control t-upper" id="dtIngreso" maxlength="200" placeholder="Ingrese el numero" required>
                        </div>
                        <div class="form-group col-lg-4 col-sm-12">
                            <label for="dtBaja">Fecha baja</label>
                            <input type="date" class="form-control t-upper" id="dtBaja" maxlength="200" placeholder="Ingrese el numero" required>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="status d-inline">Estatus:</label>
                            <span class="badge bg-success d-inline cp">Activo</span>
                            <span class="badge bg-secondary d-inline cp">Suspendido</span>
                            <span class="badge bg-warning d-inline cp">Inactivo</span>
                        </div>
                    </form>
                </div>
                <div class="row py-3 mt-1 lstOperadores">
                    <p class="col-12 my-0 px-0 txtTitle">Mis operadores</p>
                    @if($operadores["ok"] && count($operadores["data"]) > 0)
                        <div class="card col-12 mt-1">
                            <div class="row g-0">
                                <div class="col-md-4">
                                    <img src="..." class="img-fluid rounded-start" alt="...">
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <h5 class="card-title">JADED ENRIQUE RUIZ PECH</h5>
                                        <p class="card-text">No. Empleado, (999) 272 64 85, razonable3500@gmail.com</p>
                                        <p class="card-text"><small class="text-muted">Suspendido</small></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="card col-12 mt-1">
                            <div class="card-body text-center">
                                <p class="card-text">Aún no se han agregado operadores al catálogo</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>