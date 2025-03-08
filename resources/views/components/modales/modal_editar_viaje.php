<button type="button" class="d-none btnEditarViaje" data-bs-toggle="modal" data-bs-target="#modalEditarViaje"></button>
<div class="modal fade" id="modalEditarViaje" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Administrar Viaje
                    <br>
                    <p class="card-title-desc">Edita o cambia el status del viaje seleccionado</p>
                </h1>
                <button type="button" class="btn-close btnModalCloseEditar" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row gap-2">
                    <div class="col-12 mt-1">
                        <h5>Cambio de Status</h5>
                    </div>
                    <div class="col-12 d-flex flex-column mt-2">
                        <div class="inpStatus" data-operador="0">
                            <div class="status disabled" data-status="En servicio">
                                <div class="circle bg-success"></div> <span>Asignado</span>
                            </div>
                            <div class="status" data-status="Pending">
                                <div class="circle bg-primary"></div> <span>Sin asignación</span>
                            </div>
                            <div class="status" data-status="Cancelado">
                                <div class="circle bg-warning"></div> <span>Cancelado</span>
                            </div>
                            <div class="status" data-status="Cobrado">
                                <div class="circle bg-info"></div> <span>Cobrado</span>
                            </div>
                            <div class="status" data-status="Cerrado">
                                <div class="circle bg-danger"></div> <span>Cerrado</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="accordion p-1" id="accordionExample">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="headingOne">
                                    <button class="accordion-button p-1 bg-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                        <strong>Significado de los Status:</strong>
                                    </button>
                                </h2>
                                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                    <div class="accordion-body d-flex flex-column">
                                        <small class="text-primary">Sin asignación: Viaje sin un operador asignado</small>
                                        <small class="text-warning">Cancelado: Viaje que ha sido cancelado</small>
                                        <small class="text-info">Cobrado: Viaje que que ya ha sido reportado en corte de caja pero que aun no se ha asignado (Aplica para las reservas y aparece como "Sin asignación")</small>
                                        <small class="text-danger">Cerrado: Viaje que ya ha sido reportado en corte de caja</small>  
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mt-1">
                        <h5>Edicion del Viaje</h5>
                    </div>
                    <form id="formViaje" class="row needs-validation" novalidate>                        
                        <div class="col-lg-2 col-sm-12 mt-2">
                            <label for="folio" class="lblInp">Folio</label>
                            <input type="text" class="form-control" id="folio" name="folio" disabled>
                        </div>
                        <div class="col-lg-3 col-sm-12 mt-2">
                            <label for="date_creacion" class="lblInp required">Fecha/Hora</label>
                            <input type="datetime-local" class="form-control" id="date_creacion" name="date_creacion">
                        </div>
                        <div class="col-lg-3 col-sm-12 mt-2">
                            <label for="caja" class="lblInp required">Caja</label>
                            <select class="form-select" aria-label="Caja que registro" id="caja_id" name="caja_id">
                                <option value="1">Caja Uno</option>
                                <option value="2">Caja Dos</option>
                            </select>
                        </div>
                        <div class="col-lg-4 col-sm-12 mt-2">
                            <label for="nombre" class="lblInp required">Nombre Cliente</label>
                            <input type="text" class="form-control" id="nombre" name="nombre">
                        </div>
                        <div class="col-lg-4 col-sm-12 mt-2">
                            <label for="telefono" class="lblInp required">Teléfono Cliente</label>
                            <input type="text" class="form-control" id="telefono" name="telefono">
                        </div>
                        <div class="col-lg-4 col-sm-12 mt-2">
                            <label for="correo" class="lblInp required">Correo Cliente</label>
                            <input type="text" class="form-control" id="correo" name="correo">
                        </div>
                        <div class="col-lg-4 col-sm-12 mt-2">
                            <label for="origen" class="lblInp">Origen</label>
                            <input type="text" class="form-control" id="origen" name="origen" disabled>
                        </div>
                        <div class="col-lg-4 col-sm-12 mt-2">
                            <label for="destino" class="lblInp">Destino</label>
                            <input type="text" class="form-control" id="destino" name="destino" disabled>
                        </div>
                        <div class="col-lg-8 col-sm-12 mt-2">
                            <label for="operador" class="lblInp">Operador</label>
                            <div id="operador" class="d-flex gap-2">
                                <input type="text" class="form-control" id="nombres" name="nombres" disabled>
                                <input type="text" class="form-control" id="apellidos" name="apellidos" disabled>
                            </div>
                        </div>
                        <div class="col-lg-3 col-sm-12 mt-2">
                            <label for="precio" class="lblInp required">Precio</label>
                            <input type="text" class="form-control" id="precio" name="precio_viaje">
                        </div>
                        <div class="col-lg-3 col-sm-12 mt-2">
                            <label for="tipo_pago" class="lblInp required">Tipo de pago</label>
                            <select class="form-select" aria-label="Caja que registro" id="tipo_pago" name="tipo_pago">
                                <option value="Credit card on pickup">Pago con targeta</option>
                                <option value="Cash">Efectivo</option>
                            </select>
                        </div>
                        <div class="col-12 mt-2 d-flex justify-content-end">
                            <button type="submit" class="btn btn-success">Editar</button>
                        </div>
                    </form>                    
                </div>
            </div>
        </div>
    </div>
</div>