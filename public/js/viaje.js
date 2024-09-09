import $ from 'jquery';

$(document).on('click','#btnSiguiente1',function() {
    if($('#inpDir').val() == ""){
        return;
    }   
    console.log("entro");
    $("#inicio").addClass('d-none');
    $("#ico1").removeClass('span-active');
    $("#ico2").addClass('span-active');
    $("#reserva").removeClass('d-none');
});

$(document).on('click','#btnSiguiente2',function() {
    $("#reserva").addClass('d-none');
    $("#ico2").removeClass('span-active');
    $("#ico3").addClass('span-active');
    $("#formulario").removeClass('d-none');
})

$(document).on('click','#btnReservar',function() {
    let json= {
        sNombre: "",
        sTelefono: "",
        sCorreo: "",
    }
    $.post("./viaje.create",json, function(res) {
        if(res.ok) {
            alert('se envio el formulario');
        }
    })
})