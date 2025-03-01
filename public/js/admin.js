import DataTable from 'datatables.net-dt';
import $ from 'jquery';
import Swal from 'sweetalert2';

var id_viaje=0;
var user=0;
var table = null;
var swalWithBootstrapButtons = null;
$(window).on("load", function() {

    table = inicarTabla();
    swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-danger mx-2"
        },
        buttonsStyling: false
    });

    user = window.user;
    //Escuchamos los enventos del WeebHook
    Echo.channel('insert-event')
    .listen('InsertarViaje', (res) => {
        insertarNuevoViaje(res.viajes);
    });
    //Escuchamos los enventos de las Asignaciones
    Echo.channel('table-event')
    .listen('ActualizarViaje', (res) => {
        actualizarViaje(res.viajes);
    });
    //Escuchamos los enventos del turno
    Echo.channel('turnos-event')
    .listen('ActualizarTurno', (res) => {
        let html = actualizarListaTurnos(res.turnos);
        $(".lstTurnos").html(html);
    });

});

$(document).on("click",".btnTicket", function() {
    let id_viaje= $(this).attr("data-attr");
    $.post({
        url: window.routes.generarTicket,
        data: {
        id_viaje: id_viaje
        },
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Incluye el token CSRF aquí
        },
        success: (res) => {
            if(res.ok) {
                // Aquí utilizamos el atributo src en lugar de data
                let pdfData = "data:application/pdf;base64," + res.data;
                $("#pdfShow").attr("src", pdfData); // Actualizamos el src
                
                $(".btnModal").click(); // Abre el modal
            }
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
        swalWithBootstrapButtons.fire({
            title: "Turno creado",
            text: "Actualizando Ventanillas",
            showConfirmButton: false,
            timer: 30000
        });
        swalWithBootstrapButtons.showLoading();
        $.post({
            url: window.routes.agregarNuevoTurno, 
            data: {
                id_vehiculo_operador: id_vehiculo_operador
            },
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Incluye el token CSRF aquí
            }, 
            success: function(res) {
                if(res.ok) {
                    Swal.close();
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

//Eliminar Turno
$(document).on("click",".btnEliminarTurno", function() {
    let id_turno = $(this).attr('data-attr');
    swalWithBootstrapButtons.fire({
        title: "Turno eliminado",
        text: "Actualizando Ventanillas",
        showConfirmButton: false,
        timer: 30000
    });
    swalWithBootstrapButtons.showLoading();
    $.post({
        url: window.routes.eliminarTurno, 
        data: {
            id_turno: id_turno
        },
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Incluye el token CSRF aquí
        }, 
        success: function(res) {
            if(res.ok) {
                Swal.close();
                Swal.fire({
                    title: "Buen trabajo!",
                    text: "Turno eliminado de la lista",
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
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
        }
    });
});

//Editar Viaje
$(document).on("click",".btnEditar", function() {
    if(id_viaje != $(this).attr("data-attr")) {
        id_viaje = $(this).attr("data-attr");
        $.post(window.routes.obtenerViajeId,{ id_viaje : id_viaje },function(response) {
            if(response.ok) {
                resetearStatus();
                let statu = $(".inpStatus .status").filter((index, statu) => {
                    return $(statu).data("status") == response.data.status;
                });
                statu.addClass('active');
                // Recorrer todos los inputs del formulario
                $('#formViaje input, #formViaje select').each(function() {
                    const id = $(this).attr('id'); // Obtener el ID del input o select
                    if (response.data[id]) { // Verificar si el ID existe en los datos
                        if ($(this).is('select')) {
                            // Si es un select, seleccionar la opción correspondiente
                            $(this).val(response.data[id]).change();
                        } else {
                            // Si es un input, asignar el valor directamente
                            $(this).val(response.data[id]);
                        }
                    }
                });
                $(".btnEditarViaje").click();
                return;
            }        
            Swal.fire("Ha sucedido un problema",response.message,"warning");
        });
    } else {
        $(".btnEditarViaje").click();
    }
})
//Cambiar status
$(document).on("click", ".inpStatus .status", function() {
    let div = $(this);
    if(!div.hasClass("disabled")) {
        $.post(window.routes.cambiarStatus, {
            id_viaje : id_viaje,
            status : div.data("status")
        }, function(response) {
            if(response.ok) {
                resetearStatus();
                div.addClass('active');
                Swal.fire("Buen trabajo!",response.message,"success");
            }
        });        
    }else {
        alert("No se puede cambiar a este status");
    }
})
//Asignar Operador a Viaje
$(document).on("click",".btnAsignarOperador", function() {
    id_viaje = $(this).attr("data-attr");
    Swal.fire({
        title: "¿Has realizado el cobro del servicio?",
        text: "Recuerda cobrar antes de generar el ticket",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, ya lo he realizado",
        cancelButtonText: "No, aún no"
      }).then((result) => {
        if (result.isConfirmed) {
            swalWithBootstrapButtons.fire({
                title: "Operador asignado al viaje",
                text: "Eliminando turno en ventanillas",
                showConfirmButton: false,
                timer: 30000
            });
            swalWithBootstrapButtons.showLoading();
            $.post(window.routes.asignarOperadorAViaje, {id_viaje: id_viaje}, (res) => {
                if(res.ok) {
                    Swal.close();
                    Swal.fire({
                        title: "Buen trabajo!",
                        text: res.data,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
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
        }
      });
    
})

//Abrir Modal Asignacion libre
$(document).on("click",".btnAsignarOperadorAdmin", function() {
    id_viaje = $(this).attr("data-attr");
    $.get(window.routes.obtenerTurnosAsync, (res) => {
        $(".lstTurnosAdmin").html(actualizarListaTurnos(res, 1));
        $(".btnModalAsigViajeAdmin").click();
    })
});

//Abrir Modal Asignacion libre
$(document).on("click",".btnAsignarViajeAdmin", function() {
    let id_vehiculo_operador = $(this).attr("data-attr");
    Swal.fire({
        title: "¿Seguro que desear agregar a este operador al viaje?",
        text: "Lo puedes cambiar en cualquier momento",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, asignar",
        cancelButtonText: "No, mejor no"
    }).then((result) => {
    if (result.isConfirmed) {
        swalWithBootstrapButtons.fire({
            title: "Operador asignado al viaje",
            text: "Eliminando turno en ventanillas",
            showConfirmButton: false,
            timer: 30000
        });
        swalWithBootstrapButtons.showLoading();
        $.post(window.routes.asignarOperadorAViajeAdmin, {id_viaje: id_viaje, id_vehiculo_operador: id_vehiculo_operador}, (res) => {
            if(res.ok) {
                Swal.close();
                // actualizarTablaViajes(user.id_empresa, user.caja_id);
                $(".btnModalAsigViajeAdminClose").click();
                Swal.fire({
                    title: "Buen trabajo!",
                    text: res.data,
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
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
    }
    });
});

//Iniciar Operaciones
$(document).on("click","#iniciarOperacion", function() {
    $.post(window.routes.inicioOperacion, {}, (res) => {
        if(res.ok) {
            Swal.fire({
                title: "Buen trabajo!",
                text: res.data,
                icon: "success",
                showConfirmButton: false,
                timer: 3000
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    $(".buttons-operations").html(`
                        <button class="btn btn-sm btn-info btn-style text-white" id="modalListaResultados">
                            <i class="fa fa-list-alt" aria-hidden="true"></i> &nbsp;
                            Tabla de resultados
                        </button>
                    `);
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

//Abrir modal tabla resultados
$(document).on("click","#modalListaResultados", function() {
    $.post(window.routes.listaResultados, {}, (res) => {
        if(res.ok) {
            Swal.fire({
                title: "Buen trabajo!",
                text: res.data,
                icon: "success",
                showConfirmButton: false,
                timer: 3000
            }).then((result) => {
                if (result.dismiss === Swal.DismissReason.timer) {
                    $(".btnModalCierreOperacion").click();
                    infoCierre(res);
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

//Cerrar Operaciones
$(document).on("click","#cierreOperacion", function() {
    Swal.fire({
        title: "¿Seguro que desesas realizar el cierre de operaciones?",
        text: "Cerrada la operación no podras registrar más viajes en tu turno",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Si, cerrar operaciones",
        cancelButtonText: "No, aún no"
    }).then((result) => {
        if (result.isConfirmed) {
            $.post(window.routes.cierreOperacion, {}, (res) => {
                if(res.ok) {
                    Swal.fire({
                        title: "Buen trabajo!",
                        text: res.data,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 3000
                    }).then((result) => {
                        if (result.dismiss === Swal.DismissReason.timer) {
                            $(".buttons-operations").html(`
                                <button class="btn btn-sm btn-success btn-style" id="iniciarOperacion">
                                    <span class="material-symbols--not-started"></span> &nbsp;
                                    Inicio de operaciones
                                </button>
                            `);
                            table.clear();
                            table.draw();
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
        }
    });
})

function actualizarListaTurnos(res, tipo = 0) {
    let html =`<li class="list-group-item text-center">Aún no hay vehiculos en turno</li>`;
    if(res.ok) {
        if(res.data.length > 0) {
            html = "";
            res.data.forEach((element, index) => {
                if(tipo == 1) {
                    let nombre = (element.nombres+" "+element.apellidos).substring(0,35);
                    html+= `
                        <li class="list-group-item row px-0 mx-0 d-flex">
                            <div class="col-2 px-0 border-orden">${ index+1 }</div>
                            <div class="col-10 d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <p class="py-0 my-0 lstTitulo">${ nombre }</p>
                                    <small class="lstTurnoSmall text-danger">${ element.vehiculo+"-"+element.marca+" ("+element.modelo+")" }</small>
                                </div>
                                <button class="btn btn-sm btn-secondary text-white btnAsignarViajeAdmin" data-attr="${ element.id_vehiculo_operador }"  title="Asignar Operador">
                                    <i class="fa fa-check-square" aria-hidden="true"></i>
                                </button>
                            </div>
                        </li>
                    `;
                }
                if(tipo == 0) {
                    let nombre = (element.nombres+" "+element.apellidos).substring(0,25)
                    if(user.permisos.perfil == "Administrador") {
                        html+= `
                            <li class="list-group-item row px-0 mx-0 d-flex">
                                <div class="col-2 px-0 border-orden">${ index+1 }</div>
                                <div class="col-10 d-flex justify-content-between">
                                    <div class="d-flex flex-column">
                                        <p class="py-0 my-0 lstTitulo">${ nombre }</p>
                                        <small class="lstTurnoSmall text-danger">${ element.vehiculo+"-"+element.marca+" ("+element.modelo+")" }</small>
                                    </div>
                                    <button class="btn btn-sm btn-danger text-white btnEliminarTurno" data-attr="${ element.id_turno }"  title="Eliminar Turno">
                                        <i class="fa fa-trash-o" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </li>
                        `;
                    }else {
                        html+= `
                            <li class="list-group-item row px-0 mx-0 d-flex">
                                <div class="col-3 px-0 border-orden">${ index+1 }</div>
                                <div class="col-9 d-flex flex-column">
                                    <p class="py-0 my-0 lstTitulo">${ nombre }</p>
                                    <small class="lstTurnoSmall text-danger">${ element.vehiculo+"-"+element.marca+" ("+element.modelo+")" }</small>
                                </div>
                            </li>
                        `;
                    }                   
                }                
            });
        }
    }
    return html;
}

function actualizarViaje(data) {
    let viajes = $("#datatable tbody").find('tr');
    let viajePorActualizar = viajes.filter((index, tr) => {
        return $(tr).data("id") == data.id_viaje;
    });
    if (viajePorActualizar.length > 0) {
        table.destroy();
        switch(user.permisos.perfil) {
            case "Administrador":
                viajePorActualizar.html(data.row_admin);
                break;
            case "Cajera":
                viajePorActualizar.html(data.row_caja);
                break;
            case "Operador":
                viajePorActualizar.html(data.row_operador);
                break;
        }
        table = inicarTabla();
    }    
}

function insertarNuevoViaje(viaje) {
    let td_contacto = "";
    let td_pago = "";
    let td_accion = "";
    if(user.permisos.perfil == "Cajera" || user.permisos.perfil == "Administrador") {
        td_contacto = `<td>${ viaje.nombre }<br>${ viaje.telefono }</td>`;
    }
    if(user.permisos.perfil == "Cajera" || user.permisos.perfil == "Administrador") {
        td_pago = `<td class="text-center cp" title="${ viaje.tipo_pago }">${ viaje.precio }</td>`;
    }
    if( user.permisos.perfil == "Cajera") {
        td_accion = `
        <td class="td-acciones">
            <button class="btn btn-sm btn-secondary text-white btnAsignarOperador" data-attr="${ viaje.id_viaje }"  title="Asignar Operador">
                <i class="fa fa-check-square" aria-hidden="true"></i>
            </button>
        </td>`;
    }
    if( user.permisos.perfil == "Administrador") {
        td_accion = `
        <td class="td-acciones">
            <div class="dropdown">
                <button class="btn btn-sm btn-info text-white ${ viaje.status == "Cancelado" ? 'disabled' : '' }" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa fa-bars" aria-hidden="true"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownMenuButton1">
                    <li><a class="dropdown-item cp btnAsignarOperadorAdmin"data-attr="${ viaje.id_viaje }">Asignar viaje</a></li>
                    <li><a class="dropdown-item cp btnTicket ${ viaje.status == "Pending" ? 'disabled' : '' }" data-attr="${ viaje.id_viaje }">Generar ticket</a></li>
                    <li><a class="dropdown-item cp btnEditar" data-attr="${ viaje.id_viaje }">Editar viaje</a></li>
                </ul>
            </div>
        </td>`;
    }
    let tr =  `<tr data-id="${ viaje.id_viaje }">
        <td>${ viaje.folio }</td>
        ${ td_contacto }
        <td>
            1. ${ viaje.origen }
            <br>
            2. ${ viaje.destino }
        </td>
        <td class="text-center td-status"><span class="badge rounded-pill bg-primary">Sin asginación</span></td>
        ${ td_pago }
        <td>${ viaje.date_creacion }</td>
        ${ td_accion }
    </tr>`;
    table.destroy();
    $("#datatable tbody").prepend(tr);
    table = inicarTabla();
}

function inicarTabla() {
    return new DataTable('#datatable', {
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
        },
        initComplete: ()=> {
            $('#loading').addClass('d-none'); // Oculta el mensaje de carga
            $('#table-content').css({
                visibility: 'visible',
                position: 'relative'
            });
        }
    });
}

function infoCierre(res) {
    $(".no_venta").text(res.totales.no_ventas);
    $(".total_cash").text("$ "+res.totales.total_cash.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    $(".total_tarjet").text("$ "+res.totales.total_tarjet.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    $(".total").text("$ "+res.totales.total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 }));
    $(".txtTitleCorte").text("Ciere de "+res.caja);
    let html = "";
    res.ventas.forEach(element => {
        html += `
            <tr>
                <th scope="row">${element.folio}</th>
                <td>${element.tipo_pago}</td>
                <td>${element.precio}</td>
                <td>${element.status}</td>
            </tr>
        `;
    });
    $(".infoVentas").html(html);
}

function resetearStatus() {
    let statu = $(".inpStatus .status").filter((index, statu) => {
        return $(statu).hasClass("active");
    });
    statu.removeClass('active');
}
