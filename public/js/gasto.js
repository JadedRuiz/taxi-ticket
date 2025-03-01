import DataTable from 'datatables.net-dt';
import 'jquery-ui/themes/base/all.css';
import Swal from 'sweetalert2';

//Variables globales
let table=null;
let id_gasto=0;
// Mascara de Input Money
document.addEventListener('DOMContentLoaded', function () {
    const input = document.getElementById('importe'); // Reemplaza con el ID de tu input

    input.addEventListener('input', function () {
        // Obtén el valor del input, eliminando todo excepto números
        let value = this.value.replace(/[^0-9]/g, '');

        // Convierte el número a formato de moneda con espacio después del símbolo
        value = new Intl.NumberFormat('es-MX', {
            style: 'currency',
            currency: 'MXN',
            minimumFractionDigits: 2,
        }).format(value / 100); // Dividir por 100 para obtener decimales

        // Actualiza el valor del input con el formato
        this.value = value.replace('$', '$ ');
    });
});

$(window).on("load", function() {
    obtenerConceptos();
    table = inicarTabla();
    //Seleccionar tab
    $(document).on("click","#tab-gastos",function() {
        if(!$(this).hasClass("active")) {
            $("#tab-reporte").removeClass("active");
            $(this).addClass("active");
            $(".Gastos").removeClass("d-none");
            $(".Reportes").addClass("d-none");
            $("#cleanSearch").click();
            $("#nuevo_gasto").click();
        }
    });
    $(document).on("click","#tab-reporte",function() {
        if(!$(this).hasClass("active")) {
            $("#tab-gastos").removeClass("active");
            $(this).addClass("active");
            $(".Reportes").removeClass("d-none");
            $(".Gastos").addClass("d-none");
        }
    });
    //Seleccionar Status
    $(document).on("click",".status-item", function() {
        $(".status-item").each((index, element) => {
            $(element).removeClass("active");
        });
        $(this).addClass("active");
    });
    //Nuevo Gasto
    $(document).on("click",'#nuevo_gasto', function () {
        obtenerUltimoFolio();
        $('#form_gastos')[0].reset();  // Resetea el formulario utilizando jQuery
        $('#nuevo_gasto').addClass("d-none");
        $("#reporte_gasto").addClass("d-none");
        $("#btnEdit").addClass("d-none");
        $("#btnSave").removeClass("d-none");
    });

    //Guardar Gasto
    $(document).on("click","#btnSave", function() {
        botonGuardarAsync(0);
        var json= {};
        json["status"] = $(".status-item.active").text().toUpperCase();
        $('#form_gastos').find('input, select, textarea').each(function() {
            var nombre = $(this).attr('name'); // Obtener el atributo name del campo
            json[nombre] = $(this).val(); // Guardar en el objeto con el nombre como clave
        });
        $.post(window.routes.agregarGasto,json, function(data) {
            if(data.ok) {
                botonGuardarAsync(1);
                agregarRegistroTabla(json);
                Swal.fire("Buen trabajo!",data.data,"success");
                $('#nuevo_gasto').removeClass("d-none");
                $("#reporte_gasto").removeClass("d-none");
                $("#btnSave").addClass("d-none");
                id_gasto = data.id;
            } else {
                alerta(data.message,"alert-warning");
                botonGuardarAsync(1);
            }
        });
    });
    //Seleccionar Gasto
    $(document).on("click",".tr-active", function() {
        if($("#tab-gastos").hasClass("active")) {
            Swal.fire({
                title: "Confirmación",
                text: "Al seleccionar un Gasto reemplazarás la información capturada, estas seguro?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Si!",
                cancelButtonText: "No, cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    id_gasto = $(this).data("attr");
                    $.post(window.routes.obtenerGastoId, { id_gasto: id_gasto }, function(data) {
                        if(data.ok) {
                            $('#nuevo_gasto').removeClass("d-none");
                            $("#reporte_gasto").removeClass("d-none");
                            $("#btnEdit").removeClass("d-none");
                            $("#btnSave").addClass("d-none");
                            $("#tab-gastos").click();
                            //Seleccionar status
                            $(".status-item").each((index, element) => {
                                $(element).removeClass("active");
                                if($(element).text().toUpperCase() == data.data.status) {
                                    $(element).addClass("active");
                                }
                            });
                            //Reemplazar info
                            $('#form_gastos').find('input, select, textarea').each(function() {
                                var nombre = $(this).attr('name'); // Obtener el atributo name del campo
                                switch(nombre) {
                                    case "folio": $(this).val(data.data.folio); break;
                                    case "concepto_gasto": $(this).val(data.data.concepto_gasto); break;
                                    case "fecha": $(this).val(data.data.fecha); break;
                                    case "importe": $(this).val(data.data.importe); break;
                                    case "tipo_comprobante": $(this).val(data.data.tipo_comprobante); break;
                                    case "folio_comprobante": $(this).val(data.data.folio_comprobante); break;
                                    case "descripcion": $(this).val(data.data.descripcion); break;
                                    case "admin_gastos_id": $(this).val(data.data.admin_gastos_id); break;
                                    case "recibe_efectivo": $(this).val(data.data.recibe_efectivo); break;
                                    default: $(this).val(""); break;
                                }
                            });
                            return;
                        }
                        alerta(data.message,"alert-warning");
                    });
                }
            });
        }
    });
    //Actualizar Gasto
    $(document).on("click","#btnEdit", function() {
        botonGuardarAsync(3);
        var json= {};
        json["id_gasto"] = id_gasto; 
        json["status"] = $(".status-item.active").text().toUpperCase();
        $('#form_gastos').find('input, select, textarea').each(function() {
            var nombre = $(this).attr('name'); // Obtener el atributo name del campo
            json[nombre] = $(this).val(); // Guardar en el objeto con el nombre como clave
        });
        $.post(window.routes.actualizarGasto,json, function(data) {
            if(data.ok) {
                botonGuardarAsync(2);
                actualizarRegistroTabla(json);
                Swal.fire("Buen trabajo!",data.data,"success");
                $('#nuevo_gasto').removeClass("d-none");
                $("#reporte_gasto").removeClass("d-none");
                $("#btnSave").addClass("d-none");
            } else {
                alerta(data.message,"alert-warning");
                botonGuardarAsync(2);
            }
        });
    });
    //Generar Reporte Ficha Gasto
    $(document).on("click","#reporte_gasto",function() {
        $.post(window.routes.generarFichaGasto, { id_gasto : id_gasto }, function(data) {
            if(data.ok) {
                var base64PDF = 'data:application/pdf;base64,'+data.data;
                // Crea un enlace de descarga
                var fechaSolo = new Date().toLocaleDateString();
                var enlace = document.createElement('a');
                enlace.href = base64PDF;
                enlace.download = fechaSolo+'-Ficha_gasto.pdf'; // El nombre que tendrá el archivo descargado

                // Simula el clic para iniciar la descarga
                enlace.click();
            }else {
                alerta(data.message,"alert-warning");
            }
        });
    });
    //Buscar información
    $(document).on("click","#btnSearch",function() {
        var json = {};
        botonGuardarAsyncDos(0);
        $('#form-reportes').find('input, select').each(function() {
            var nombre = $(this).attr('name'); // Obtener el atributo name del campo
            json[nombre] = $(this).val(); // Guardar en el objeto con el nombre como clave
        });
        $.post(window.routes.buscarFiltros, json, function(data) {
            if(data.ok) {
                actualizarRegistrosTabla(data.data);
                botonGuardarAsyncDos(1);
                return;
            }
            botonGuardarAsyncDos(1);
            alerta(data.message,"alert-danger");
        })
    })
    //Generar Reporte
    $(document).on("click","#btnReport",function() {
        var json = {};
        json["bInfoDeta"] = $("#bInfoDeta").prop('checked');
        botonGuardarAsyncDos(3);
        $('#form-reportes').find('input, select').each(function() {
            var nombre = $(this).attr('name'); // Obtener el atributo name del campo
            json[nombre] = $(this).val(); // Guardar en el objeto con el nombre como clave
        });
        $.post(window.routes.generarReporte, json, function(data) {
            if(data.ok) {
                botonGuardarAsyncDos(2);
                var base64PDF = 'data:application/pdf;base64,'+data.data;
                // Crea un enlace de descarga
                var fechaSolo = new Date().toLocaleDateString();
                var enlace = document.createElement('a');
                enlace.href = base64PDF;
                if(json.bInfoDeta) {
                    enlace.download = fechaSolo+'-ReporteDetallado.pdf'; // El nombre que tendrá el archivo descargado
                }else {
                    enlace.download = fechaSolo+'-Reporte.pdf'; // El nombre que tendrá el archivo descargado
                }

                // Simula el clic para iniciar la descarga
                enlace.click();
            }else {
                botonGuardarAsyncDos(2);
                alerta(data.message,"alert-warning");
            }
        })
    })
    //Limpiar Busqueda
    $(document).on("click","#cleanSearch", function() {
        $('#form-reportes')[0].reset();
        $("#btnSearch").click();
    })
});

