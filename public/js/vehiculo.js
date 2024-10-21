import DataTable from 'datatables.net-dt';
import $ from 'jquery';


$(window).on("load", function() {
    new DataTable('#datatable', {
        order: [[1, "asc"]],
        columnDefs: [
            { targets: [0,2,3,4,5,6], orderable: false}
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
})

// Nuevo Vehiculo
$(document).on("click",".btnAdd", function() {
    // resetearFormulario();
    $(".modal-title").text("Agregar nuevo Vehiculo");
    $(".btnModal").click(); 
});