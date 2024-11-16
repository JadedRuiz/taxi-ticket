import DataTable from 'datatables.net-dt';
import $ from 'jquery';
import Swal from 'sweetalert2';

var id_viaje=0;
var user=0;
$(window).on("load", function() {
    new DataTable('#datatable', {
        ordering: false,
        lengthMenu: [[8,15,25,50,-1],["8","15","25","50","Todos"]],
        scrollY: '500px',
        scrollCollapse: true,
        pageLength: 8,
        pagingType: "simple_numbers",
        language: {
            "decimal": "",
            "emptyTable": "No hay información",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
            "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
            "infoFiltered": "(Filtrado de _MAX_ total entradas)",
            "infoPostFix": "",
            "thousands": ",",
            "lengthMenu": "Mostrar _MENU_ Entradas",
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "search": "Buscar:",
            "zeroRecords": "Sin resultados encontrados",
            "paginate": {
                "first": "Primero",
                "last": "Ultimo",
                "next": "Siguiente",
                "previous": "Anterior"
            }
        }
    });
    user = window.user;
    // actualizaViajes();
    actualizarTurnos();
});

$(document).on("click",".btnTicket", function() {
    let id_viaje= $(this).attr("data-attr");
    $.post(window.routes.generarTicket,{
        id_viaje: id_viaje
    }, (res) => {
        if(res.ok) {
            Swal.fire({
                title: "Confirmación",
                text: "¿Se ha realizado el cobro del servicio?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si, generar ticket",
                cancelButtonText: "Cancelar"
              }).then((result) => {
                if (result.isConfirmed) {
                    $("#pdfShow").attr("data","data:application/pdf;base64,"+res.data)
                    $(".btnModal").click();
                }
              });            
        }
    });    
})

$(document).on("click",'.btnAgregarTurno', function() {
    id_viaje= $(this).attr("data-attr");
    $(".btnModalAsigViaje").click();
});

//Nuevo Turno
$(document).on("click",".btnNuevoTurno", function() {
    let id_vehiculo_operador = $(this).attr('data-attr');
    if(id_vehiculo_operador != 0) {
        $.post(window.routes.agregarNuevoTurno, {
            id_vehiculo_operador: id_vehiculo_operador
        }, function(res) {
            if(res.ok) {
                actualizarTurnos();
                Swal.fire({
                    title: "Buen trabajo!",
                    text: "Turno agregado a la lista",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $(".btnModalClose").click();
                    }
                });                
            }else {
                Swal.fire({
                    title: "Aviso!",
                    text: res.message,
                    icon: "warning",
                    showConfirmButton: false,
                    timer: 2000
                })
            }
        });
    }else {
        Swal.fire({
            title: "Aviso!",
            text: "Primero seleccione un operador",
            icon: "warning",
            showConfirmButton: false,
            timer: 1500
        })
    } 
});

//Asignar Operador a Viaje
$(document).on("click",".btnAsignarOperador", function() {
    id_viaje = $(this).attr("data-attr");
    $.post(window.routes.asignarOperadorAViaje, {id_viaje: id_viaje}, (res) => {
        if(res.ok) {
            Swal.fire({
                title: "Buen trabajo!",
                text: res.data,
                icon: "success",
                showConfirmButton: false,
                timer: 1500
            }).then((result) => {
                /* Read more about handling dismissals below */
                if (result.dismiss === Swal.DismissReason.timer) {
                    location.reload();
                }
            });
        }else {
            Swal.fire({
                title: "Aviso!",
                text: res.message,
                icon: "warning",
                showConfirmButton: false,
                timer: 2000
            });
        }
    });
})

function actualizaViajes() {
    Echo.private('client.1')
    .listen('UpdateClient', (res) => {
        console.log(res);
        return;
        let html =`
        <tr>
            <td>{{ $reservacion->folio }}</td>`;
            if(user.permisos.perfil.include(["Cajera","Administrador"])) {
                html+= `<td>
                            {{ strtoupper($reservacion->nombre) }}
                            <br>
                            {{ $reservacion->telefono }}
                        </td>`;
            }
            html+= `<td>
                        1. {{ $reservacion->origen }} 
                        <br>
                        2. {{ $reservacion->destino }}
                    </td>`;
            if(isset(res.status)) {
                if(res.status == "Pending") {
                    html += `<td class="text-center"><span class="badge rounded-pill bg-primary">Sin asginación</span></td>`;
                }
                if(res.status == "En servicio") {
                    html += `<td class="text-center">
                                <span class="badge rounded-pill bg-success">Asignado</span>
                                <br>
                                {{ $reservacion->nombres }} {{ $reservacion->apellidos }}
                            </td>`;
                }
            }
            if(user.permisos.perfil.include(["Cajera","Administrador"])) {
                const precio_formateado = res.precio.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2,
                });
                html += `<td class="text-center cp" title="${ res.tipo_pago }">${ precio_formateado }</td>`;
            }
            html += `<td>{{ date('d-m-Y H:m',strtotime($reservacion->date_creacion)) }}</td>`;
            if(user.permisos.perfil.include(["Cajera","Administrador"])) {
                html += `<td>`;
                    if(typeof res.status !== 'undefined' && res.status !== null) {
                        if(res.status == "Pending") {
                            html += `<button class="btn btn-sm btn-info text-white btnTicket" data-attr="{{ $reservacion->id_viaje }}" disabled="true">
                                        <i class="fa fa-print" aria-hidden="true" title="Generar Ticket"></i>
                                    </button>
                                    <button class="btn btn-sm btn-secondary text-white btnAsignarOperador" data-attr="{{ $reservacion->id_viaje }}"  title="Asignar Operador">
                                        <i class="fa fa-check-square" aria-hidden="true"></i>
                                    </button>`;
                        } else {
                            html += `<button class="btn btn-sm btn-info text-white btnTicket" data-attr="{{ $reservacion->id_viaje }}">
                                <i class="fa fa-print" aria-hidden="true" title="Generar Ticket"></i>
                            </button>`;
                        }
                    } else {
                        html+= `<button class="btn btn-sm btn-info text-white btnTicket" data-attr="{{ $reservacion->id_viaje }}">
                                    <i class="fa fa-print" aria-hidden="true" title="Generar Ticket"></i>
                                </button>`;
                    }
                    
                html += `</td>`;
            }
        html += `</tr>`;
        $("#viajes").append(html);
    });

}

function actualizarTurnos() {
    Echo.channel('turnos-event')
    .listen('ActualizarTurno', (res) => {
        let html ="";
        res = res.turnos;
        if(res.ok && res.data.length > 0) {
            console.log(res);
            res.data.forEach((element, index) => {
                let nombre = (element.nombres+" "+element.apellidos).substring(0,25)
                html+= `
                    <li class="list-group-item row px-0 mx-0 d-flex">
                        <div class="col-3 px-0 border-orden">${ index+1 }</div>
                        <div class="col-9 d-flex flex-column">
                            <p class="py-0 my-0 lstTitulo">${ nombre }</p>
                            <small class="lstTurnoSmall text-danger">${ element.vehiculo+"-"+element.marca+" ("+element.modelo+")" }</small>
                        </div>
                    </li>
                `;
            });
            $(".lstTurnos").html(html);
        }
    });
}
