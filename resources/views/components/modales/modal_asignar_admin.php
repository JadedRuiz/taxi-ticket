<button type="button" class="d-none btnModalAsigViajeAdmin" data-bs-toggle="modal" data-bs-target="#modalAsigViajeAdmin"></button>
<div class="modal fade" id="modalAsigViajeAdmin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Asignación de Operador al Viaje
                    <br>
                    <p class="card-title-desc">Aquí podrás asignar libremente el viaje al operador en turno</p>
                </h1>
                <button type="button" class="btn-close btnModalAsigViajeAdminClose" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12">
                        <ul class="list-group list-turnos">
                            <li class="list-group-item row px-0 mx-0 d-flex list-item-header">
                                <div class="col-2 px-0 border-orden">Orden</div>
                                <div class="col-10 d-flex justify-content-between">
                                    Vehiculo & Operador         
                                </div>
                            </li>
                            <div class="lstTurnosAdmin"></div>                            
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>