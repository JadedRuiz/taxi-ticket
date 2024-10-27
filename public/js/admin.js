import DataTable from 'datatables.net-dt';
import $ from 'jquery';
import Swal from 'sweetalert2';

var id_viaje=0;
$(window).on("load", function() {
    new DataTable('#datatable', {
        order: [[0, ""]],
        columnDefs: [
            { targets: [0,1,2,3,4,5], orderable: false}
        ],
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

    $(document).on("click",".btnTicket", function() {
        let id_viaje= $(this).attr("data-attr");
        $.post(window.routes.generarTicket,{
            id_viaje: id_viaje
        }, (res) => {
            if(res.ok) {
                $("#pdfShow").attr("data","data:application/pdf;base64,"+res.data)
                $(".btnModal").click();
            }
        });    
    })

    $(document).on("click",'.btnAsignar', function() {
        id_viaje= $(this).attr("data-attr");
        $(".btnModalAsigViaje").click();
    });

    //Asignar VehiculoOperador a Viaje
    $(document).on("click",".btnAsignarVehiculo", function() {
        let id_vehiculo_operador = $(this).siblings('.slcOperadores').val();
        if(id_vehiculo_operador != 0) {
            $.post(window.routes.asignarVehiculoOperador, {
                id_viaje: id_viaje,
                id_vehiculo_operador: id_vehiculo_operador
            }, function(res) {
                if(res.ok) {
                    $(".btnModalClose").click();
                    Swal.fire({
                        title: "Buen trabajo!",
                        text: "El Vehiculo & Operador se han asigando con exito al Viaje",
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }
                    });
                    
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
})
