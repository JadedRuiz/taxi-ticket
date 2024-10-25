<button type="button" class="d-none btnModal" data-bs-toggle="modal" data-bs-target="#modalVehiculo"></button>
    <div class="modal fade" id="modalVehiculo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel"></h1>
                    <button type="button" class="btn-close btnModalClose" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="form-vehiculo" enctype="multipart/form-data" class="row needs-validation" novalidate>
                      <input type="hidden" id="id_vehiculo" value="0">
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
                    <div class="row d-flex justify-content-end mt-4 w-100 px-0 mx-0">
                      <a type="submit" class="btn btn-sm btn-success col-2 d-flex justify-content-center align-items-center" id="btnSave">
                          <div class="spinner-border text-light mx-2 d-none spiner-loading" role="status"></div>
                          <i class="fa fa-floppy-o mx-2" aria-hidden="true"></i>
                          <div class="loading-text">Guardar</div>
                      </a>
                    </div>
                    <div class="row mt-1 mb-0">
                      <div class="alert w-100" role="alert" id="alert-form" style="display:none;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>