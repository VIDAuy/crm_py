function buscarSocio() {
    let cedula = $("#txt_cedula_buscar").val();

    $.ajax({
        url: `${url_ajax}cargar_datos_socios.php`,
        type: "GET",
        dataType: "JSON",
        data: {
            CI: cedula
        },
        beforeSend: function () {
            ocultar_contenido_socio();
        },
    }).done(function (datos) {
        $("#span_cedula_socio").text(cedula);
        select_avisar_a();
        historiaComunicacionDeCedula();
        if (datos.noSocioConRegistros) {
            alerta("Problema!", datos.mensaje, "warning");
            $("#span_cedula_no_socio_registros").text($("#txt_cedula_buscar").val());
            $("#txt_nombre_no_socio_registros").val(datos.nombre);
            $("#txt_telefono_no_socio_registros").val(datos.telefono);
            $("#contenedor_no_es_socio_registros").css({ display: "block" });
            $("#contenedor_registros_del_socio").css("display", "block");
        } else if (datos.noSocio) {
            alerta("<span style='color: #9C0404'>¿Está seguro de que la cédula pertenece un socio?</span>", datos.mensaje, "error");
            $("#span_cedula_no_es_socio").text($("#txt_cedula_buscar").val());
            $("#contenedor_no_es_socio").css({ display: "block" });
        } else if (datos.bajaProcesada) {
            alerta("Problema!", datos.mensaje, "warning");
            $("#span_cedula_no_socio_registros").text($("#txt_cedula_buscar").val());
            $("#txt_nombre_no_socio_registros").val(datos.nombre);
            $("#txt_telefono_no_socio_registros").val(datos.telefono);
            $("#contenedor_no_es_socio_registros").css({ display: "block" });
            $("#contenedor_registros_del_socio").css("display", "block");
        } else {
            $("#span_nombre").text(datos.nombre);
            $("#span_telefono").text(datos.tel);
            $("#span_fecha_afiliacion").text(datos.fecha_afiliacion);
            $("#span_radio").text(datos.radio);
            $("#span_sucursal").text(datos.sucursal);
            $("#span_inspira").html(datos.inspira);
            $("#contenedor_si_es_socio").css({ display: "block" });
            if (!datos.mostrar_inspira) $("#div_inspira").css("display", "none");
            $("#contenedor_registros_del_socio").css("display", "block");

        }

        $("#contenedor_seccion_mas_datos").css("display", "block");

        ultima_comunicacion_crm(cedula);
        datos_cobranza();
        //datos_coordina();
        datos_productos();
        tabla_servicios_utilizados();

    }).fail(function (err) {
        console.log(err);
        error("Ha ocurrido un error, por favor comuníquese con el administrador");
    });
}


function ultima_comunicacion_crm(cedula) {
    $.ajax({
        type: "GET",
        url: `${url_ajax}ultima_comunicacion_crm.php?cedula=${cedula}`,
        dataType: "JSON",
        success: function (response) {
            if (response.error === false) {
                $("#span_ultima_comunicacion_crm").html(response.mensaje);
            } else {
                $("#span_ultima_comunicacion_crm").html(response.mensaje);
            }
        }
    });
}


function ocultar_contenido_socio() {
    $("#contenedor_registros_del_socio").css("display", "none");
    $("#contenedor_seccion_mas_datos").css("display", "none");

    //noEsSocioRegistro
    $("#span_cedula_no_socio_registros").text("");
    $("#txt_nombre_no_socio_registros").val(null);
    $("#txt_telefono_no_socio_registros").val(null);
    $("#txt_observacion_no_socio_registros").val("");
    $("#txt_avisar_a_no_socio_registros").prop("selectedIndex", 0);
    $("#cargar_imagen_registro_1").val("");
    $("#contenedor_no_es_socio_registros").css({ display: "none" });

    //noEsSocio
    $("#span_cedula_no_es_socio").text("");
    $("#txt_nombre_no_es_socio").val(null);
    $("#txt_apellido_no_es_socio").val(null);
    $("#txt_telefono_no_es_socio").val(null);
    $("#txt_celular_no_es_socio").val(null);
    $("#txt_observacion_no_es_socio").val("");
    $("#txt_avisar_a_no_es_socio").prop("selectedIndex", 0);
    $("#cargar_imagen_registro_2").val("");
    $("#contenedor_no_es_socio").css({ display: "none" });

    //siEsSocio
    $("#span_cedula_socio").val("");
    $("#txt_observacion_si_es_socio").val("");
    $("#txt_avisar_a_si_es_socio").prop("selectedIndex", 0);
    $("#cargar_imagen_registro_3").val("");
    $("#contenedor_si_es_socio").css({ display: "none" });
}