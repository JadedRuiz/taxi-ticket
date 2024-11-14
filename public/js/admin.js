import DataTable from 'datatables.net-dt';
import $ from 'jquery';
import Swal from 'sweetalert2';

var id_viaje=0;
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
    .listen('UpdateClient', (e) => {
        console.log(e);
    });
}

function actualizarTurnos() {
    // console.log("entro");
    Echo.channel('turnos-event')
    .listen('ActualizarTurno', (e) => {
        console.log(e);
    });
    // let html ="";
    // if(res.ok && res.data.length > 0) {
    //     res.data.forEach((element, index) => {
    //         let nombre = (element.nombres+" "+element.apellidos).substring(0,25)
    //         html+= `
    //             <li class="list-group-item row px-0 mx-0 d-flex">
    //                 <div class="col-3 px-0 border-orden">${ index+1 }</div>
    //                 <div class="col-9 d-flex flex-column">
    //                     <p class="py-0 my-0 lstTitulo">${ nombre }</p>
    //                     <small class="lstTurnoSmall text-danger">${ element.vehiculo+"-"+element.marca+" ("+element.modelo+")" }</small>
    //                 </div>
    //             </li>
    //         `;
    //     });
    //     $(".lstTurnos").html(html);
    // }
}