function obtenerUltimoFolio() {
    $.get(window.routes.obtenerUltimoFolio, function(data) {
        $("#folio").val(data.folio);
    })
}

function obtenerConceptos() {
    $('.concepto_auto').autocomplete({
        source: function (request, response) {
            // Realiza una solicitud GET pasando el término buscado
            $.get(window.routes.obtenerConceptos, { term: request.term }, function (data) {
                if (data.ok) {
                    // Filtra los resultados según el término ingresado
                    const resultadosFiltrados = data.data.filter(item =>
                        item.toLowerCase().includes(request.term.toLowerCase())
                    ).slice(0, 8);
                    response(resultadosFiltrados);
                } else {
                    response([data.message]);
                }
            });
        },
        minLength: 0, // Permite buscar incluso con 0 caracteres
    }).focus(function () {
        // Muestra todos los resultados al hacer clic
        $(this).autocomplete('search', '');
    });
}

function agregarRegistroTabla(json) {
    table.destroy();
    $("#datatable tbody").prepend(`
        <tr class="tr-active" data-attr="${id_gasto}">
            <td class="text-start">${$("#folio").val()}</td>
            <td class="text-start">${json.concepto_gasto}</td>
            <td class="text-center">${json.importe}</td>
            <td class="text-center">${json.status}</td>
            <td class="text-center">${json.fecha}</td>
        </tr>  
    `);
    //Inicializamos un nuevo datatable
    table = inicarTabla();
}

