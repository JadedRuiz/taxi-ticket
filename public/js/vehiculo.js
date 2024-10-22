import DataTable from 'datatables.net-dt';
import $ from 'jquery';


$(window).on("load", function() {
    new DataTable('#datatable', {
        order: [[1, "asc"]],
        columnDefs: [
            { targets: [0,2,3,4,5], orderable: false}
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
$(document).on("click", ".btnAdd", function() {
    // resetearFormulario();
    $(".modal-title").text("Agregar nuevo Vehiculo");
    $(".btnModal").click(); 
});

//Guardar Vehiculo
$(document).on("click", "#btnSave", function() {
    var form = document.querySelector('.needs-validation');
    if (form.checkValidity()) {
        let json = {};
        $("#form-vehiculo input").each(function() {
            json[this.id] = (this.value+"").toUpperCase();
        });
        json["notas"] = $("#notas").val().toUpperCase();
        $.post(window.routes.guardarVehiculo, json)
        .done((res) => {
            console.log(res);
        })
        .fail((xhr) => {
            alerta(xhr.responseJSON.message);
        });
    }
    form.classList.add('was-validated')
})

//Ajuntar fotografia
$(document).on("click","#btnAdj", function() {
    $("#inpAdj").click();
});

$(document).on("change","#inpAdj", function(e) {
    $("#fotografia").val(e.target.files[0].name);
});

function alerta(message) {
    $("#alert-form").html(message);
    $("#alert-form").show("d-none");
    setTimeout(()=> {
        $("#alert-form").hide("d-none");
    },5000);
}