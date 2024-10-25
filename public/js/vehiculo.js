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
    resetearFormularioVehiculos();
    $("#id_vehiculo").val(0);
    $(".modal-title").text("Agregar nuevo Vehiculo");
    $(".btnModal").click(); 
});

//Editar Vehiculo
$(document).on("click", ".editVehiculo", function() {
    let id = $(this).attr("data-attr");
    $(".modal-title").text("Editar Vehiculo");
    $.post(window.routes.getVehiculoId,{ id: id }, (res) => {
        if(res.ok) {
            $(".btnModal").click();
            for(var key in res.data) {
                if($("#"+key).length) {
                    $("#"+key).val(res.data[key]);
                }
            };
            return;
        }
        Swal.fire({
            title: "Ha ocurrido un error!",
            text: res.message,
            icon: "error",
            showConfirmButton: false,
            timer: 3000
        });
    });
    

});

//Guardar Vehiculo
$(document).on("click", "#btnSave", function() {
    botonGuardarAsync();
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
                if(res.ok) {
                    resetearFormularioVehiculos();
                    botonGuardarAsync(1);
                    $(".btnModalClose").click();
                    Swal.fire({
                        title: "Buen trabajo!",
                        text: res.data,
                        icon: "success",
                        showConfirmButton: false,
                        timer: 2000
                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            location.reload();
                        }
                    });
                    return;
                }
                botonGuardarAsync(1);
                alerta(res.message,'alert-danger');
            },
            error: (xhr) => {
                botonGuardarAsync(1);
                alerta(xhr.responseJSON.message,'alert-danger');
            }
        });
    }
    botonGuardarAsync(1);
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

//Funciones genericas
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
    $("#form-vehiculo").removeClass('was-validated');
    $("#notas").val("")
}

function botonGuardarAsync(tipo = 0) {
    if(tipo == 1) {
        $(".spiner-loading").addClass("d-none");
        $(".fa-floppy-o").removeClass("d-none");
        $(".loading-text").val("Guardar");
        $("#btnSave").removeClass("btn-disabled");
        $("#btnSave").removeAttr("disabled");
        return;
    }
    $(".spiner-loading").removeClass("d-none");
    $(".fa-floppy-o").addClass("d-none");
    $(".loading-text").val("Guardando...");
    $("#btnSave").addClass("btn-disabled");
    $("#btnSave").attr("disabled",true);
}