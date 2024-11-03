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
    limpiarFormulario();
    if($("#formOperador").attr("data-show") != "false") { 
        toggleFormOperador();
    }
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
    limpiarFormulario();
    toggleFormOperador();
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
                if(res.ok) {
                    Swal.fire({
                        title: "Buen trabajo!",
                        text: 'Datos guardados existosamente',
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
                    }).then((result) => {
                        /* Read more about handling dismissals below */
                        if (result.dismiss === Swal.DismissReason.timer) {
                            if($("#id_operador").val() == 0) {
                                $(".lstOperadores").prepend(agregarCardOperador(res.data));
                            }else {
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
                                        toggleFormOperador();
                                        return;
                                    }
                                });
                            }
                        }
                    });
                }
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
        $("#path").val("");
        return;
    }
    $("#path").val(e.target.files[0].name);
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

//Habilitar la edicion del operador
$(document).on("click",".btnEditOperador", function() {
    let id_operador = $(this).attr("data-attr");
    limpiarFormulario();
    $.post(window.routes.getOperadorId,{'id_operador':id_operador}, (res) => {
        if(res.ok) {
            for(var key in res.data) {
                $("#"+key).val(res.data[key]);
                if(key == "status") {
                    $(".status").each((index,element) => {
                        $(element).css({
                            border: 'none'
                        });
                        if($(element).attr("data-status") == res.data[key]) {
                            $(element).css({
                                border: '2px black solid'
                            });
                        }
                    })
                }
            }
            toggleFormOperador();
            return;
        }
        alerta(res.message,'alert-danger');
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
                                <button class="btn btn-sm btn-warning text-white btnEditOperador" style="padding-bottom: 0px;" data-attr="${ data.id_operador }" data-bs-toggle="tooltip" data-bs-placement="top" title="Editar Operador">
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

//Mostrar FormOperador
function toggleFormOperador() {
    if($("#formOperador").attr("data-show") == "false") {
        $("#formOperador").show();
        $("#btnNewOperador").removeClass("btn-success");
        $("#btnNewOperador").addClass("btn-danger");
        $("#btnNewOperador").find("span").removeClass("flowbite--user-add-solid");
        $("#btnNewOperador").find("span").addClass("mdi--close-box");
        $("#formOperador").attr("data-show","true");
        return;
    }
    $("#formOperador").hide();
    $("#btnNewOperador").removeClass("btn-danger");
    $("#btnNewOperador").addClass("btn-success");
    $("#btnNewOperador").find("span").removeClass("mdi--close-box");
    $("#btnNewOperador").find("span").addClass("flowbite--user-add-solid");
    $("#formOperador").attr("data-show","false");
}

//Limpiar Form Operador
function limpiarFormulario() {
    $("#form-operador input").each(function() {
        $(this).val("");
    });
    $("#form-operador").removeClass('was-validated');
    $(".status").each((index,element) => {
        $(element).css({
            border: 'none'
        });
        if($(element).attr("data-status") == 1) {
            $(element).css({
                border: '2px black solid'
            });
        }
    })
}