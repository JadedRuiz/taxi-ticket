import 'select2/dist/css/select2.min.css';
import ExcelJS from 'exceljs';
import { saveAs } from 'file-saver';

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
            $("#generarExcel").addClass("disabled");
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
                    $("#generarExcel").removeClass("disabled");
                    $("#datatable").html(res.data);
                }
            });
        });

        $("#generarExcel").on("click", function() {
            exportarExcel();
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

async function exportarExcel() {
    const workbook = new ExcelJS.Workbook();
    const worksheet = workbook.addWorksheet('Reporte');

    const tabla = $('#datatable');

    // Recorrer filas de la tabla HTML
    tabla.find('tr').each((rowIndex, row) => {
        const $row = $(row); // Convertir fila a objeto jQuery
        const excelRow = worksheet.addRow($row.find('td, th').map((_, cell) => $(cell).text()).get());

        // Aplicar estilos personalizados según el atributo
        if ($row.attr('data-dif') === '1') {
            // Estilo para filas con attr-dif="diferente"
            excelRow.eachCell(cell => {
                cell.font = {
                    name: 'Verdana', // Cambiar font-family
                    size: 12,
                    bold: true,
                    color: { argb: 'FF000000' } // Texto negro
                };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FFFFC0CB' } // Fondo rosado
                };
            });
        } else if (rowIndex === 0) {
            // Estilo para encabezado
            excelRow.eachCell(cell => {
                cell.font = {
                    name: 'Calibri', // Font-family para encabezados
                    size: 14,
                    bold: true,
                    color: { argb: 'FFFFFFFF' } // Texto blanco
                };
                cell.fill = {
                    type: 'pattern',
                    pattern: 'solid',
                    fgColor: { argb: 'FF4CAF50' } // Fondo verde
                };
            });
        }
    });

    // Ajustar ancho automático de las columnas
    worksheet.columns.forEach(column => {
        let maxLength = 0;
        column.eachCell({ includeEmpty: true }, cell => {
            maxLength = Math.max(maxLength, cell.value ? cell.value.toString().length : 0);
        });
        column.width = maxLength + 2;
    });

    // Exportar archivo
    const buffer = await workbook.xlsx.writeBuffer();
    saveAs(new Blob([buffer]), 'reporte-myride.xlsx');
}