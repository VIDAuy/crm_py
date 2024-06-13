const produccion = true;
const app = produccion ? "crm_py" : "crm_py_test";
const url_app = `http://192.168.1.250:82/${app}/`;
const url_ajax = `${url_app}php/ajax/nivel7/`;


$(document).ready(function () {
    tabla_historial_registros();
});



function tabla_historial_registros() {
    $('#tabla_historial_registros').DataTable({
        ajax: `${url_ajax}historial_registros.php`,
        columns: [
            { data: 'fecha_registro' },
            { data: 'cedula' },
            { data: 'sector' },
            { data: 'socio' },
            { data: 'baja' },
            { data: 'observaciones' },
            { data: 'mas_info' },
        ],
        "bDestroy": true,
        "order": [[0, 'desc']],
        language: { url: `${url_app}assets/js/lenguage.json` },
        dom: 'Bfrtip',
        buttons: ['excel'],
    });
}


function abrir_modal_mas_info(nombre, cedula, telefono, socio, baja, sector, fecha_registro, observacion) {
    /** LLENAR LOS INPUTS CON LOS DATOS */
    $("#text_nombre_registro").val(nombre);
    $("#text_cedula_registro").val(cedula);
    if (telefono == "") {
        $("#text_telefono_registro").val("Sin Registros");
        $("#text_telefono_registro").addClass("text-danger");
    } else {
        $("#text_telefono_registro").val(telefono);
    }
    $("#text_socio_registro").val(socio);
    $("#text_baja_registro").val(baja);
    $("#text_sector_registro").val(sector);
    $("#text_fecha_registro_registro").val(fecha_registro);
    $("#text_observacion_registro").val(observacion);

    /** ABRIR MODAL **/
    $("#modal_verMasInfoRegistros").modal("show");
}