function actualizarRegistroTabla(json) {
    table.destroy();
    $(".tr-active").each((index, element) => {
        if($(element).data("attr") == json.id_gasto ){
            $(element).find(".gasto-row").text(json.concepto_gasto);
            $(element).find(".importe-row").text(json.importe);
            $(element).find(".status-row").text(json.status);
            $(element).find(".fecha-row").text(json.fecha);
        }
    });
    table = inicarTabla();
}

function actualizarRegistrosTabla(data) {
    table.destroy();
    $("#datatable tbody").html(data);
    //Inicializamos un nuevo datatable
    table = inicarTabla();
}

function inicarTabla() {
    return new DataTable('#datatable', {
        ordering: false,
        lengthMenu: [[8,15,25,50,-1],["8","15","25","50","Todos"]],
        scrollY: '500px',
        scrollCollapse: true,
        pageLength: 8,
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
}

//Funciones genericas
function alerta(message, classname) {
    $("#alert-form").html(message);
    $("#alert-form").addClass(classname);
    $("#alert-form").show("d-none");
    setTimeout(()=> {
        $("#alert-form").hide("d-none");
    },5000);
}

function botonGuardarAsync(tipo = 0) {
    if(tipo == 1) {
        $(".spiner-loading").addClass("d-none");
        $(".fa-floppy-o").removeClass("d-none");
        $(".loading-text").val("Guardar");
        $("#btnSave").removeClass("btn-disabled");
        $("#btnSave").removeClass("disabled");
    }
    if(tipo == 0) {
        $(".spiner-loading").removeClass("d-none");
        $(".fa-floppy-o").addClass("d-none");
        $(".loading-text").val("Espere..");
        $("#btnSave").addClass("btn-disabled");
        $("#btnSave").addClass("disabled");
    }
    if(tipo == 2) {
        $(".spiner-loading").addClass("d-none");
        $(".fa-floppy-o").removeClass("d-none");
        $(".loading-text").val("Guardar");
        $("#btnEdit").removeClass("btn-disabled");
        $("#btnEdit").removeClass("disabled");
    }
    if(tipo == 3) {
        $(".spiner-loading").removeClass("d-none");
        $(".fa-floppy-o").addClass("d-none");
        $(".loading-text").val("Espere..");
        $("#btnEdit").addClass("btn-disabled");
        $("#btnEdit").addClass("disabled");
    }
}
function botonGuardarAsyncDos(tipo = 0) {
    if(tipo == 1) {
        $(".spiner-loading.btnSearch").addClass("d-none");
        $(".fa-search ").removeClass("d-none");
        $(".loading-text").val("Buscar");
        $("#btnSearch").removeClass("btn-disabled");
        $("#btnSearch").removeClass("disabled");
    }
    if(tipo == 0) {
        $(".spiner-loading.btnSearch").removeClass("d-none");
        $(".fa-search ").addClass("d-none");
        $(".loading-text").val("Espere..");
        $("#btnSearch").addClass("btn-disabled");
        $("#btnSearch").addClass("disabled");
    }
    if(tipo == 2) {
        $(".spiner-loading.btnReport").addClass("d-none");
        $(".fa-file-pdf-o").removeClass("d-none");
        $(".loading-text").val("Reporte");
        $("#btnReport").removeClass("btn-disabled");
        $("#btnReport").removeClass("disabled");
    }
    if(tipo == 3) {
        $(".spiner-loading.btnReport").removeClass("d-none");
        $(".fa-file-pdf-o").addClass("d-none");
        $(".loading-text").val("Espere..");
        $("#btnReport").addClass("btn-disabled");
        $("#btnReport").addClass("disabled");
    }
}