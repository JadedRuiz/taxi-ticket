import DataTable from 'datatables.net-dt';
import $ from 'jquery';
var id_destino = 0;
$(window).on("load", function() {
    new DataTable('#datatable', {
        order: [[0, "asc"]],
        columnDefs: [
            { targets: [1,2,3,4,5,6], orderable: false}
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

$(document).on("click",".btnEdit", function() {
    if(id_destino != $(this).attr("data-attr"))  {
        resetearFormulario();
        $(".loading-form").removeClass("d-none");
        $(".form-info").addClass("d-none");
        id_destino= $(this).attr("data-attr");
        $(".btnModal").click();  
        $(".modal-title").text("Edición de Destino");
        $.post(window.routes.getDestinoId,{
            id_destino : id_destino
        },(res) => {
            if(res.ok) {
                $(".loading-form").addClass("d-none");
                $(".form-info").removeClass("d-none");
                $("#destino").val(res.data.destino);
                $("#precio").val(res.data.precio);
                res.data.duracion == null ? $("#duracion").attr('placeholder','00:00') : $("#duracion").val(res.data.duracion);
                res.data.distancia == null ? $("#distancia").attr('placeholder','0') : $("#distancia").val(res.data.distancia);
                res.data.colonia == null ? $("#colonia").attr('placeholder','Sin asignar') : $("#colonia").val(res.data.colonia);
                res.data.calle == null ? $("#calle").attr('placeholder','Sin asignar') : $("#calle").val(res.data.calle);
                res.data.ciudad == null ? $("#ciudad").attr('placeholder','Sin asignar') : $("#ciudad").val(res.data.ciudad);
                res.data.colonia == null ? $("#colonia").attr('placeholder','Sin asignar') : $("#distancia").val(res.data.colonia);
                res.data.num_int == null ? $("#noint").attr('placeholder','Sin asignar') : $("#noint").val(res.data.num_int);
                res.data.cp == null ? $("#cp").attr('placeholder','Sin asignar') : $("#cp").val(res.data.cp);
                res.data.num_ext == null ? $("#noext").attr('placeholder','Sin asignar') : $("#noext").val(res.data.num_ext);
            }
        }) 
    }else{
        $(".btnModal").click(); 
    }    
})

$(document).on("click",".btnAdd", function() {
    resetearFormulario();
    $(".modal-title").text("Agregar nuevo Destino");
    $(".btnModal").click(); 
});

$(document).on("click","#btnSave", function(e) { 
    e.preventDefault();
    e.stopPropagation();
    $.post(window.routes.guardarDestino, {
        id_destino : id_destino,
        destino : $("#destino").val().toUpperCase(),
        precio : $("#precio").val(),
        duracion : $("#duracion").val(),
        distancia : $("#distancia").val(),
        colonia : $("#colonia").val().toUpperCase(),
        calle : $("#calle").val().toUpperCase(),
        ciudad : $("#ciudad").val().toUpperCase(),
        no_int : $("#noint").val(),
        no_ext : $("#noext").val(),
        cp : $("#cp").val()
    }, (res) => {
        if(res.ok) {
            alert(res.data);
            $(".btnModalClose").click();
            location.reload();
        }        
    });
});
function resetearFormulario() {
    $(".loading-form").addClass("d-none");
    $(".form-info").removeClass("d-none");
    id_destino=0;
    $("#destino").val("");
    $("#precio").val("");
    $("#duracion").val("");
    $("#distancia").val("");
    $("#colonia").attr("");
    $("#calle").attr("");
    $("#ciudad").attr("");
    $("#noint").attr("");
    $("#noext").attr("");
    $("#cp").attr("");
    $("#colonia").val("");
    $("#calle").val("");
    $("#ciudad").val("");
    $("#noint").val("");
    $("#noext").val("");
    $("#cp").val("");
}