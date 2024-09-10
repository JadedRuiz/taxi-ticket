import $ from 'jquery';

$(window).on('load',function() {
    obtenerDestinos();
    obtenerOrigenId();

    //Validation
    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    const forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
        
        }, false)
    })
});
//Abrir Buscador
$(document).on('click','#inpDir',function() {
    let inp = $(this);
    $(inp).parent().addClass('subirInput');
    $(".buscador-pantalla").show();
    $(".list-default").show();
    $("#inpDir").val("");
    setTimeout(function(){
        $(inp).parent().removeClass('subirInput');
        $(inp).parent().addClass("inputAnimation");
    },700)
});

//Filtrar busqueda
$(document).on('keydown','#inpDir',function() {
    let text = $(this).val().toLowerCase();
    //Filtramos los resultados
    let elements = $(".list-group-item").find('p');
    if(text.length > 1) {
        $(elements).each((index, element) => {
            if($(element).html().toLowerCase().includes(text)) {
                $(element).parent().show();
            }else {
                $(element).parent().hide();
            }
        });
    }else {
        $(elements).each((index, element) => {
            $(element).parent().show();
        });
    }    
});

//Seleccionar un destino
$(document).on('click','.list-group-item',function() {
    $("#inpDir").val($(this).find('p').html().toUpperCase());
    $(".buscador-pantalla").hide();
    $(".list-default").hide();
    $("#inpDir").parent().removeClass('inputAnimation');
    obtenerDestinoId($(this).attr("attr-data"));
});

//Siguiente Pantalla 1
$(document).on('click','#btnSiguiente1',function() {
    if($('#inpDir').val() == ""){
        return;
    }   
    $("#inicio").addClass('d-none');
    $("#ico1").removeClass('span-active');
    $("#ico2").addClass('span-active');
    $("#reserva").removeClass('d-none');
});

//Siguiente Pantalla 2
$(document).on('click','#btnSiguiente2',function() {
    $("#reserva").addClass('d-none');
    $("#ico2").removeClass('span-active');
    $("#ico3").addClass('span-active');
    $("#formulario").removeClass('d-none');
})

//Reservar
$(document).on('click','#btnReservar',function(event) {
    // document.getElementById("btnEnviar").click();
    let form = document.querySelector('.needs-validation');
    if (!form.checkValidity()) {
        event.preventDefault()
        event.stopPropagation()
    }else{
        event.preventDefault();
        reservar();
    }

    form.classList.add('was-validated')
})

//Cerrar Buscador
$(document).on('click','.btnClose',function() {
    $(".buscador-pantalla").hide();
    $(".list-default").hide();
    $("#inpDir").parent().removeClass('inputAnimation');
})

//Metodos API
function obtenerOrigenId() {
    $.get('viaje/obtenerOrigen/'+data_origen, (res) => {
        if(res.ok) {
            $("#iIdOrigen").val(data_origen);
            $(".sOrigen").html(res.data.sOrigen);
        }
    })
}

function obtenerDestinos() {
    $.get("viaje/obtenerDestinos",(res) => {
        if(res.ok) {
            let html= '';
            res.data.forEach(element => {
                html+= `
                   <li class="list-group-item" attr-data="${element.iIdDestino}">
                            <p class="my-0 py-0">${element.sNombre}</p>
                            <small>${element.sDireccion}</small>
                    </li> 
                `;
            });
            $(".list-default").html(html);
        }
    })
}

function obtenerDestinoId(id) {
    $.get("viaje/obtenerDestinoId/"+id,(res) => {
        if(res.ok) {
            $("#iIdDestino").val(id);
            $(".sDestino").html(res.data.sDestino);
            $(".sPrecio").html(res.data.sPrecio);
        }
    })
}

function reservar() {
    let json= {
        // _token: "{{ csrf_token() }}",
        iIdOrigen: $("#iIdOrigen").val(),
        iIdDestino: $("#iIdDestino").val(),
        sNombre: $("#sNombre").val(),
        sTelefono: $("#sTelefono").val(),
        sCorreo: $("#sCorreo").val()
    }
    $("#btnReservar").prop("disabled",true);
    $("#btnReservar").addClass("disabled-aunt");
    $(".spinner-border").show();
    $(".text-btn").html("&nbsp; Reservando..")
    $.post("viaje/reservar",json, function(res) {
        if(res.ok) {
            
        }
    })
}