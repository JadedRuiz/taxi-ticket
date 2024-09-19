import DataTable from 'datatables.net-dt';
import $ from 'jquery';

$(window).on("load", function() {
    new DataTable('#datatable', {
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
})
$(document).on("click",".btnTicket", function() {
    let id_viaje= $(this).attr("data-attr");
    $.post(window.routes.generarTicket,{
        id_viaje: id_viaje
    }, (res) => {
        if(res.ok) {
            $("#pdfShow").attr("data","data:application/pdf;base64,"+res.data)
            $(".btnModal").click();
        }
    })
    
})
