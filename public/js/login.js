import $ from 'jquery';

$(document).on("click","#submitForm", (event) => {
    event.preventDefault();
    clearTimeout();
    if($("#user").val().length == 0){
        showError("*El campo usuario es obligatorio*",5000);
        return;
    }
    if($("#pass").val().length == 0){
        showError("*El campo contraseña es obligatorio*",5000);
        return;
    }

    $.post(window.routes.login , {
        usuario: $("#user").val(),
        pass: $("#pass").val()
    }, (res) => {
        if(res.ok) {
            $("#submitForm").addClass("d-none");
            $("#btn-loading").removeClass("d-none");
            setTimeout(() => {
                $("#btn-loading").find(".spinner-border").addClass("d-none");
                $("#btn-loading").find("i").removeClass("d-none");
            }, 2000);
            location.href = window.routes.home;
            return;
        }
        showError("*"+res.message+"*",5000);
    });
});

function showError(message, duration) {
    $(".alert-dir").html(message);
    $(".alert-dir").removeClass('d-none');
    setTimeout(()=> {
        $(".alert-dir").addClass('d-none');
    },duration);
}