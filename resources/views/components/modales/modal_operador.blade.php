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
                        <button type="button" class="btn btn-success" style="padding-bottom: 0px;" id="btnNewOperador">
                            <span class="flowbite--user-add-solid"></span>
                        </button>
                    </div>
                </div>
                <div class="row mt-3" style="display: none" id="formOperador" data-show="false">
                    <form id="form-operador" enctype="multipart/form-data" class="row needs-validation" novalidate>
                        <p class="col-12 mb-1 txtTitle">Nuevo operador</p>
                        <input type="hidden" id="id_operador" value="0">
                        <input type="hidden" id="id_direccion" value="0">
                        <div class="form-group col-12">
                        <label for="fotografia">Fotografia</label>
                        <div class="input-group">
                            <input type="hidden" id="fotografia_id" value="0">
                            <input type="text" class="form-control" placeholder="Adjunte la imagen del operador" id="fotografia_operador" disabled>
                            <input type="file" class="d-none" id="inpAdjOperador" accept="image/png, image/jpeg, image/jpg">
                            <button class="btn btn-outline-secondary" type="button" id="btnAdjOperador">Adjuntar</button>
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
                            <input type="text" class="form-control" id="correo" maxlength="100" placeholder="Ingrese el correo">
                        </div>
                        <div class="form-group col-lg-5 col-sm-12">
                            <label for="telefono">Telefono</label>
                            <input type="text" class="form-control t-upper" id="telefono" maxlength="10" placeholder="Ingrese el numero">
                        </div>
                        <div class="form-group col-lg-4 col-sm-12">
                            <label for="no_licencia">No. licencia</label>
                            <input type="text" class="form-control t-upper" id="no_licencia" maxlength="100" placeholder="Ingrese la licencia" required>
                        </div>
                        <div class="form-group col-lg-5 col-sm-12">
                            <label for="dtVigencia">Fecha Vigencia</label>
                            <input type="date" class="form-control" id="dtVigencia" required>
                        </div>
                        <div class="form-group col-lg-3 col-sm-12">
                            <label for="edad">Edad</label>
                            <input type="text" class="form-control t-upper" id="edad" maxlength="2">
                        </div>
                        <div class="form-group col-lg-7 col-sm-12">
                            <label for="curp">CURP</label>
                            <input type="text" class="form-control t-upper" id="curp" maxlength="18" placeholder="Ingrese el curp">
                        </div>
                        
                        <div class="form-group col-lg-5 col-sm-12">
                            <label for="dtNacimiento">Fecha Nacimiento</label>
                            <input type="date" class="form-control" id="dtNacimiento">
                        </div>
                        <div class="form-group col-lg-5 col-sm-12">
                            <label for="dtIngreso">Fecha Ingreso</label>
                            <input type="date" class="form-control" id="dtIngreso">
                        </div>
                        <div class="form-group col-lg-5 col-sm-12">
                            <label for="dtBaja">Fecha baja</label>
                            <input type="date" class="form-control" id="dtBaja">
                        </div>
                        <div class="form-group col-12">
                            <label for="direccion">Dirección</label>
                            <textarea class="form-control t-upper" id="direccion" maxlength="350" placeholder="Escriba la direccion" row="1"></textarea>
                        </div>
                        <div class="col-12 mt-2">
                            <label for="status d-inline">Estatus:</label>
                            <span class="badge bg-info d-inline cp status" style="border: 2px black solid" data-status="1">Activo</span>
                            <span class="badge bg-secondary d-inline cp status" data-status="2">Suspendido</span>
                            <span class="badge bg-warning d-inline cp status" data-status="3">Inactivo</span>
                            <input type="hidden" class="form-control" id="status" value="1">
                        </div>
                        <div class="col=12 d-flex justify-content-end btnGuardar">
                            <a type="submit" class="btn btn-sm btn-success col-3 d-flex justify-content-center align-items-center" id="btnSaveOpe">
                                <div class="spinner-border text-light mx-2 d-none spiner-loading" role="status"></div>
                                <i class="fa fa-floppy-o mx-2" aria-hidden="true"></i>
                                <div class="loading-text">Guardar</div>
                            </a>
                        </div>
                        <div class="col-12 mt-1 mb-0">
                            <div class="alert w-100" role="alert" id="alert-form-ope" style="display:none;"></div>
                        </div>
                    </form>                    
                </div>
                <div class="row py-3 mt-1">                    
                    <p class="col-12 my-0 txtTitle">Mis operadores</p>
                </div>
                <div class="row lstOperadores"></div>
            </div>
        </div>
    </div>
</div>