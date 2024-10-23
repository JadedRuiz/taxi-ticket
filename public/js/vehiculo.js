import DataTable from 'datatables.net-dt';
import $ from 'jquery';
import Swal from 'sweetalert2';


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
        var formdata = new FormData();
        $("#form-vehiculo input").each(function() {
            formdata.append(this.id, this.value);
        });
        formdata.append("notas", $("#notas").val());
        formdata.append("image", $('#inpAdj')[0].files[0] ?? '');
        $.ajax({
            url: window.routes.guardarVehiculo,
            method: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: (res) => {
                resetearFormularioVehiculos();
                $(".btnModalClose").click();
                Swal.fire({
                    title: "Buen trabajo!",
                    text: "Datos registrados con exito",
                    icon: "success"
                });
            },
            error: (xhr) => {
                alerta(xhr.responseJSON.message,'alert-danger');
            }
        });
    }
    form.classList.add('was-validated')
})

//Ajuntar fotografia
$(document).on("click","#btnAdj", function() {
    $("#inpAdj").click();
});

//Validacion de tamñao de la imagen
$(document).on("change","#inpAdj", function(e) {
    let image_size = $('#inpAdj')[0].files[0].size / 1024;
    if(image_size > 5000) {
        alerta("La imagen no cumple con el tamaño",'alert-danger');
        $('#inpAdj').val("");
        $("#fotografia").val("");
        return;
    }
    $("#fotografia").val(e.target.files[0].name);
});

//Metodo para mostrar alerta
function alerta(message, classname) {
    $("#alert-form").html(message);
    $("#alert-form").addClass(classname);
    $("#alert-form").show("d-none");
    setTimeout(()=> {
        $("#alert-form").hide("d-none");
    },5000);
}

function resetearFormularioVehiculos() {
    $("#form-vehiculo input").each(function() {
        $(this).val("");
    });
    $("#notas").val("")
}