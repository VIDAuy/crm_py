function cargo(param, socioParam) {

    let nombre = param == 0 ? $("#txt_nombre_no_socio_registros").val() :
        param == 1 ? $("#txt_nombre_no_es_socio").val() + " " + $("#txt_apellido_no_es_socio").val() :
            $("#span_nombre").text();

    let telefono = param == 0 ? $("#txt_telefono_no_socio_registros").val() :
        param == 1 ? $("#txt_telefono_no_es_socio").val() + " " + $("#txt_celular_no_es_socio").val() :
            $("#span_telefono").text();

    let observacion = param == 0 ? $("#txt_observacion_no_socio_registros").val() :
        param == 1 ? $("#txt_observacion_no_es_socio").val() :
            $("#txt_observacion_si_es_socio").val();

    let ensec = param == 0 ? $("#txt_avisar_a_no_socio_registros").val() :
        param == 1 ? $("#txt_avisar_a_no_es_socio").val() :
            $("#txt_avisar_a_si_es_socio").val();

    cedulas = $("#span_cedula_socio").text();
    sector = $("#sector_py").val();

    if (controlCargo(param) != "") {
        error("Ha ocurrido lo siguiente:\n" + controlCargo(param));
    } else if (ensec == "sin_seleccion") {
        error("Debe seleccionar a quien desea avisar");
    } else {

        var form_data = new FormData();
        form_data.append("nombre", nombre);
        form_data.append("telefono", telefono);
        form_data.append("observacion", observacion);
        form_data.append("ensec", ensec);
        form_data.append("cedulas", cedulas);
        form_data.append("sector", sector);
        form_data.append("socio", socioParam);

        if (param == 0) {
            let totalImagenes = $("#cargar_imagen_registro_1").prop("files").length;
            for (let i = 0; i < totalImagenes; i++) {
                form_data.append("imagen[]", $("#cargar_imagen_registro_1").prop("files")[i]);
            }
        } else if (param == 1) {
            let totalImagenes = $("#cargar_imagen_registro_2").prop("files").length;
            for (let i = 0; i < totalImagenes; i++) {
                form_data.append("imagen[]", $("#cargar_imagen_registro_2").prop("files")[i]);
            }
        } else {
            let totalImagenes = $("#cargar_imagen_registro_3").prop("files").length;
            for (let i = 0; i < totalImagenes; i++) {
                form_data.append("imagen[]", $("#cargar_imagen_registro_3").prop("files")[i]);
            }
        }

        $.ajax({
            type: "POST",
            data: form_data,
            url: `${url_ajax}cargar_registro.php`,
            dataType: "JSON",
            contentType: false,
            processData: false,
            beforeSend: function () {
                mostrarLoader();
            },
            complete: function () {
                mostrarLoader("O");
            },
            success: function (response) {
                if (response.error === false) {
                    correcto(response.mensaje);
                    historiaComunicacionDeCedula();
                    //No Es Socio, Tiene Registros
                    $("#txt_observacion_no_socio_registros").val("");
                    $("#txt_avisar_a_no_socio_registros").val("sin_seleccion");
                    $("#cargar_imagen_registro_1").val("");
                    //No Es Socio
                    $("#txt_observacion_no_es_socio").val("");
                    $("#txt_avisar_a_no_es_socio").val("sin_seleccion");
                    $("#cargar_imagen_registro_2").val("");
                    //Es Socio
                    $("#txt_observacion_si_es_socio").val("");
                    $("#txt_avisar_a_si_es_socio").val("sin_seleccion");
                    $("#cargar_imagen_registro_3").val("");
                } else {
                    error(response.mensaje);
                }
            },
        });

    }
}


function controlCargo(param) {
    let mensaje = "";
    if (param == 0) {
        if ($("#txt_observacion_no_socio_registros").val() == "")
            mensaje += "Es necesario que agregue una observación.";
    } else if (param == 1) {
        if ($("#txt_nombre_no_es_socio").val() == "")
            mensaje += 'Es necesario que llene el campo "nombre".\n';
        if ($("#txt_apellido_no_es_socio").val() == "")
            mensaje += 'Es necesario que llene el campo "apellido".\n';
        if ($("#txt_telefono_no_es_socio").val() == "" && $("#txt_celular_no_es_socio").val() == "")
            mensaje += "Es necesario que agregue un teléfono o un celular.\n";
        else {
            if ($("#txt_telefono_no_es_socio").val() != "") {
                if (!/^([0-9])*$/.test($("#txt_telefono_no_es_socio").val()))
                    mensaje += 'El campo "Telefono" sólo puede contener números.\n';
                else if ($("#txt_telefono_no_es_socio").val().length < 6 || $("#txt_telefono_no_es_socio").val().length > 7)
                    mensaje += 'El campo "Teléfono contacto" debe contener entre 6 y 7 dígitos.\n';
                else if (
                    $("#txt_telefono_no_es_socio").val().substring(0, 1) != 2 &&
                    $("#txt_telefono_no_es_socio").val().substring(0, 1) != 4
                )
                    mensaje += 'El telefono ingresado en el campo "Teléfono" es inválido.\n';
            }
            if ($("#txt_celular_no_es_socio").val() != "") {
                if (!/^([0-9])*$/.test($("#txt_celular_no_es_socio").val()))
                    mensaje += 'El campo "Celular" sólo puede contener números.\n';
                else if ($("#txt_celular_no_es_socio").val().length != 10)
                    mensaje += 'El campo "Celular" debe de tener 10 números.\n';
                else if ($("#txt_celular_no_es_socio").val().substring(0, 2) != 09)
                    mensaje +=
                        'El celular ingresado en el campo "Celular" es inválido.\n';
            }
        }
        if ($("#txt_observacion_no_es_socio").val() == "")
            mensaje += "Es necesario que agregue una observación.";
    } else {
        if ($("#txt_observacion_si_es_socio").val() == "")
            mensaje = "Es necesario que agregue una observación.";
    }

    return mensaje;
}