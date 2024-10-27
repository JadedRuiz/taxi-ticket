import $ from 'jquery';
import Swal from 'sweetalert2';

var id_vehiculo=0;

//Abrir modal
$(document).on("click","#btnOperador",function() {
    id_vehiculo = $(this).attr("data-attr");
    let no_operadores= `
        <div class="card col-12 mt-1">
            <div class="card-body text-center">
                <p class="card-text">Aún no se han agregado operadores al catálogo</p>
            </div>
        </div>
    `;
    $.post(window.routes.getVehiculoOperadores, { id: id_vehiculo}, (res) => {
        if(res.ok) {
            if(res.data.length > 0) {
                let html = "";
                res.data.forEach(element => {
                    html += agregarCardOperador(element);
                });
                $(".lstOperadores").html(html);
            } else {
                $(".lstOperadores").html(no_operadores);
            }            
            $(".btnModalOperador").click();
            return;
        }
        Swal.fire({
            title: "Ha ocurrido un error!",
            text: 'No se ha podido recuperar los datos',
            icon: "error",
            showConfirmButton: false,
            timer: 1500
        })
    });
});

//Nuevo operador
$(document).on("click","#btnNewOperador",function() {
    if($("#formOperador").attr("data-show") == "false") {
        $("#formOperador").show();
        $(this).removeClass("btn-success");
        $(this).addClass("btn-danger");
        $(this).find("span").removeClass("flowbite--user-add-solid");
        $(this).find("span").addClass("mdi--close-box");
        $("#formOperador").attr("data-show","true");
        return;
    }
    $("#formOperador").hide();
    $(this).removeClass("btn-danger");
    $(this).addClass("btn-success");
    $(this).find("span").removeClass("mdi--close-box");
    $(this).find("span").addClass("flowbite--user-add-solid");
    $("#formOperador").attr("data-show","false");
});

//Guardar Vehiculo
$(document).on("click", "#btnSaveOpe", function() {
    var form = document.querySelector('#form-operador');
    if (form.checkValidity()) {
        var formdata = new FormData();
        $("#form-operador input").each(function() {
            formdata.append(this.id, this.value);
        });
        formdata.append("image", $('#inpAdjOperador')[0].files[0] ?? '');

        $.ajax({
            url: window.routes.guardarOperador,
            method: 'POST',
            data: formdata,
            contentType: false,
            processData: false,
            success: (res) => {
                Swal.fire({
                    title: "Buen trabajo!",
                    text: 'Datos guardados existosamente',
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                }).then((result) => {
                    /* Read more about handling dismissals below */
                    if (result.dismiss === Swal.DismissReason.timer) {
                        $(".lstOperadores").prepend(agregarCardOperador(res.data));
                        $("#btnNewOperador").click();
                    }
                });
            },
            error: (xhr) => {
                alerta(xhr.responseJSON.message,'alert-danger');
            }
        })
    }
    form.classList.add('was-validated');
});

//Abrir Explorador de archivos
$(document).on("click","#btnAdjOperador", function() {
    $("#inpAdjOperador").click();
});

//Validacion de tamñao de la imagen
$(document).on("change","#inpAdjOperador", function(e) {
    let image_size = $(this)[0].files[0].size / 1024;
    if(image_size > 5000) {
        alerta("La imagen no cumple con el tamaño",'alert-danger');
        $('#inpAdjOperador').val("");
        $("#fotografia_operador").val("");
        return;
    }
    $("#fotografia_operador").val(e.target.files[0].name);
});

//Seleccionar status
$(document).on("click",".status",function() {
    $(".status").each((index,element) => {
        $(element).css({
            border: 'none'
        });
    })
    $(this).css({
        border: '2px black solid'
    });
    $("#status").val($(this).attr("data-status"));
});

//Seleccionar Vehiculo Operador
$(document).on("click",".checkSelectOpe",function() {
    let element = $(this);
    let id = $(this).attr("data-attr");
    $.post(window.routes.asignarOperadorVehiculo, {
        id_operador: id,
        id_vehiculo: id_vehiculo
    }, (res) => {
        if(res.ok) {
            console.log(res);
            return;
        }
        Swal.fire({
            title: "Ha ocurrido un error!",
            text: res.message,
            icon: "error",
            showConfirmButton: false,
            timer: 1500
        }).then((result) => {
            /* Read more about handling dismissals below */
            if (result.dismiss === Swal.DismissReason.timer) {
                element.prop('checked', false);
            }
        });
    });
});
//Template Card Operador
function agregarCardOperador(data) {
    let status = "";
    if(data.status == 1) {
        status = `<span class="badge bg-info d-inline cp status" style="border: 2px black solid" data-status="1">Activo</span>`;
    }
    if(data.status == 2) {
        status = `<span class="badge bg-secondary d-inline cp status" style="border: 2px black solid" data-status="2">Suspendido</span>`;
    }
    if(data.status == 3) {
        status = `<span class="badge bg-warning d-inline cp status" style="border: 2px black solid" data-status="3">Inactivo</span>`;
    }
    let html = `
        <div class="card col-12 mt-1 just">
            <div class="row g-0">
                <div class="col-md-3 cont-img">
                    <img src="${ data.path }" class="rounded-start img-fluid img-defualt">                               
                </div>
                <div class="col-md-9">
                    <div class="card-body-default">
                        <div class="info-ope col-12">
                            <h5 class="card-title">${ data.nombres } ${ data.apellidos }</h5>
                            <p class="card-text">Licencia: ${ data.no_licencia }, Vigencia: ${ data.dtVigencia } </p>
                            <p class="card-text">
                                <i class="fa fa-phone-square" aria-hidden="true"></i> &nbsp; ${ data.telefono },
                                <i class="fa fa-envelope" aria-hidden="true"></i> &nbsp; ${ (data.correo+'').length > 24 ? (data.correo+'').substring(0,23)+'..' : data.correo }
                            </p>
                        </div>
                        <div class="footer-card col-12">
                            <small class="text-muted d-flex align-items-center">${ status }</small>
                            <div class="buttons">
                                <button class="btn btn-sm btn-warning text-white" style="padding-bottom: 0px;" data-attr="${ data.id_operador }" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Operador">
                                    <span class="ic--baseline-edit"></span>
                                </button>
                                <div class="form-check form-switch my-0" data-bs-toggle="tooltip" data-bs-placement="top" title="Selcciona/Deselecciona">
                                    <input class="form-check-input cp checkSelectOpe" type="checkbox" id="checkOpe${ data.id_operador }" data-attr="${ data.id_operador }"
                                    ${ data.seleccionado != null && data.seleccionado != 0 ? 'checked' : '' }
                                    >
                                    <label class="form-check-label cp" for="checkOpe${ data.id_operador }">${ data.seleccionado == null ? 'Seleccionar' : 'Seleccionado' }</label>
                                </div>
                            </div>
                        </div>                        
                    </div>
                </div>
            </div>
        </div>`;
    return html;
}

//Mostar alerta form
function alerta(message, classname) {
    $(".btnGuardar").addClass('d-none');
    $("#alert-form-ope").html(message);
    $("#alert-form-ope").addClass(classname);
    $("#alert-form-ope").show();
    setTimeout(()=> {
        $(".btnGuardar").removeClass('d-none');
        $("#alert-form-ope").hide();
    },5000);
}