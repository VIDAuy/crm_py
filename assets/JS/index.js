$(document).ready(function () {
    alertar_gestion_bajas();
    setInterval(alertar_gestion_bajas, 30000);

    contar_alertas();
    setInterval(contar_alertas, 30000);
});



function alertar_gestion_bajas() {
    let usuario = $("#usuario_logueado_py").val();
    if (["Calidaduy", "1707544"].includes(usuario)) {

        $.ajax({
            type: "GET",
            url: `${url_ajax}alertas/cantidad_gestion_bajas_pendientes.php?usuario=${usuario}`,
            dataType: "JSON",
            success: function (response) {
                if (response.error === false) {
                    $('#cantidad_gestion_bajas_pendientes').html(`${response.cantidad}+`);
                }
            }
        });

    }
}


function contar_alertas() {
    let nivel = $("#nivel_py").val();
    $("#span_cantidad_alertas_pendientes").text(`0+`);

    $.ajax({
        data: {
            nivel,
        },
        url: `${url_ajax}alertas/cantidad_alertas_pendientes.php`,
        type: "POST",
        dataType: "JSON",
        success: function (response) {
            if (response.error === false) {
                $("#span_cantidad_alertas_pendientes").text(`${response.cantidad}+`);
            } else {
                $("#span_cantidad_alertas_pendientes").text("!");
            }
        },
    });
}


function abrir_modal_alertas_pendientes() {
    if ($('#span_cantidad_alertas_pendientes').text() == "0+") {
        error('No hay ningún mensaje que visualizar.');
    } else {

        $("#tabla_alertas_pendientes").DataTable({
            ajax: `${url_ajax}/alertas/alertas_pendientes.php`,
            columns: [
                { data: "idRegistro" },
                { data: "sector" },
                { data: "cedula" },
                { data: "nombre" },
                { data: "telefono" },
                { data: "acciones" },
            ],
            order: [[0, "desc"]],
            bDestroy: true,
            language: {
                url: `${url_app}assets/js/lenguage.json`,
            },
        });

        $('#modalDatosAlertas').modal('show');

    }
}


function ver_alerta_pendiente(CI, idRegistro) {
    $.ajax({
        type: "POST",
        url: `${url_ajax}/alertas/alertas_pendientes.php`,
        data: {
            CI,
            idRegistro
        },
        dataType: "JSON",
        success: function (response) {
            b = response.message;
            $('#txt_cedula_buscar').val(CI);
            $('#modalDatosAlertas').modal('hide');
            buscarSocio();
            contar_alertas();
        }
    });
}


function select_avisar_a() {
    let nuevaLinea1 = '<option value="sin_seleccion" selected>Seleccione una opción</option>';
    $(nuevaLinea1).appendTo('.agregarFiliales');
    let nuevaLinea2 = '<option value="">No avisar</option>';
    $(nuevaLinea2).appendTo('.agregarFiliales');

    $.ajax({
        url: `${url_ajax}select_avisar_a.php`,
        dataType: "JSON",
        success: function (r) {
            $.each(r.datos, function (i, v) {
                let nuevaLinea = `<option value="${v.id}">${v.usuario}</option>`;
                $(nuevaLinea).appendTo(".agregarFiliales");
            });
        },
    });
}
