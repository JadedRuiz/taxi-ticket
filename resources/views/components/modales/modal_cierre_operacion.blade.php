<button type="button" class="d-none btnModalCierreOperacion" data-bs-toggle="modal" data-bs-target="#modalCierreOperacion"></button>
<div class="modal fade" id="modalCierreOperacion" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Información de cierre de operacion
                    <br>
                    <p class="card-title-desc">Aquí podrás visualizar el detalle de tus ventas</p>
                </h1>
                {{-- <button type="button" class="btn-close btnModalClose"></button> --}}
            </div>
            <div class="modal-body">
                <div class="row my-0">                    
                    <p class="col-12 my-0 txtTitleCorte" style="font-size: 16px !important;">Caja</p>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                              <th scope="col">#Folio</th>
                              <th scope="col">Tipo de Pago</th>
                              <th scope="col">Precio</th>
                              <th scope="col">Status</th>
                            </tr>
                        </thead>
                        <tbody class="infoVentas"></tbody>
                    </table>
                </div>
                <div class="row mt-3">
                    <div class="col-12 d-flex justify-content-end align-items-center totales">
                        <p>Total Viajes:</p>&nbsp;&nbsp;<span class="no_venta"></span>
                    </div>
                    <div class="col-12 d-flex justify-content-end align-items-center totales">
                        <p>Total Efectivo:</p>&nbsp;&nbsp;<span class="total_cash"></span>
                    </div>
                    <div class="col-12 d-flex justify-content-end align-items-center totales">
                        <p>Total Tarjetas:</p>&nbsp;&nbsp;<span class="total_tarjet"></span>
                    </div>
                    <div class="col-12 d-flex justify-content-end align-items-center totales">
                        <p>Total:</p>&nbsp;&nbsp;<span class="total"></span>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-12 d-flex justify-content-center align-items-center">
                        <div class="btn btn-sm btn-warning text-white" data-bs-dismiss="modal" aria-label="Close">Cerrar</div>&nbsp;&nbsp;
                        <a class="btn btn-sm btn-danger text-white" href="{{ route('auth.logout') }}">Cerrar sesión</a>
                    </div>
                </div>      
            </div>
        </div>
    </div>
</div>