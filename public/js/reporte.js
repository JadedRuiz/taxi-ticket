import 'select2/dist/css/select2.min.css';

import('https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js').then((select2) => {
    
    // Inicializa Select2
    $(window).on("load", function() {
        cargarOpciones();
        $( '#select-columns' ).select2( {
            theme: "bootstrap-5",
            placeholder: $( this ).data( 'placeholder' ),
            closeOnSelect: false,
        } );
        $("#desglose").on("change", function() {
            const desgloseVal = $(this).val();
            $(".caja-filtro").toggleClass("d-none", desgloseVal != 2);
            $(".operador-filtro").toggleClass("d-none", desgloseVal != 3);
        });

        // Evento de cambio de selección
        let isChanging = false;
        $('#select-columns').next().find('.select2-selection__choice').find('.select2-selection__choice__remove').addClass('d-none');
        $('#select-columns').on('change', function() {
            if (!isChanging) {
                let seleccion = $(this).val();
                isChanging = true;
                console.log($(this).val().length);
                if (seleccion[0] == '-1' && ($(this).val().length > 2)) {
                    $(this).val(['-1']).trigger('change'); // Deselecciona todas y selecciona solo '-1'
                    $('#select-columns').next().find('.select2-selection__choice').find('.select2-selection__choice__remove').addClass('d-none');
                } else {
                    // Si no se selecciona "-1", asegura que se deseleccione la opción "-1"
                    $(this).find('option[value="-1"]').prop('selected', false);
                    $(this).trigger('change'); // Desactiva la opción '-1' y dispara el cambio
                }
            }
            isChanging = false;
        });

        $("#aplicarFiltros").on("submit", function(e) {
            e.preventDefault();
            let json = {};
            $(this).find('input, select').each((index, element) => {
                json[$(element).attr("name")] = $(element).val();
            });
            $.post(window.routes.generarConsulta,json,function(res) {
                if(res.ok) {
                    $("#datatable").html(res.data);
                }
            });
        });
    });
});
function cargarOpciones(tipo = 1) {
    let array = [
        "Folio",
        "Contacto",
        "Itinerario",
        "Estatus",
        "Precio",
        "Distancia",
        "Duracion",
        "Fecha Viaje",
        "Vehiculo",
        "Tipo pago",
        "Operador",
        "No. Caja"
    ];
    let html ='';
    for(let i=0; i<array.length; i++) {
        html+= `<option value="${i}">${array[i]}</option>`;
    }
    $('#columns-dipo').html(html);
}